<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationalCardOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'subject_id',
        'subject_name',  // حفظ اسم المادة وقت الطلب
        'price',         // حفظ السعر وقت الطلب
        'quantity'       // الكمية
    ];

    protected $casts = [
        'order_id' => 'integer',
        'subject_id' => 'integer',
        'price' => 'decimal:2',
        'quantity' => 'integer'
    ];

    // العلاقات
    public function order()
    {
        return $this->belongsTo(EducationalCardOrder::class, 'order_id');
    }

    public function subject()
    {
        return $this->belongsTo(EducationalSubject::class, 'subject_id');
    }

    // Accessors
    public function getSubtotalAttribute()
    {
        return $this->price * $this->quantity;
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2) . ' JD';
    }

    public function getFormattedSubtotalAttribute()
    {
        return number_format($this->subtotal, 2) . ' JD';
    }
}