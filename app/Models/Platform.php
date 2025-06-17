<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'website_url',
        'price_percentage',
        'is_active',
        'order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price_percentage' => 'decimal:2',
    ];

    /**
     * Scope للمنصات النشطة
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope للترتيب
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('name', 'asc');
    }

    /**
     * العلاقات
     */
    public function dossiers()
    {
        return $this->hasMany(Dossier::class);
    }

    public function orderItems()
    {
        return $this->hasMany(EducationalOrderItem::class);
    }

    /**
     * Accessors
     */
    public function getStatusTextAttribute()
    {
        return $this->is_active ? 'نشط' : 'غير نشط';
    }

    public function getStatusColorAttribute()
    {
        return $this->is_active ? 'success' : 'danger';
    }

    public function getFormattedPricePercentageAttribute()
    {
        return $this->price_percentage . '%';
    }

    /**
     * حساب السعر مع إضافة نسبة المنصة
     */
    public function calculatePrice($basePrice)
    {
        return $basePrice + ($basePrice * ($this->price_percentage / 100));
    }
}