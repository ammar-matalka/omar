<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationalSubject extends Model
{
    use HasFactory;

    protected $fillable = [
        'generation_id',
        'name',           // "الرياضيات", "العلوم"
        'price',          // سعر المادة
        'is_active',      // فعال أم لا
        'order'           // ترتيب العرض
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'order' => 'integer',
        'generation_id' => 'integer'
    ];

    // العلاقات
    public function generation()
    {
        return $this->belongsTo(EducationalGeneration::class, 'generation_id');
    }

    public function orderItems()
    {
        return $this->hasMany(EducationalCardOrderItem::class, 'subject_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('name', 'asc');
    }

    public function scopeForGeneration($query, $generationId)
    {
        return $query->where('generation_id', $generationId);
    }

    // Accessors
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2) . ' JD';
    }
}