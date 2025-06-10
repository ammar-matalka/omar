<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Order;
use Illuminate\Support\Str;

class CouponService
{
    /**
     * Generate a coupon for the given order based on purchase amount
     *
     * @param Order $order
     * @return Coupon|null
     */
    public function generateCouponForOrder(Order $order)
    {
        $orderTotal = $order->total_amount;
        
        // No coupon for orders less than $50
        if ($orderTotal < 50) {
            return null;
        }
        
        // Determine coupon amount based on purchase total
        $couponAmount = $this->calculateCouponAmount($orderTotal);
        
        // Generate a unique coupon code
        $code = $this->generateUniqueCode();
        
        // Create the coupon
        $coupon = Coupon::create([
            'code' => $code,
            'amount' => $couponAmount,
            'min_purchase_amount' => 0, // Can be used on any purchase
            'valid_from' => now(),
            'valid_until' => now()->addMonths(3), // Valid for 3 months
            'is_used' => false,
            'user_id' => $order->user_id,
            'order_id' => null, // This will be set when the coupon is used
        ]);
        
        return $coupon;
    }
    
    /**
     * Calculate the coupon amount based on the order total
     *
     * @param float $orderTotal
     * @return float
     */
    private function calculateCouponAmount($orderTotal)
    {
        // Tiered coupon system
        if ($orderTotal >= 100) {
            return 20.00; // $20 coupon for orders $100+
        } else {
            return 10.00; // $10 coupon for orders $50-$99.99
        }
        if ($orderTotal >= 100) {
            return 20.00; // $20 coupon for orders $100+
        } else {
            return 10.00; // $10 coupon for orders $50-$99.99
        }
    }
    
    /**
     * Generate a unique coupon code
     *
     * @return string
     */
    private function generateUniqueCode()
    {
        $code = strtoupper(Str::random(8));
        
        // Ensure code is unique
        while (Coupon::where('code', $code)->exists()) {
            $code = strtoupper(Str::random(8));
        }
        
        return $code;
    }
    
    /**
     * Validate a coupon for a user and order total
     *
     * @param string $code
     * @param int $userId
     * @param float $orderTotal
     * @return array
     */
    public function validateCoupon($code, $userId, $orderTotal)
    {
        $coupon = Coupon::where('code', $code)
            ->where('user_id', $userId)
            ->where('is_used', false)
            ->where('valid_from', '<=', now())
            ->where('valid_until', '>=', now())
            ->first();
            
        if (!$coupon) {
            return [
                'valid' => false,
                'message' => 'Invalid or expired coupon'
            ];
        }
        
        if ($orderTotal < $coupon->min_purchase_amount) {
            return [
                'valid' => false,
                'message' => "Order total must be at least $" . $coupon->min_purchase_amount
            ];
        }
        
        return [
            'valid' => true,
            'coupon' => $coupon
        ];
    }
    
    /**
     * Apply a coupon to an order
     *
     * @param Order $order
     * @param Coupon $coupon
     * @return Order
     */
    public function applyCoupon(Order $order, Coupon $coupon)
    {
        // Update the order with the discount
        $discountAmount = min($coupon->amount, $order->total_amount);
        $newTotal = $order->total_amount - $discountAmount;
        
        $order->update([
            'discount_amount' => $discountAmount,
            'total_amount' => $newTotal
        ]);
        
        // Mark coupon as used
        $coupon->update([
            'is_used' => true,
            'order_id' => $order->id
        ]);
        
        return $order;
    }
}