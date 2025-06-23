<?php
// app/Models/Teacher.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'name',
        'specialization',
        'bio',
        'image',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // ====================================
    // RELATIONSHIPS
    // ====================================

    /**
     * Get the subject that owns this teacher
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the generation through subject
     */
    public function generation()
    {
        return $this->hasOneThrough(Generation::class, Subject::class);
    }

    /**
     * Get the platforms for this teacher
     */
    public function platforms()
    {
        return $this->hasMany(Platform::class);
    }

    /**
     * Get active platforms for this teacher
     */
    public function activePlatforms()
    {
        return $this->hasMany(Platform::class)->where('is_active', true);
    }

    /**
     * Get educational pricing for this teacher
     */
    public function educationalPricing()
    {
        return $this->hasMany(EducationalPricing::class);
    }

    /**
     * Get educational inventory for this teacher
     */
    public function educationalInventory()
    {
        return $this->hasMany(EducationalInventory::class);
    }

    /**
     * Get educational cards for this teacher
     */
    public function educationalCards()
    {
        return $this->hasMany(EducationalCard::class);
    }

    /**
     * Get order items for this teacher
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // ====================================
    // SCOPES
    // ====================================

    /**
     * Scope for active teachers
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for teachers by subject
     */
    public function scopeBySubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    /**
     * Scope for teachers with platforms
     */
    public function scopeWithPlatforms($query)
    {
        return $query->whereHas('platforms');
    }

    /**
     * Scope for teachers with active platforms
     */
    public function scopeWithActivePlatforms($query)
    {
        return $query->whereHas('activePlatforms');
    }

    // ====================================
    // ACCESSORS
    // ====================================

    /**
     * Get teacher image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        
        // Default teacher avatar
        $initials = strtoupper(substr($this->name, 0, 1));
        return "https://ui-avatars.com/api/?name={$initials}&background=4f46e5&color=ffffff&size=150";
    }

    /**
     * Get full teacher info
     */
    public function getFullInfoAttribute()
    {
        $info = $this->name;
        
        if ($this->specialization) {
            $info .= " - {$this->specialization}";
        }
        
        return $info;
    }

    /**
     * Get teacher's subject and generation info
     */
    public function getTeachingInfoAttribute()
    {
        return "{$this->subject->name} - {$this->subject->generation->display_name}";
    }

    // ====================================
    // HELPER METHODS
    // ====================================

    /**
     * Check if teacher has platforms
     */
    public function hasPlatforms()
    {
        return $this->platforms()->exists();
    }

    /**
     * Check if teacher has active platforms
     */
    public function hasActivePlatforms()
    {
        return $this->activePlatforms()->exists();
    }

    /**
     * Get platforms count
     */
    public function getPlatformsCountAttribute()
    {
        return $this->platforms()->count();
    }

    /**
     * Get active platforms count
     */
    public function getActivePlatformsCountAttribute()
    {
        return $this->activePlatforms()->count();
    }

    /**
     * Check if teacher has image
     */
    public function hasImage()
    {
        return !empty($this->image);
    }
}