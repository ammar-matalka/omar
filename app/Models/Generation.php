<?php
// app/Models/Generation.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Generation extends Model
{
    use HasFactory;

    protected $fillable = [
        'birth_year',
        'name',
        'is_active'
    ];

    protected $casts = [
        'birth_year' => 'integer',
        'is_active' => 'boolean'
    ];

    // ====================================
    // RELATIONSHIPS
    // ====================================

    /**
     * Get the subjects for this generation
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    /**
     * Get active subjects for this generation
     */
    public function activeSubjects()
    {
        return $this->hasMany(Subject::class)->where('is_active', true);
    }

    /**
     * Get educational pricing for this generation
     */
    public function educationalPricing()
    {
        return $this->hasMany(EducationalPricing::class);
    }

    /**
     * Get educational inventory for this generation
     */
    public function educationalInventory()
    {
        return $this->hasMany(EducationalInventory::class);
    }

    /**
     * Get educational cards for this generation
     */
    public function educationalCards()
    {
        return $this->hasMany(EducationalCard::class);
    }

    /**
     * Get order items for this generation
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // ====================================
    // SCOPES
    // ====================================

    /**
     * Scope for active generations
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for generations by birth year range
     */
    public function scopeByYearRange($query, $startYear, $endYear)
    {
        return $query->whereBetween('birth_year', [$startYear, $endYear]);
    }

    // ====================================
    // HELPER METHODS
    // ====================================

    /**
     * Get display name
     */
    public function getDisplayNameAttribute()
    {
        return $this->name ?: "جيل {$this->birth_year}";
    }

    /**
     * Check if generation has subjects
     */
    public function hasSubjects()
    {
        return $this->subjects()->exists();
    }

    /**
     * Check if generation has active subjects
     */
    public function hasActiveSubjects()
    {
        return $this->activeSubjects()->exists();
    }

    /**
     * Get age of students in this generation
     */
    public function getStudentAgeAttribute()
    {
        return now()->year - $this->birth_year;
    }

    /**
     * Get grade level estimate
     */
    public function getGradeLevelAttribute()
    {
        $age = $this->student_age;
        
        if ($age >= 6 && $age <= 11) {
            return 'ابتدائي';
        } elseif ($age >= 12 && $age <= 14) {
            return 'إعدادي';
        } elseif ($age >= 15 && $age <= 17) {
            return 'ثانوي';
        } elseif ($age >= 18) {
            return 'جامعي';
        }
        
        return 'غير محدد';
    }
}