<?php
// app/Models/Subject.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'generation_id',
        'name',
        'code',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // ====================================
    // RELATIONSHIPS
    // ====================================

    /**
     * Get the generation that owns this subject
     */
    public function generation()
    {
        return $this->belongsTo(Generation::class);
    }

    /**
     * Get the teachers for this subject
     */
    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }

    /**
     * Get active teachers for this subject
     */
    public function activeTeachers()
    {
        return $this->hasMany(Teacher::class)->where('is_active', true);
    }

    /**
     * Get educational pricing for this subject
     */
    public function educationalPricing()
    {
        return $this->hasMany(EducationalPricing::class);
    }

    /**
     * Get educational inventory for this subject
     */
    public function educationalInventory()
    {
        return $this->hasMany(EducationalInventory::class);
    }

    /**
     * Get educational cards for this subject
     */
    public function educationalCards()
    {
        return $this->hasMany(EducationalCard::class);
    }

    /**
     * Get order items for this subject
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // ====================================
    // SCOPES
    // ====================================

    /**
     * Scope for active subjects
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for subjects by generation
     */
    public function scopeByGeneration($query, $generationId)
    {
        return $query->where('generation_id', $generationId);
    }

    /**
     * Scope for subjects with teachers
     */
    public function scopeWithTeachers($query)
    {
        return $query->whereHas('teachers');
    }

    /**
     * Scope for subjects with active teachers
     */
    public function scopeWithActiveTeachers($query)
    {
        return $query->whereHas('activeTeachers');
    }

    // ====================================
    // HELPER METHODS
    // ====================================

    /**
     * Get full subject name with generation
     */
    public function getFullNameAttribute()
    {
        return "{$this->name} - {$this->generation->display_name}";
    }

    /**
     * Check if subject has teachers
     */
    public function hasTeachers()
    {
        return $this->teachers()->exists();
    }

    /**
     * Check if subject has active teachers
     */
    public function hasActiveTeachers()
    {
        return $this->activeTeachers()->exists();
    }

    /**
     * Get teachers count
     */
    public function getTeachersCountAttribute()
    {
        return $this->teachers()->count();
    }

    /**
     * Get active teachers count
     */
    public function getActiveTeachersCountAttribute()
    {
        return $this->activeTeachers()->count();
    }
}