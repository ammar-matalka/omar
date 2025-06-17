<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'specialization',
        'description',
        'phone',
        'email',
        'is_active',
        'order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope للمعلمين النشطين
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
    public function subjects()
    {
        return $this->belongsToMany(EducationalSubject::class, 'educational_order_items');
    }

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
}