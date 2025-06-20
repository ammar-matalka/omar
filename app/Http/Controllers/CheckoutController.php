<?php

// ===================================
// CheckoutController - وحدة تحكم عملية الشراء
// ===================================

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

class CheckoutController extends Controller
{
    protected $couponService;
    
    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }
    
    /**
     * عرض صفحة الدفع
     */
    public function index()
    {
        $cart = Auth::user()->cart;
        
        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'سلة التسوق فارغة.');
        }
        
        // جلب الفئات للتخطيط
        $categories = Category::all();
        
        // المنتجات المميزة للتوصيات
        $featuredProducts = Product::where('is_active', true)
            ->where('featured', true)
            ->latest()
            ->take(4)
            ->get();
        
        // الحصول على القسائم المتاحة للمستخدم
        $availableCoupons = Coupon::where('user_id', Auth::id())
            ->where('is_used', false)
            ->where('valid_until', '>=', now())
            ->get();
            
        // حساب إجمالي السلة
        $cartTotal = $cart->cartItems->sum(function ($item) {
            return $item->quantity * $item->item_price;
        });
        
        // التحقق من القسيمة المطبقة في الجلسة
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
    
    /**
     * تطبيق قسيمة على السلة
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string'
        ], [
            'coupon_code.required' => 'رمز القسيمة مطلوب.',
            'coupon_code.string' => 'رمز القسيمة يجب أن يكون نص.',
        ]);
        
        $cart = Auth::user()->cart;
        
        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'سلة التسوق فارغة.');
        }
        
        $cartTotal = $cart->cartItems->sum(function ($item) {
            return $item->quantity * $item->item_price;
        });
        
        $validation = $this->couponService->validateCoupon(
            $request->coupon_code,
            Auth::id(),
            $cartTotal
        );
        
        if (!$validation['valid']) {
            return back()->withErrors(['coupon' => $validation['message']]);
        }
        
        // حفظ رمز القسيمة في الجلسة
        Session::put('coupon_code', $request->coupon_code);
        
        return back()->with('success', 'تم تطبيق القسيمة بنجاح!');
    }
    
    /**
     * إزالة القسيمة المطبقة
     */
    public function removeCoupon()
    {
        Session::forget('coupon_code');
        return back()->with('success', 'تم إزالة القسيمة بنجاح!');
    }
    
    /**
     * معالجة الدفع وإنشاء الطلب
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'payment_method' => 'required|in:cash',
                'shipping_address' => 'required|string|max:500',
                'billing_address' => 'required|string|max:500',
                'phone_number' => 'required|string|max:20',
            ], [
                'payment_method.required' => 'طريقة الدفع مطلوبة.',
                'payment_method.in' => 'طريقة الدفع يجب أن تكون الدفع عند الاستلام.',
                'shipping_address.required' => 'عنوان الشحن مطلوب.',
                'shipping_address.string' => 'عنوان الشحن يجب أن يكون نص.',
                'shipping_address.max' => 'عنوان الشحن لا يجب أن يتجاوز 500 حرف.',
                'billing_address.required' => 'عنوان الفوترة مطلوب.',
                'billing_address.string' => 'عنوان الفوترة يجب أن يكون نص.',
                'billing_address.max' => 'عنوان الفوترة لا يجب أن يتجاوز 500 حرف.',
                'phone_number.required' => 'رقم الهاتف مطلوب.',
                'phone_number.string' => 'رقم الهاتف يجب أن يكون نص.',
                'phone_number.max' => 'رقم الهاتف لا يجب أن يتجاوز 20 حرف.',
            ]);
            
            $cart = Auth::user()->cart;
            
            if (!$cart || $cart->cartItems->isEmpty()) {
                return redirect()->route('cart.index')
                    ->with('error', 'سلة التسوق فارغة.');
            }
            
            $totalAmount = 0;
            
            // التحقق من المخزون وحساب الإجمالي لجميع العناصر
            foreach ($cart->cartItems as $item) {
                if (!$item->product) {
                    return back()->with('error', 'واحد أو أكثر من المنتجات في سلتك لم تعد متوفرة.');
                }
                
                if (!$item->product->is_active) {
                    return back()->with('error', 'المنتج "' . $item->product->name . '" لم يعد متوفراً.');
                }
                
                if ($item->product->stock < $item->quantity) {
                    return back()->with('error', 'مخزون غير كافٍ للمنتج "' . $item->product->name . '". المتاح: ' . $item->product->stock);
                }
                
                $totalAmount += $item->quantity * $item->product->price;
            }
            
            // التعامل مع خصم القسيمة
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
                    
                    // حفظ تفاصيل القسيمة للطلب
                    $couponDetails = [
                        'code' => $appliedCoupon->code,
                        'amount' => $appliedCoupon->amount,
                        'discount_applied' => $discountAmount
                    ];
                }
            }
            
            DB::beginTransaction();
            
            // إنشاء الطلب
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $totalAmount,
                'discount_amount' => $discountAmount,
                'status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'shipping_address' => $validated['shipping_address'],
                'billing_address' => $validated['billing_address'],
                'phone_number' => $validated['phone_number'],
                'coupon_details' => $couponDetails ? json_encode($couponDetails) : null,
            ]);
            
            // إنشاء عناصر الطلب وتحديث المخزون
            foreach ($cart->cartItems as $item) {
                // إنشاء عنصر الطلب
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'item_name' => $item->product->name, // حفظ الاسم وقت الشراء
                ]);
                
                // تحديث مخزون المنتج
                $item->product->update([
                    'stock' => $item->product->stock - $item->quantity,
                ]);
            }
            
            // تطبيق القسيمة إذا تم استخدامها
            if ($appliedCoupon) {
                $this->couponService->applyCoupon($order, $appliedCoupon);
                Session::forget('coupon_code');
            }
            
            // توليد قسيمة مكافأة للطلب إذا كان مؤهلاً
            $rewardCoupon = $this->couponService->generateCouponForOrder($order);
            
            // حفظ قسيمة المكافأة في الجلسة لعرضها في صفحة الشكر
            if ($rewardCoupon) {
                Session::put('reward_coupon', $rewardCoupon->id);
            }
            
            // مسح السلة
            $cart->cartItems()->delete();
            
            DB::commit();
            
            // إعادة التوجيه إلى نموذج الشهادة
            return redirect()->route('testimonials.create', ['order' => $order->id])
                ->with('success', 'تم تقديم الطلب بنجاح! يرجى مشاركة تجربتك معنا.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            // تسجيل الخطأ للتشخيص
            Log::error('خطأ في الدفع', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
            ]);
            
            return back()->with('error', 'حدث خطأ أثناء معالجة طلبك. يرجى المحاولة مرة أخرى.');
        }
    }
}