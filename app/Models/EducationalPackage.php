<?php
// app/Models/EducationalPackage.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationalPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_type_id',
        'platform_id',
        'name',
        'description',
        'duration_days',
        'lessons_count',
        'pages_count',
        'weight_grams',
        'is_active'
    ];

    protected $casts = [
        'duration_days' => 'integer',
        'lessons_count' => 'integer',
        'pages_count' => 'integer',
        'weight_grams' => 'integer',
        'is_active' => 'boolean'
    ];

    // ====================================
    // RELATIONSHIPS
    // ====================================

    /**
     * Get the product type that owns this package
     */
    public function productType()
    {
        return $this->belongsTo(EducationalProductType::class, 'product_type_id');
    }

    /**
     * Get the platform that owns this package
     */
    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    /**
     * Get the teacher through platform
     */
    public function teacher()
    {
        return $this->hasOneThrough(Teacher::class, Platform::class);
    }

    /**
     * Get the subject through platform and teacher
     */
    public function subject()
    {
        return $this->hasOneThrough(
            Subject::class,
            Teacher::class,
            'id', // Foreign key on teachers table
            'id', // Foreign key on subjects table
            'platform_id', // Local key on packages table (through platform)
            'subject_id' // Local key on teachers table
        )->join('platforms', 'platforms.teacher_id', '=', 'teachers.id')
         ->where('platforms.id', $this->platform_id);
    }

    /**
     * Get educational pricing for this package
     */
    public function educationalPricing()
    {
        return $this->hasMany(EducationalPricing::class, 'package_id');
    }

    /**
     * Get educational inventory for this package
     */
    public function educationalInventory()
    {
        return $this->hasMany(EducationalInventory::class, 'package_id');
    }

    /**
     * Get educational cards for this package
     */
    public function educationalCards()
    {
        return $this->hasMany(EducationalCard::class, 'package_id');
    }

    /**
     * Get order items for this package
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'package_id');
    }

    // ====================================
    // SCOPES
    // ====================================

    /**
     * Scope for active packages
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for packages by platform
     */
    public function scopeByPlatform($query, $platformId)
    {
        return $query->where('platform_id', $platformId);
    }

    /**
     * Scope for packages by product type
     */
    public function scopeByProductType($query, $productTypeId)
    {
        return $query->where('product_type_id', $productTypeId);
    }

    /**
     * Scope for digital packages
     */
    public function scopeDigital($query)
    {
        return $query->whereHas('productType', function($q) {
            $q->where('is_digital', true);
        });
    }

    /**
     * Scope for physical packages
     */
    public function scopePhysical($query)
    {
        return $query->whereHas('productType', function($q) {
            $q->where('is_digital', false);
        });
    }

    // ====================================
    // ACCESSORS
    // ====================================

    /**
     * Check if this is a digital package
     */
    public function getIsDigitalAttribute()
    {
        return $this->productType ? $this->productType->is_digital : false;
    }

    /**
     * Check if this package requires shipping
     */
    public function getRequiresShippingAttribute()
    {
        return $this->productType ? $this->productType->requires_shipping : false;
    }

    /**
     * Get package type display
     */
    public function getPackageTypeAttribute()
    {
        return $this->is_digital ? 'بطاقة رقمية' : 'دوسية ورقية';
    }

    /**
     * Get duration display for digital packages
     */
    public function getDurationDisplayAttribute()
    {
        if (!$this->is_digital || !$this->duration_days) {
            return null;
        }

        if ($this->duration_days == 30) {
            return 'شهر واحد';
        } elseif ($this->duration_days == 90) {
            return '3 أشهر';
        } elseif ($this->duration_days == 180) {
            return '6 أشهر';
        } elseif ($this->duration_days == 365) {
            return 'سنة كاملة';
        }

        return "{$this->duration_days} يوم";
    }

    /**
     * Get lessons display for digital packages
     */
    public function getLessonsDisplayAttribute()
    {
        if (!$this->is_digital) {
            return null;
        }

        if (!$this->lessons_count) {
            return 'دروس غير محدودة';
        }

        return "{$this->lessons_count} درس";
    }

    /**
     * Get pages display for physical packages
     */
    public function getPagesDisplayAttribute()
    {
        if ($this->is_digital || !$this->pages_count) {
            return null;
        }

        return "{$this->pages_count} صفحة";
    }

    /**
     * Get weight display for physical packages
     */
    public function getWeightDisplayAttribute()
    {
        if ($this->is_digital || !$this->weight_grams) {
            return null;
        }

        if ($this->weight_grams >= 1000) {
            $kg = $this->weight_grams / 1000;
            return number_format($kg, 2) . ' كيلو';
        }

        return "{$this->weight_grams} جرام";
    }

    /**
     * Get full package info
     */
    public function getFullInfoAttribute()
    {
        $info = $this->name;

        if ($this->is_digital) {
            if ($this->duration_display) {
                $info .= " - {$this->duration_display}";
            }
            if ($this->lessons_display) {
                $info .= " - {$this->lessons_display}";
            }
        } else {
            if ($this->pages_display) {
                $info .= " - {$this->pages_display}";
            }
        }

        return $info;
    }

    // ====================================
    // HELPER METHODS
    // ====================================

    /**
     * Check if package is digital
     */
    public function isDigital()
    {
        return $this->is_digital;
    }

    /**
     * Check if package is physical
     */
    public function isPhysical()
    {
        return !$this->is_digital;
    }

    /**
     * Check if package requires shipping
     */
    public function requiresShipping()
    {
        return $this->requires_shipping;
    }

    /**
     * Get weight in kilograms
     */
    public function getWeightInKg()
    {
        return $this->weight_grams ? $this->weight_grams / 1000 : 0;
    }

    /**
     * Check if package has unlimited lessons
     */
    public function hasUnlimitedLessons()
    {
        return $this->is_digital && !$this->lessons_count;
    }

    /**
     * Check if package has duration limit
     */
    public function hasDurationLimit()
    {
        return $this->is_digital && $this->duration_days;
    }
}