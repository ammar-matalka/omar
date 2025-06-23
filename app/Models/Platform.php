<?php
// app/Models/Platform.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'name',
        'description',
        'website_url',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // ====================================
    // RELATIONSHIPS
    // ====================================

    /**
     * Get the teacher that owns this platform
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the subject through teacher
     */
    public function subject()
    {
        return $this->hasOneThrough(Subject::class, Teacher::class);
    }

    /**
     * Get the generation through teacher and subject
     */
    public function generation()
    {
        return $this->hasOneThrough(
            Generation::class,
            Subject::class,
            'id', // Foreign key on subjects table
            'id', // Foreign key on generations table
            'teacher_id', // Local key on platforms table (through teacher)
            'generation_id' // Local key on subjects table
        )->join('teachers', 'teachers.subject_id', '=', 'subjects.id')
         ->where('teachers.id', $this->teacher_id);
    }

    /**
     * Get educational packages for this platform
     */
    public function educationalPackages()
    {
        return $this->hasMany(EducationalPackage::class);
    }

    /**
     * Get active educational packages for this platform
     */
    public function activeEducationalPackages()
    {
        return $this->hasMany(EducationalPackage::class)->where('is_active', true);
    }

    /**
     * Get educational pricing for this platform
     */
    public function educationalPricing()
    {
        return $this->hasMany(EducationalPricing::class);
    }

    /**
     * Get educational inventory for this platform
     */
    public function educationalInventory()
    {
        return $this->hasMany(EducationalInventory::class);
    }

    /**
     * Get educational cards for this platform
     */
    public function educationalCards()
    {
        return $this->hasMany(EducationalCard::class);
    }

    /**
     * Get order items for this platform
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // ====================================
    // SCOPES
    // ====================================

    /**
     * Scope for active platforms
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for platforms by teacher
     */
    public function scopeByTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    /**
     * Scope for platforms with packages
     */
    public function scopeWithPackages($query)
    {
        return $query->whereHas('educationalPackages');
    }

    /**
     * Scope for platforms with active packages
     */
    public function scopeWithActivePackages($query)
    {
        return $query->whereHas('activeEducationalPackages');
    }

    // ====================================
    // ACCESSORS
    // ====================================

    /**
     * Get full platform info
     */
    public function getFullInfoAttribute()
    {
        return "{$this->name} - {$this->teacher->name}";
    }

    /**
     * Get platform's complete teaching chain
     */
    public function getTeachingChainAttribute()
    {
        return "{$this->name} - {$this->teacher->name} - {$this->teacher->subject->name} - {$this->teacher->subject->generation->display_name}";
    }

    // ====================================
    // HELPER METHODS
    // ====================================

    /**
     * Check if platform has website URL
     */
    public function hasWebsite()
    {
        return !empty($this->website_url);
    }

    /**
     * Check if platform has packages
     */
    public function hasPackages()
    {
        return $this->educationalPackages()->exists();
    }

    /**
     * Check if platform has active packages
     */
    public function hasActivePackages()
    {
        return $this->activeEducationalPackages()->exists();
    }

    /**
     * Get packages count
     */
    public function getPackagesCountAttribute()
    {
        return $this->educationalPackages()->count();
    }

    /**
     * Get active packages count
     */
    public function getActivePackagesCountAttribute()
    {
        return $this->activeEducationalPackages()->count();
    }

    /**
     * Get formatted website URL
     */
    public function getFormattedWebsiteUrlAttribute()
    {
        if (!$this->website_url) {
            return null;
        }

        // Add protocol if missing
        if (!preg_match('/^https?:\/\//', $this->website_url)) {
            return 'https://' . $this->website_url;
        }

        return $this->website_url;
    }
}