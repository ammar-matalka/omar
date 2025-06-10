<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'is_read_by_user',
        'is_read_by_admin'
    ];

    protected $casts = [
        'is_read_by_user' => 'boolean',
        'is_read_by_admin' => 'boolean'
    ];

    // ====================================
    // RELATIONSHIPS
    // ====================================

    /**
     * Get the user that owns the conversation
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the messages for the conversation
     */
    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    /**
     * Get the latest message
     */
    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latest();
    }

    // ====================================
    // HELPER METHODS
    // ====================================

    /**
     * Mark as read by admin
     */
    public function markAsReadByAdmin(): void
    {
        $this->update(['is_read_by_admin' => true]);
    }

    /**
     * Mark as read by user
     */
    public function markAsReadByUser(): void
    {
        $this->update(['is_read_by_user' => true]);
    }

    /**
     * Check if conversation has unread messages for admin
     */
    public function hasUnreadForAdmin(): bool
    {
        return !$this->is_read_by_admin;
    }

    /**
     * Check if conversation has unread messages for user
     */
    public function hasUnreadForUser(): bool
    {
        return !$this->is_read_by_user;
    }

    /**
     * Get conversation status
     */
    public function getStatusAttribute(): string
    {
        if ($this->hasUnreadForAdmin()) {
            return 'unread_admin';
        } elseif ($this->hasUnreadForUser()) {
            return 'unread_user';
        }
        return 'read';
    }

    // ====================================
    // SCOPES
    // ====================================

    /**
     * Scope for unread conversations by admin
     */
    public function scopeUnreadByAdmin($query)
    {
        return $query->where('is_read_by_admin', false);
    }

    /**
     * Scope for unread conversations by user
     */
    public function scopeUnreadByUser($query)
    {
        return $query->where('is_read_by_user', false);
    }
}
