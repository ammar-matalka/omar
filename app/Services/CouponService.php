<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CouponService
{
    /**
     * التحقق من صحة القسيمة
     */
    public function validateCoupon($code, $userId, $cartTotal)
    {
        $coupon = Coupon::where('code', $code)->first();
        
        if (!$coupon) {
            return [
                'valid' => false,
                'message' => 'كود القسيمة غير صحيح.'
            ];
        }
        
        if ($coupon->is_used) {
            return [
                'valid' => false,
                'message' => 'تم استخدام هذه القسيمة من قبل.'
            ];
        }
        
        if ($coupon->user_id !== $userId) {
            return [
                'valid' => false,
                'message' => 'هذه القسيمة غير متاحة لك.'
            ];
        }
        
        if ($coupon->valid_until < now()) {
            return [
                'valid' => false,
                'message' => 'انتهت صلاحية هذه القسيمة.'
            ];
        }
        
        if ($coupon->minimum_amount && $cartTotal < $coupon->minimum_amount) {
            return [
                'valid' => false,
                'message' => 'الحد الأدنى للطلب هو $' . number_format($coupon->minimum_amount, 2) . ' لاستخدام هذه القسيمة.'
            ];
        }
        
        return [
            'valid' => true,
            'coupon' => $coupon
        ];
    }
    
    /**
     * تطبيق القسيمة على الطلب
     */
    public function applyCoupon(Order $order, Coupon $coupon)
    {
        $coupon->update([
            'is_used' => true,
            'used_at' => now(),
            'order_id' => $order->id
        ]);
        
        return true;
    }
    
    /**
     * توليد قسيمة مكافأة للطلب
     */
    public function generateCouponForOrder(Order $order)
    {
        // شروط توليد القسيمة
        $minimumOrderAmount = 50; // الحد الأدنى للطلب
        $rewardPercentage = 10; // نسبة المكافأة
        
        if ($order->total_amount < $minimumOrderAmount) {
            return null;
        }
        
        $rewardAmount = ($order->total_amount * $rewardPercentage) / 100;
        $rewardAmount = min($rewardAmount, 20); // حد أقصى للمكافأة
        
        $coupon = Coupon::create([
            'code' => 'REWARD-' . strtoupper(Str::random(8)),
            'amount' => $rewardAmount,
            'user_id' => $order->user_id,
            'type' => 'reward',
            'is_used' => false,
            'valid_until' => now()->addMonths(3), // صالحة لمدة 3 أشهر
            'minimum_amount' => 0,
            'generated_from_order_id' => $order->id,
            'description' => 'قسيمة مكافأة للطلب رقم #' . $order->id
        ]);
        
        return $coupon;
    }
    
    /**
     * الحصول على القسائم المتاحة للمستخدم
     */
    public function getAvailableCoupons($userId)
    {
        return Coupon::where('user_id', $userId)
            ->where('is_used', false)
            ->where('valid_until', '>=', now())
            ->orderBy('created_at', 'desc')
            ->get();
    }
    
    /**
     * توليد كود قسيمة عشوائي
     */
    public function generateCouponCode($prefix = '')
    {
        $code = $prefix . strtoupper(Str::random(8));
        
        // التأكد من عدم وجود الكود من قبل
        while (Coupon::where('code', $code)->exists()) {
            $code = $prefix . strtoupper(Str::random(8));
        }
        
        return $code;
    }
    
    /**
     * حساب الخصم المطبق
     */
    public function calculateDiscount(Coupon $coupon, $orderTotal)
    {
        if ($coupon->type === 'percentage') {
            $discount = ($orderTotal * $coupon->amount) / 100;
            
            // تطبيق الحد الأقصى للخصم إذا وُجد
            if ($coupon->max_discount_amount) {
                $discount = min($discount, $coupon->max_discount_amount);
            }
            
            return $discount;
        }
        
        // خصم ثابت
        return min($coupon->amount, $orderTotal);
    }
    
    /**
     * إحصائيات استخدام القسائم
     */
    public function getCouponStats($userId = null)
    {
        $query = Coupon::query();
        
        if ($userId) {
            $query->where('user_id', $userId);
        }
        
        return [
            'total_coupons' => $query->count(),
            'used_coupons' => $query->where('is_used', true)->count(),
            'available_coupons' => $query->where('is_used', false)
                                        ->where('valid_until', '>=', now())
                                        ->count(),
            'expired_coupons' => $query->where('is_used', false)
                                      ->where('valid_until', '<', now())
                                      ->count(),
            'total_savings' => $query->where('is_used', true)
                                    ->sum('amount')
        ];
    }
}