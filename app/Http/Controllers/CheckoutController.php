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
use Illuminate\Support\Facades\Route;  // إضافة Route Facade

class CheckoutController extends Controller
{
    protected $couponService;
    
    public function __construct()
    {
        // جعل CouponService اختياري لتجنب الأخطاء
        if (class_exists('App\Services\CouponService')) {
            $this->couponService = app(CouponService::class);
        }
    }
    
    /**
     * عرض صفحة الدفع
     */
    public function index()
    {
        try {
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
            $availableCoupons = collect(); // مجموعة فارغة افتراضياً
            if (class_exists('App\Models\Coupon')) {
                $availableCoupons = Coupon::where('user_id', Auth::id())
                    ->where('is_used', false)
                    ->where('valid_until', '>=', now())
                    ->get();
            }
                
            // حساب إجمالي السلة
            $cartTotal = $cart->cartItems->sum(function ($item) {
                return $item->quantity * $item->item_price;
            });
            
            // التحقق من القسيمة المطبقة في الجلسة
            $appliedCoupon = null;
            $discountAmount = 0;
            
            if (Session::has('coupon_code') && $this->couponService) {
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
            
        } catch (\Exception $e) {
            Log::error('خطأ في صفحة الدفع', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
            ]);
            
            return redirect()->route('cart.index')
                ->with('error', 'حدث خطأ في تحميل صفحة الدفع.');
        }
    }
    
    /**
     * تطبيق قسيمة على السلة
     */
    public function applyCoupon(Request $request)
    {
        if (!$this->couponService) {
            return response()->json(['message' => 'خدمة الكوبونات غير متاحة'], 503);
        }
        
        $request->validate([
            'coupon_code' => 'required|string'
        ]);
        
        $cart = Auth::user()->cart;
        
        if (!$cart || $cart->cartItems->isEmpty()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'سلة التسوق فارغة.'], 400);
            }
            return redirect()->route('cart.index')->with('error', 'سلة التسوق فارغة.');
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
            if ($request->expectsJson()) {
                return response()->json(['message' => $validation['message']], 400);
            }
            return back()->withErrors(['coupon' => $validation['message']]);
        }
        
        Session::put('coupon_code', $request->coupon_code);
        
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'تم تطبيق القسيمة بنجاح!',
                'success' => true
            ], 200);
        }
        
        return back()->with('success', 'تم تطبيق القسيمة بنجاح!');
    }
    
    /**
     * إزالة القسيمة المطبقة
     */
    public function removeCoupon(Request $request)
    {
        Session::forget('coupon_code');
        
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'تم إزالة القسيمة بنجاح!',
                'success' => true
            ], 200);
        }
        
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
            ]);
            
            $cart = Auth::user()->cart;
            
            if (!$cart || $cart->cartItems->isEmpty()) {
                return redirect()->route('cart.index')
                    ->with('error', 'سلة التسوق فارغة.');
            }
            
            $totalAmount = 0;
            
            // التحقق من المخزون وحساب الإجمالي
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
            
            if (Session::has('coupon_code') && $this->couponService) {
                $couponCode = Session::get('coupon_code');
                $couponValidation = $this->couponService->validateCoupon($couponCode, Auth::id(), $totalAmount);
                
                if ($couponValidation['valid']) {
                    $appliedCoupon = $couponValidation['coupon'];
                    $discountAmount = min($appliedCoupon->amount, $totalAmount);
                    $totalAmount -= $discountAmount;
                    
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
            
            // إنشاء عناصر الطلب وتحديث المخزون - بدون item_name
            foreach ($cart->cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    // تم حذف item_name لأنه غير موجود في الجدول
                ]);
                
                // تحديث مخزون المنتج
                $item->product->update([
                    'stock' => $item->product->stock - $item->quantity,
                ]);
            }
            
            // تطبيق القسيمة إذا تم استخدامها
            if ($appliedCoupon && $this->couponService) {
                $this->couponService->applyCoupon($order, $appliedCoupon);
                Session::forget('coupon_code');
            }
            
            // توليد قسيمة مكافأة إذا كانت الخدمة متاحة
            if ($this->couponService) {
                $rewardCoupon = $this->couponService->generateCouponForOrder($order);
                if ($rewardCoupon) {
                    Session::put('reward_coupon', $rewardCoupon->id);
                }
            }
            
            // مسح السلة
            $cart->cartItems()->delete();
            
            DB::commit();
            
            // التحقق من وجود route الشهادات - مُصحح
            if (Route::has('testimonials.create')) {
                return redirect()->route('testimonials.create', ['order' => $order->id])
                    ->with('success', 'تم تقديم الطلب بنجاح! يرجى مشاركة تجربتك معنا.');
            } elseif (Route::has('orders.show')) {
                return redirect()->route('orders.show', $order)
                    ->with('success', 'تم تقديم الطلب بنجاح!');
            } else {
                // إذا لم توجد أي من الـ routes، توجه للرئيسية
                return redirect()->route('home')
                    ->with('success', 'تم تقديم الطلب بنجاح! رقم الطلب: #' . $order->id);
            }
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('خطأ في الدفع', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'request_data' => $request->all()
            ]);
            
            return back()->with('error', 'حدث خطأ أثناء معالجة طلبك. يرجى المحاولة مرة أخرى.');
        }
    }
}