<?php
// app/Models/EducationalInventory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationalInventory extends Model
{
    use HasFactory;

    protected $table = 'educational_inventory';

    protected $fillable = [
        'generation_id',
        'subject_id',
        'teacher_id',
        'platform_id',
        'package_id',
        'quantity_available',
        'quantity_reserved'
    ];

    protected $casts = [
        'quantity_available' => 'integer',
        'quantity_reserved' => 'integer'
    ];

    // ====================================
    // RELATIONSHIPS
    // ====================================

    /**
     * Get the generation for this inventory
     */
    public function generation()
    {
        return $this->belongsTo(Generation::class);
    }

    /**
     * Get the subject for this inventory
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the teacher for this inventory
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the platform for this inventory
     */
    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    /**
     * Get the package for this inventory
     */
    public function package()
    {
        return $this->belongsTo(EducationalPackage::class, 'package_id');
    }

    // ====================================
    // SCOPES
    // ====================================

    /**
     * Scope for inventory by generation
     */
    public function scopeByGeneration($query, $generationId)
    {
        return $query->where('generation_id', $generationId);
    }

    /**
     * Scope for inventory by subject
     */
    public function scopeBySubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    /**
     * Scope for inventory by teacher
     */
    public function scopeByTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    /**
     * Scope for inventory by platform
     */
    public function scopeByPlatform($query, $platformId)
    {
        return $query->where('platform_id', $platformId);
    }

    /**
     * Scope for inventory by package
     */
    public function scopeByPackage($query, $packageId)
    {
        return $query->where('package_id', $packageId);
    }

    /**
     * Scope for available inventory
     */
    public function scopeAvailable($query)
    {
        return $query->where('quantity_available', '>', 0);
    }

    /**
     * Scope for out of stock inventory
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('quantity_available', '<=', 0);
    }

    /**
     * Scope for low stock inventory
     */
    public function scopeLowStock($query, $threshold = 10)
    {
        return $query->where('quantity_available', '>', 0)
                    ->where('quantity_available', '<=', $threshold);
    }

    /**
     * Scope for complete inventory match
     */
    public function scopeForSelection($query, $generationId, $subjectId, $teacherId, $platformId, $packageId)
    {
        return $query->where('generation_id', $generationId)
                    ->where('subject_id', $subjectId)
                    ->where('teacher_id', $teacherId)
                    ->where('platform_id', $platformId)
                    ->where('package_id', $packageId);
    }

    // ====================================
    // ACCESSORS
    // ====================================

    /**
     * Get total quantity (available + reserved)
     */
    public function getTotalQuantityAttribute()
    {
        return $this->quantity_available + $this->quantity_reserved;
    }

    /**
     * Get actual available quantity (available - reserved)
     */
    public function getActualAvailableAttribute()
    {
        return max(0, $this->quantity_available - $this->quantity_reserved);
    }

    /**
     * Get stock status
     */
    public function getStockStatusAttribute()
    {
        $available = $this->actual_available;
        
        if ($available <= 0) {
            return 'نفدت الكمية';
        } elseif ($available <= 10) {
            return 'كمية قليلة';
        } elseif ($available <= 50) {
            return 'متوفر';
        } else {
            return 'متوفر بكثرة';
        }
    }

    /**
     * Get stock status class for styling
     */
    public function getStockStatusClassAttribute()
    {
        $available = $this->actual_available;
        
        if ($available <= 0) {
            return 'danger';
        } elseif ($available <= 10) {
            return 'warning';
        } elseif ($available <= 50) {
            return 'info';
        } else {
            return 'success';
        }
    }

    /**
     * Get product info for this inventory
     */
    public function getProductInfoAttribute()
    {
        return [
            'generation' => $this->generation->display_name,
            'subject' => $this->subject->name,
            'teacher' => $this->teacher->name,
            'platform' => $this->platform->name,
            'package' => $this->package->name,
            'quantity_available' => $this->quantity_available,
            'quantity_reserved' => $this->quantity_reserved,
            'actual_available' => $this->actual_available,
            'stock_status' => $this->stock_status
        ];
    }

    // ====================================
    // HELPER METHODS
    // ====================================

    /**
     * Check if item is in stock
     */
    public function isInStock($quantity = 1)
    {
        return $this->actual_available >= $quantity;
    }

    /**
     * Check if item is out of stock
     */
    public function isOutOfStock()
    {
        return $this->actual_available <= 0;
    }

    /**
     * Check if item has low stock
     */
    public function hasLowStock($threshold = 10)
    {
        $available = $this->actual_available;
        return $available > 0 && $available <= $threshold;
    }

    /**
     * Reserve quantity for order
     */
    public function reserveQuantity($quantity)
    {
        if (!$this->isInStock($quantity)) {
            return false;
        }

        $this->increment('quantity_reserved', $quantity);
        return true;
    }

    /**
     * Release reserved quantity
     */
    public function releaseReservedQuantity($quantity)
    {
        $this->decrement('quantity_reserved', min($quantity, $this->quantity_reserved));
    }

    /**
     * Confirm sale (reduce available and reserved quantities)
     */
    public function confirmSale($quantity)
    {
        if (!$this->isInStock($quantity)) {
            return false;
        }

        $this->decrement('quantity_available', $quantity);
        $this->decrement('quantity_reserved', min($quantity, $this->quantity_reserved));
        return true;
    }

    /**
     * Add stock
     */
    public function addStock($quantity)
    {
        $this->increment('quantity_available', $quantity);
    }

    /**
     * Set stock level
     */
    public function setStock($quantity)
    {
        $this->update(['quantity_available' => $quantity]);
    }

    /**
     * Get maximum orderable quantity
     */
    public function getMaxOrderableQuantity()
    {
        return $this->actual_available;
    }
}