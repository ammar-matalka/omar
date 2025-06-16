<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationalGeneration extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',          // "جيل 2007", "جيل 2008"
        'year',          // 2007, 2008
        'description',   // وصف للجيل
        'is_active',     // فعال أم لا
        'order'          // ترتيب العرض
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'year' => 'integer',
        'order' => 'integer'
    ];

    // العلاقات
    public function subjects()
    {
        return $this->hasMany(EducationalSubject::class, 'generation_id');
    }

    public function orders()
    {
        return $this->hasMany(EducationalCardOrder::class, 'generation_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('year', 'desc');
    }

    // Accessors
    public function getDisplayNameAttribute()
    {
        return $this->name ?: "جيل {$this->year}";
    }
}