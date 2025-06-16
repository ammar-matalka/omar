<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationalCardOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'generation_id',
        'student_name',
        'semester',      // "الأول", "الثاني", "كلاهما"
        'quantity',      // الكمية
        'total_amount',  // المجموع الكلي
        'status',        // 'pending', 'processing', 'completed', 'cancelled'
        'notes',         // ملاحظات إضافية
        'phone',         // رقم الهاتف
        'address'        // العنوان
    ];

    protected $casts = [
        'user_id' => 'integer',
        'generation_id' => 'integer',
        'quantity' => 'integer',
        'total_amount' => 'decimal:2'
    ];

    // العلاقات
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function generation()
    {
        return $this->belongsTo(EducationalGeneration::class, 'generation_id');
    }

    public function orderItems()
    {
        return $this->hasMany(EducationalCardOrderItem::class, 'order_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Accessors
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'processing' => 'info',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'secondary'
        };
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'pending' => 'قيد الانتظار',
            'processing' => 'قيد المعالجة',
            'completed' => 'مكتمل',
            'cancelled' => 'ملغى',
            default => 'غير محدد'
        };
    }

    public function getFormattedTotalAttribute()
    {
        return number_format($this->total_amount, 2) . ' JD';
    }

    public function getSemesterTextAttribute()
    {
        return match($this->semester) {
            'first' => 'الفصل الأول',
            'second' => 'الفصل الثاني',
            'both' => 'كلا الفصلين',
            default => $this->semester
        };
    }
}