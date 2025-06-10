<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
        'payment_method',
        'shipping_address',
        'billing_address',
        'phone_number',
        'discount_amount',
        'coupon_details' // JSON field to store coupon information
    ];

    protected $casts = [
        'coupon_details' => 'array',
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function testimonial()
    {
        return $this->hasOne(Testimonial::class);
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }

    public function appliedCoupon()
    {
        return $this->hasOne(Coupon::class, 'order_id')->where('is_used', true);
    }
    
    /**
     * Get the original amount before discount
     */
    public function getOriginalAmountAttribute()
    {
        return $this->total_amount + $this->discount_amount;
    }
    
    /**
     * Check if order has coupon applied
     */
    public function hasCoupon()
    {
        return !empty($this->coupon_details) || $this->discount_amount > 0;
    }
    
    /**
     * Get coupon code if applied
     */
    public function getCouponCodeAttribute()
    {
        if ($this->coupon_details && isset($this->coupon_details['code'])) {
            return $this->coupon_details['code'];
        }
        
        $appliedCoupon = $this->appliedCoupon;
        return $appliedCoupon ? $appliedCoupon->code : null;
    }
    
    /**
     * Get coupon discount amount
     */
    public function getCouponDiscountAttribute()
    {
        if ($this->coupon_details && isset($this->coupon_details['discount_applied'])) {
            return $this->coupon_details['discount_applied'];
        }
        
        return $this->discount_amount;
    }
    
    /**
     * Get formatted status for display
     */
    public function getFormattedStatusAttribute()
    {
        $statuses = [
            'pending' => ['label' => __('Pending'), 'class' => 'warning'],
            'processing' => ['label' => __('Processing'), 'class' => 'info'],
            'shipped' => ['label' => __('Shipped'), 'class' => 'primary'],
            'delivered' => ['label' => __('Delivered'), 'class' => 'success'],
            'cancelled' => ['label' => __('Cancelled'), 'class' => 'danger'],
        ];
        
        return $statuses[$this->status] ?? ['label' => $this->status, 'class' => 'secondary'];
    }
    
    /**
     * Calculate total quantity of items
     */
    public function getTotalQuantityAttribute()
    {
        return $this->orderItems->sum('quantity');
    }
    
    /**
     * Get order summary for admin
     */
    public function getOrderSummaryAttribute()
    {
        $summary = [
            'total_items' => $this->orderItems->count(),
            'total_quantity' => $this->total_quantity,
            'subtotal' => $this->original_amount,
            'discount' => $this->discount_amount,
            'final_total' => $this->total_amount,
            'has_coupon' => $this->hasCoupon(),
            'coupon_code' => $this->coupon_code,
        ];
        
        if ($this->hasCoupon()) {
            $summary['coupon_details'] = $this->coupon_details;
        }
        
        return $summary;
    }
}