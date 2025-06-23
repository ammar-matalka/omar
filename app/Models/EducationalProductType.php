<?php
// app/Models/EducationalProductType.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationalProductType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'is_digital',
        'requires_shipping'
    ];

    protected $casts = [
        'is_digital' => 'boolean',
        'requires_shipping' => 'boolean'
    ];

    // ====================================
    // RELATIONSHIPS
    // ====================================

    /**
     * Get educational packages for this product type
     */
    public function educationalPackages()
    {
        return $this->hasMany(EducationalPackage::class, 'product_type_id');
    }

    /**
     * Get active educational packages for this product type
     */
    public function activeEducationalPackages()
    {
        return $this->hasMany(EducationalPackage::class, 'product_type_id')->where('is_active', true);
    }

    // ====================================
    // SCOPES
    // ====================================

    /**
     * Scope for digital product types
     */
    public function scopeDigital($query)
    {
        return $query->where('is_digital', true);
    }

    /**
     * Scope for physical product types
     */
    public function scopePhysical($query)
    {
        return $query->where('is_digital', false);
    }

    /**
     * Scope for product types that require shipping
     */
    public function scopeRequiresShipping($query)
    {
        return $query->where('requires_shipping', true);
    }

    /**
     * Scope for product types that don't require shipping
     */
    public function scopeNoShipping($query)
    {
        return $query->where('requires_shipping', false);
    }

    // ====================================
    // HELPER METHODS
    // ====================================

    /**
     * Check if this is a digital product type
     */
    public function isDigital()
    {
        return $this->is_digital;
    }

    /**
     * Check if this is a physical product type
     */
    public function isPhysical()
    {
        return !$this->is_digital;
    }

    /**
     * Check if this product type requires shipping
     */
    public function requiresShipping()
    {
        return $this->requires_shipping;
    }

    /**
     * Get display type
     */
    public function getDisplayTypeAttribute()
    {
        return $this->is_digital ? 'رقمي' : 'ورقي';
    }

    /**
     * Get shipping status
     */
    public function getShippingStatusAttribute()
    {
        return $this->requires_shipping ? 'مع شحن' : 'بدون شحن';
    }

    /**
     * Get full description
     */
    public function getFullDescriptionAttribute()
    {
        return "{$this->name} ({$this->display_type} - {$this->shipping_status})";
    }
}