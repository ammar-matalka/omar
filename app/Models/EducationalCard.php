<?php
// app/Models/EducationalCard.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EducationalCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_item_id',
        'card_code',
        'pin_code',
        'generation_id',
        'subject_id',
        'teacher_id',
        'platform_id',
        'package_id',
        'status',
        'used_by_student_id',
        'used_at',
        'expires_at'
    ];

    protected $casts = [
        'used_at' => 'datetime',
        'expires_at' => 'datetime'
    ];

    // ====================================
    // RELATIONSHIPS
    // ====================================

    /**
     * Get the order item for this card
     */
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    /**
     * Get the order through order item
     */
    public function order()
    {
        return $this->hasOneThrough(Order::class, OrderItem::class);
    }

    /**
     * Get the user who purchased this card
     */
    public function purchaser()
    {
        return $this->hasOneThrough(User::class, OrderItem::class, 'id', 'id', 'order_item_id', 'order_id')
                    ->join('orders', 'orders.id', '=', 'order_items.order_id');
    }

    /**
     * Get the student who used this card
     */
    public function usedByStudent()
    {
        return $this->belongsTo(User::class, 'used_by_student_id');
    }

    /**
     * Get the generation for this card
     */
    public function generation()
    {
        return $this->belongsTo(Generation::class);
    }

    /**
     * Get the subject for this card
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the teacher for this card
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the platform for this card
     */
    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    /**
     * Get the package for this card
     */
    public function package()
    {
        return $this->belongsTo(EducationalPackage::class, 'package_id');
    }

    // ====================================
    // SCOPES
    // ====================================

    /**
     * Scope for active cards
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for used cards
     */
    public function scopeUsed($query)
    {
        return $query->where('status', 'used');
    }

    /**
     * Scope for expired cards
     */
    public function scopeExpired($query)
    {
        return $query->where('status', 'expired')
                    ->orWhere('expires_at', '<', now());
    }

    /**
     * Scope for cancelled cards
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Scope for cards by generation
     */
    public function scopeByGeneration($query, $generationId)
    {
        return $query->where('generation_id', $generationId);
    }

    /**
     * Scope for cards by subject
     */
    public function scopeBySubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    /**
     * Scope for cards by teacher
     */
    public function scopeByTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    /**
     * Scope for cards by platform
     */
    public function scopeByPlatform($query, $platformId)
    {
        return $query->where('platform_id', $platformId);
    }

    // ====================================
    // ACCESSORS
    // ====================================

    /**
     * Get status display in Arabic
     */
    public function getStatusDisplayAttribute()
    {
        $statuses = [
            'active' => 'نشطة',
            'used' => 'مستخدمة',
            'expired' => 'منتهية الصلاحية',
            'cancelled' => 'ملغية'
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * Get status class for styling
     */
    public function getStatusClassAttribute()
    {
        $classes = [
            'active' => 'success',
            'used' => 'info',
            'expired' => 'warning',
            'cancelled' => 'danger'
        ];

        return $classes[$this->status] ?? 'secondary';
    }

    /**
     * Check if card is expired
     */
    public function getIsExpiredAttribute()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if card is usable
     */
    public function getIsUsableAttribute()
    {
        return $this->status === 'active' && !$this->is_expired;
    }

    /**
     * Get days until expiration
     */
    public function getDaysUntilExpirationAttribute()
    {
        if (!$this->expires_at) {
            return null;
        }

        $days = now()->diffInDays($this->expires_at, false);
        return $days > 0 ? $days : 0;
    }

    /**
     * Get time until expiration
     */
    public function getTimeUntilExpirationAttribute()
    {
        if (!$this->expires_at) {
            return 'بدون انتهاء صلاحية';
        }

        if ($this->is_expired) {
            return 'منتهية الصلاحية';
        }

        $days = $this->days_until_expiration;
        
        if ($days == 0) {
            return 'تنتهي اليوم';
        } elseif ($days == 1) {
            return 'تنتهي غداً';
        } elseif ($days <= 7) {
            return "تنتهي خلال {$days} أيام";
        } elseif ($days <= 30) {
            $weeks = ceil($days / 7);
            return "تنتهي خلال {$weeks} أسبوع";
        } else {
            $months = ceil($days / 30);
            return "تنتهي خلال {$months} شهر";
        }
    }

    /**
     * Get card info
     */
    public function getCardInfoAttribute()
    {
        return [
            'code' => $this->card_code,
            'pin' => $this->pin_code,
            'generation' => $this->generation->display_name,
            'subject' => $this->subject->name,
            'teacher' => $this->teacher->name,
            'platform' => $this->platform->name,
            'package' => $this->package->name,
            'status' => $this->status_display,
            'expires_at' => $this->expires_at?->format('Y-m-d'),
            'time_until_expiration' => $this->time_until_expiration,
            'is_usable' => $this->is_usable
        ];
    }

    // ====================================
    // HELPER METHODS
    // ====================================

    /**
     * Generate unique card code
     */
    public static function generateCardCode()
    {
        do {
            $code = 'EDU-' . strtoupper(Str::random(8));
        } while (self::where('card_code', $code)->exists());

        return $code;
    }

    /**
     * Generate unique PIN code
     */
    public static function generatePinCode()
    {
        do {
            $pin = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (self::where('pin_code', $pin)->exists());

        return $pin;
    }

    /**
     * Create educational card from order item
     */
    public static function createFromOrderItem(OrderItem $orderItem)
    {
        $card = self::create([
            'order_item_id' => $orderItem->id,
            'card_code' => self::generateCardCode(),
            'pin_code' => self::generatePinCode(),
            'generation_id' => $orderItem->generation_id,
            'subject_id' => $orderItem->subject_id,
            'teacher_id' => $orderItem->teacher_id,
            'platform_id' => $orderItem->platform_id,
            'package_id' => $orderItem->package_id,
            'status' => 'active',
            'expires_at' => $orderItem->package->duration_days ? now()->addDays($orderItem->package->duration_days) : null
        ]);

        return $card;
    }

    /**
     * Use the card by a student
     */
    public function useByStudent(User $student)
    {
        if (!$this->is_usable) {
            return false;
        }

        $this->update([
            'status' => 'used',
            'used_by_student_id' => $student->id,
            'used_at' => now()
        ]);

        return true;
    }

    /**
     * Cancel the card
     */
    public function cancel()
    {
        $this->update(['status' => 'cancelled']);
    }

    /**
     * Extend expiration date
     */
    public function extendExpiration($days)
    {
        if ($this->expires_at) {
            $newExpiration = $this->is_expired ? now()->addDays($days) : $this->expires_at->addDays($days);
            $this->update(['expires_at' => $newExpiration]);
        }
    }

    /**
     * Check expiration and update status
     */
    public function checkExpiration()
    {
        if ($this->is_expired && $this->status === 'active') {
            $this->update(['status' => 'expired']);
        }
    }

    /**
     * Verify card with code and PIN
     */
    public static function verifyCard($cardCode, $pinCode)
    {
        $card = self::where('card_code', $cardCode)
                   ->where('pin_code', $pinCode)
                   ->first();

        if (!$card) {
            return ['valid' => false, 'message' => 'كود البطاقة أو الرقم السري غير صحيح'];
        }

        $card->checkExpiration();

        if (!$card->is_usable) {
            return ['valid' => false, 'message' => "البطاقة غير صالحة للاستخدام - الحالة: {$card->status_display}"];
        }

        return ['valid' => true, 'card' => $card];
    }
}