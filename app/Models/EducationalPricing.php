<?php
// app/Models/EducationalPricing.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationalPricing extends Model
{
    use HasFactory;

    protected $table = 'educational_pricing';

    protected $fillable = [
        'generation_id',
        'subject_id',
        'teacher_id',
        'platform_id',
        'package_id',
        'region_id',
        'price',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    // ====================================
    // RELATIONSHIPS
    // ====================================

    /**
     * Get the generation for this pricing
     */
    public function generation()
    {
        return $this->belongsTo(Generation::class);
    }

    /**
     * Get the subject for this pricing
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the teacher for this pricing
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the platform for this pricing
     */
    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    /**
     * Get the package for this pricing
     */
    public function package()
    {
        return $this->belongsTo(EducationalPackage::class, 'package_id');
    }

    /**
     * Get the shipping region for this pricing (nullable for digital products)
     */
    public function region()
    {
        return $this->belongsTo(ShippingRegion::class, 'region_id');
    }

    // ====================================
    // SCOPES
    // ====================================

    /**
     * Scope for active pricing
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for pricing by generation
     */
    public function scopeByGeneration($query, $generationId)
    {
        return $query->where('generation_id', $generationId);
    }

    /**
     * Scope for pricing by subject
     */
    public function scopeBySubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    /**
     * Scope for pricing by teacher
     */
    public function scopeByTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    /**
     * Scope for pricing by platform
     */
    public function scopeByPlatform($query, $platformId)
    {
        return $query->where('platform_id', $platformId);
    }

    /**
     * Scope for pricing by package
     */
    public function scopeByPackage($query, $packageId)
    {
        return $query->where('package_id', $packageId);
    }

    /**
     * Scope for pricing by region
     */
    public function scopeByRegion($query, $regionId)
    {
        return $query->where('region_id', $regionId);
    }

    /**
     * Scope for digital product pricing (no region)
     */
    public function scopeDigital($query)
    {
        return $query->whereNull('region_id');
    }

    /**
     * Scope for physical product pricing (with region)
     */
    public function scopePhysical($query)
    {
        return $query->whereNotNull('region_id');
    }

    /**
     * Scope for complete pricing match
     */
    public function scopeForSelection($query, $generationId, $subjectId, $teacherId, $platformId, $packageId, $regionId = null)
    {
        return $query->where('generation_id', $generationId)
                    ->where('subject_id', $subjectId)
                    ->where('teacher_id', $teacherId)
                    ->where('platform_id', $platformId)
                    ->where('package_id', $packageId)
                    ->where('region_id', $regionId);
    }

    // ====================================
    // ACCESSORS
    // ====================================

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2) . ' دينار';
    }

    /**
     * Get total cost including shipping
     */
    public function getTotalCostAttribute()
    {
        $total = $this->price;
        
        if ($this->region) {
            $total += $this->region->shipping_cost;
        }
        
        return $total;
    }

    /**
     * Get formatted total cost
     */
    public function getFormattedTotalCostAttribute()
    {
        return number_format($this->total_cost, 2) . ' دينار';
    }

    /**
     * Get shipping cost
     */
    public function getShippingCostAttribute()
    {
        return $this->region ? $this->region->shipping_cost : 0;
    }

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
     * Check if this is digital pricing
     */
    public function getIsDigitalAttribute()
    {
        return is_null($this->region_id);
    }

    /**
     * Get complete product info
     */
    public function getProductInfoAttribute()
    {
        $info = [
            'generation' => $this->generation->display_name,
            'subject' => $this->subject->name,
            'teacher' => $this->teacher->name,
            'platform' => $this->platform->name,
            'package' => $this->package->name,
            'type' => $this->package->package_type,
            'price' => $this->price,
            'shipping_cost' => $this->shipping_cost,
            'total_cost' => $this->total_cost,
            'is_digital' => $this->is_digital
        ];

        if ($this->region) {
            $info['region'] = $this->region->name;
        }

        return $info;
    }

    // ====================================
    // HELPER METHODS
    // ====================================

    /**
     * Check if this pricing is for digital product
     */
    public function isDigital()
    {
        return $this->is_digital;
    }

    /**
     * Check if this pricing is for physical product
     */
    public function isPhysical()
    {
        return !$this->is_digital;
    }

    /**
     * Check if shipping is required
     */
    public function requiresShipping()
    {
        return $this->isPhysical();
    }

    /**
     * Check if shipping is free
     */
    public function hasFreeShipping()
    {
        return $this->shipping_cost == 0;
    }

    /**
     * Calculate price for quantity
     */
    public function calculateTotal($quantity = 1)
    {
        return [
            'unit_price' => $this->price,
            'shipping_cost' => $this->shipping_cost,
            'subtotal' => $this->price * $quantity,
            'total_shipping' => $this->requiresShipping() ? $this->shipping_cost : 0,
            'total_cost' => ($this->price * $quantity) + ($this->requiresShipping() ? $this->shipping_cost : 0)
        ];
    }
}