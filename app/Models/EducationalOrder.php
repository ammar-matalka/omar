<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationalOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'generation_id',
        'student_name',
        'order_type',
        'semester',
        'quantity',
        'total_amount',
        'phone',
        'address',
        'notes',
        'admin_notes',
        'status'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    /**
     * Scopes
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

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

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeCards($query)
    {
        return $query->where('order_type', 'card');
    }

    public function scopeDossiers($query)
    {
        return $query->where('order_type', 'dossier');
    }

    /**
     * العلاقات
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function generation()
    {
        return $this->belongsTo(EducationalGeneration::class);
    }

    public function orderItems()
    {
        return $this->hasMany(EducationalOrderItem::class, 'order_id');
    }

    /**
     * Accessors
     */
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'pending' => 'قيد الانتظار',
            'processing' => 'قيد المعالجة',
            'completed' => 'مكتملة',
            'cancelled' => 'ملغاة',
            default => 'غير محدد'
        };
    }

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

    public function getSemesterTextAttribute()
    {
        return match($this->semester) {
            'first' => 'الفصل الأول',
            'second' => 'الفصل الثاني',
            'both' => 'كلا الفصلين',
            default => $this->semester
        };
    }

    public function getOrderTypeTextAttribute()
    {
        return match($this->order_type) {
            'card' => 'بطاقة تعليمية',
            'dossier' => 'دوسية',
            default => $this->order_type
        };
    }

    public function getFormattedTotalAttribute()
    {
        return number_format($this->total_amount, 2) . ' JD';
    }

    /**
     * Helper Methods
     */
    public function isCard()
    {
        return $this->order_type === 'card';
    }

    public function isDossier()
    {
        return $this->order_type === 'dossier';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isProcessing()
    {
        return $this->status === 'processing';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }
}