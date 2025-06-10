<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;



class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'user_id',
        'message',
        'is_from_admin'
    ];

    protected $casts = [
        'is_from_admin' => 'boolean'
    ];

    // ====================================
    // RELATIONSHIPS
    // ====================================

    /**
     * Get the conversation that owns the message
     */
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * Get the user that sent the message
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ====================================
    // HELPER METHODS
    // ====================================

    /**
     * Get sender name
     */
    public function getSenderNameAttribute(): string
    {
        if ($this->is_from_admin) {
            return 'Admin';
        }
        return $this->user->name ?? 'User';
    }

    /**
     * Get formatted message time
     */
    public function getFormattedTimeAttribute(): string
    {
        return $this->created_at->format('M d, Y H:i');
    }

    /**
     * Get message excerpt
     */
    public function getExcerptAttribute(): string
    {
        return Str::limit($this->message, 100);
    }

    // ====================================
    // SCOPES
    // ====================================

    /**
     * Scope for admin messages
     */
    public function scopeFromAdmin($query)
    {
        return $query->where('is_from_admin', true);
    }

    /**
     * Scope for user messages
     */
    public function scopeFromUser($query)
    {
        return $query->where('is_from_admin', false);
    }

    /**
     * Scope for recent messages
     */
    public function scopeRecent($query, $hours = 24)
    {
        return $query->where('created_at', '>=', now()->subHours($hours));
    }
}