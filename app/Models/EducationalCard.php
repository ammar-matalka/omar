<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EducationalCard extends Model
{
    protected $fillable = [
        'subject_id',
        'title',
        'title_ar',
        'description',
        'description_ar',
        'price',
        'stock',
        'image',
        'is_active',
        'card_type',
        'difficulty_level'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    /**
     * Get the subject that owns the educational card.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the images for the educational card.
     */
    public function images(): HasMany
    {
        return $this->hasMany(EducationalCardImage::class);
    }

    /**
     * Get the primary image.
     */
    public function primaryImage()
    {
        return $this->hasOne(EducationalCardImage::class)->where('is_primary', true);
    }

    /**
     * Scope active cards.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope by subject.
     */
    public function scopeBySubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    /**
     * Scope in stock.
     */
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Get formatted price.
     */
    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price, 2);
    }
}