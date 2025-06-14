<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationalCardOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'academic_year',
        'generation',
        'subject',
        'teacher',
        'semester',
        'platform',
        'notebook_type',
        'quantity',
        'notes',
        'status',
        'total_amount',
        'is_processed'
    ];

    protected $casts = [
        'is_processed' => 'boolean',
        'quantity' => 'integer',
        'total_amount' => 'decimal:2'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessed($query)
    {
        return $query->where('is_processed', true);
    }

    // Accessors
    public function getFormattedTotalAttribute()
    {
        return '$' . number_format($this->total_amount, 2);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'processing' => 'info',
            'completed' => 'success',
            'cancelled' => 'danger'
        ];

        return $badges[$this->status] ?? 'secondary';
    }
}