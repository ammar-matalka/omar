<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\Coupon;
use App\Services\CouponService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Models\Cart;

class CheckoutController extends Controller
{
    protected $couponService;
    
    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }
    
    public function index()
    {
        $cart = Auth::user()->cart;
        
        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', __('Your cart is empty.'));
        }
        
        // Fetch categories for the layout
        $categories = Category::all();
        
        // Featured products for recommendations
        $featuredProducts = Product::where('is_active', true)->latest()->take(4)->get();
        
        // Get available coupons for the user
        $availableCoupons = Coupon::where('user_id', Auth::id())
            ->where('is_used', false)
            ->where('valid_until', '>=', now())
            ->get();
            
        // Calculate cart total
        $cartTotal = 0;
        foreach ($cart->cartItems as $item) {
            $cartTotal += $item->item_price * $item->quantity;
        }
        
        // Check for applied coupon in session
        $appliedCoupon = null;
        $discountAmount = 0;
        
        if (Session::has('coupon_code')) {
            $couponCode = Session::get('coupon_code');
            $couponValidation = $this->couponService->validateCoupon($couponCode, Auth::id(), $cartTotal);
            
            if ($couponValidation['valid']) {
                $appliedCoupon = $couponValidation['coupon'];
                $discountAmount = min($appliedCoupon->amount, $cartTotal);
            }
        }
        
        return view('checkout.index', compact(
            'cart', 
            'categories', 
            'featuredProducts', 
            'availableCoupons', 
            'appliedCoupon', 
            'discountAmount', 
            'cartTotal'
        ));
    }
    
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string'
        ]);
        
        $cart = Auth::user()->cart;
        
        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', __('Your cart is empty.'));
        }
        
        $cartTotal = 0;
        foreach ($cart->cartItems as $item) {
            $cartTotal += $item->item_price * $item->quantity;
        }
        
        $validation = $this->couponService->validateCoupon(
            $request->coupon_code,
            Auth::id(),
            $cartTotal
        );
        
        if (!$validation['valid']) {
            return back()->withErrors(['coupon' => $validation['message']]);
        }
        
        // Store the coupon code in the session
        Session::put('coupon_code', $request->coupon_code);
        
        return back()->with('success', __('Coupon applied successfully!'));
    }
    
    public function removeCoupon()
    {
        Session::forget('coupon_code');
        return back()->with('success', __('Coupon removed successfully!'));
    }
    
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'payment_method' => 'required|in:cash',
                'shipping_address' => 'required|string',
                'billing_address' => 'required|string',
                'phone_number' => 'required|string',
            ]);
            
            $cart = Auth::user()->cart;
            
            if (!$cart || $cart->cartItems->isEmpty()) {
                return redirect()->route('cart.index')
                    ->with('error', __('Your cart is empty.'));
            }
            
            $totalAmount = 0;
            
            // Validate stock for all items
            foreach ($cart->cartItems as $item) {
                $itemStock = $item->type === 'educational_card' 
                    ? $item->educationalCard->stock 
                    : $item->product->stock;
                    
                if ($itemStock < $item->quantity) {
                    $itemName = $item->item_name;
                    return back()->with('error', __("Not enough stock for :item.", ['item' => $itemName]));
                }
                
                $totalAmount += $item->item_price * $item->quantity;
            }
            
            // Handle coupon discount
            $discountAmount = 0;
            $appliedCoupon = null;
            $couponDetails = null;
            
            if (Session::has('coupon_code')) {
                $couponCode = Session::get('coupon_code');
                $couponValidation = $this->couponService->validateCoupon($couponCode, Auth::id(), $totalAmount);
                
                if ($couponValidation['valid']) {
                    $appliedCoupon = $couponValidation['coupon'];
                    $discountAmount = min($appliedCoupon->amount, $totalAmount);
                    $totalAmount -= $discountAmount;
                    
                    // Store coupon details for order
                    $couponDetails = [
                        'code' => $appliedCoupon->code,
                        'amount' => $appliedCoupon->amount,
                        'discount_applied' => $discountAmount
                    ];
                }
            }
            
            DB::beginTransaction();
            
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $totalAmount,
                'discount_amount' => $discountAmount,
                'status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'shipping_address' => $validated['shipping_address'],
                'billing_address' => $validated['billing_address'],
                'phone_number' => $validated['phone_number'],
                'coupon_details' => $couponDetails ? json_encode($couponDetails) : null, // Store coupon info
            ]);
            
            foreach ($cart->cartItems as $item) {
                // Create order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'educational_card_id' => $item->educational_card_id,
                    'quantity' => $item->quantity,
                    'price' => $item->item_price,
                    'type' => $item->type,
                    'item_name' => $item->item_name, // Store name at purchase time
                ]);
                
                // Update stock
                if ($item->type === 'educational_card') {
                    $item->educationalCard->update([
                        'stock' => $item->educationalCard->stock - $item->quantity,
                    ]);
                } else {
                    $item->product->update([
                        'stock' => $item->product->stock - $item->quantity,
                    ]);
                }
            }
            
            // Apply coupon if one was used
            if ($appliedCoupon) {
                $this->couponService->applyCoupon($order, $appliedCoupon);
                Session::forget('coupon_code');
            }
            
            // Generate a reward coupon for the order if eligible
            $rewardCoupon = $this->couponService->generateCouponForOrder($order);
            
            // Store reward coupon in session to display on the thank you page
            if ($rewardCoupon) {
                Session::put('reward_coupon', $rewardCoupon->id);
            }
            
            $cart->cartItems()->delete();
            
            DB::commit();
            
            // Redirect to testimonial form with coupon info
            return redirect()->route('testimonials.create', ['order' => $order->id])
                ->with('success', __('Order placed successfully! Please share your experience with us.'));
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log the error for debugging
            Log::error('Checkout error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', __('An error occurred while processing your order: :message', ['message' => $e->getMessage()]));
        }
    }
}