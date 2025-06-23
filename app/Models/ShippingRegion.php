<?php
// app/Models/ShippingRegion.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingRegion extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'shipping_cost',
        'is_active'
    ];

    protected $casts = [
        'shipping_cost' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    // ====================================
    // RELATIONSHIPS
    // ====================================

    /**
     * Get educational pricing for this region
     */
    public function educationalPricing()
    {
        return $this->hasMany(EducationalPricing::class, 'region_id');
    }

    /**
     * Get order items for this region
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'region_id');
    }

    // ====================================
    // SCOPES
    // ====================================

    /**
     * Scope for active regions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for free shipping regions
     */
    public function scopeFreeShipping($query)
    {
        return $query->where('shipping_cost', 0);
    }

    /**
     * Scope for paid shipping regions
     */
    public function scopePaidShipping($query)
    {
        return $query->where('shipping_cost', '>', 0);
    }

    // ====================================
    // ACCESSORS
    // ====================================

    /**
     * Get formatted shipping cost
     */
    public function getFormattedShippingCostAttribute()
    {
        if ($this->shipping_cost == 0) {
            return 'شحن مجاني';
        }

        return number_format($this->shipping_cost, 2) . ' دينار';
    }

    /**
     * Get shipping status
     */
    public function getShippingStatusAttribute()
    {
        return $this->shipping_cost == 0 ? 'مجاني' : 'مدفوع';
    }

    /**
     * Get full region info
     */
    public function getFullInfoAttribute()
    {
        return "{$this->name} - {$this->formatted_shipping_cost}";
    }

    // ====================================
    // HELPER METHODS
    // ====================================

    /**
     * Check if shipping is free
     */
    public function isFreeShipping()
    {
        return $this->shipping_cost == 0;
    }

    /**
     * Check if shipping is paid
     */
    public function isPaidShipping()
    {
        return $this->shipping_cost > 0;
    }

    /**
     * Calculate shipping cost for weight
     */
    public function calculateShippingForWeight($weightInGrams)
    {
        // Base shipping cost - can be extended for weight-based calculation
        return $this->shipping_cost;
    }

    /**
     * Get orders count for this region
     */
    public function getOrdersCountAttribute()
    {
        return $this->orderItems()->count();
    }
}