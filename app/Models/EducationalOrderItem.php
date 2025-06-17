<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationalOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'subject_id',
        'subject_name',
        'dossier_id',
        'dossier_name',
        'teacher_id',
        'teacher_name',
        'platform_id',
        'platform_name',
        'price',
        'quantity'
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * العلاقات
     */
    public function order()
    {
        return $this->belongsTo(EducationalOrder::class, 'order_id');
    }

    public function subject()
    {
        return $this->belongsTo(EducationalSubject::class);
    }

    public function dossier()
    {
        return $this->belongsTo(Dossier::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    /**
     * Accessors
     */
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2) . ' JD';
    }

    public function getSubtotalAttribute()
    {
        return $this->price * $this->quantity;
    }

    public function getFormattedSubtotalAttribute()
    {
        return number_format($this->subtotal, 2) . ' JD';
    }

    public function getItemNameAttribute()
    {
        if ($this->dossier_id) {
            return $this->dossier_name ?: $this->dossier?->name;
        }
        return $this->subject_name ?: $this->subject?->name;
    }

    public function getItemTypeAttribute()
    {
        return $this->dossier_id ? 'دوسية' : 'بطاقة تعليمية';
    }
}