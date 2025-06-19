<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'item_name' // Store name at time of purchase
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
    ];

    // ====================================
    // RELATIONSHIPS
    // ====================================

    /**
     * Get the order that owns the order item
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product (may be null if product was deleted)
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    // ====================================
    // ACCESSORS
    // ====================================
    
    /**
     * Get display name (fallback to stored name if product deleted)
     */
    public function getDisplayNameAttribute()
    {
        // If product still exists, use its current name
        if ($this->product) {
            return $this->product->name;
        }
        
        // Fallback to stored name if product was deleted
        return $this->item_name ?: 'Deleted Product';
    }

    /**
     * Get product image (fallback if product deleted)
     */
    public function getDisplayImageAttribute()
    {
        if ($this->product) {
            return $this->product->main_image_url;
        }
        
        return asset('images/no-image.jpg');
    }
    
    /**
     * Calculate total for this order item
     */
    public function getTotalAttribute()
    {
        return $this->quantity * $this->price;
    }

    /**
     * Calculate total for this order item (method version)
     */
    public function total()
    {
        return $this->quantity * $this->price;
    }

    // ====================================
    // HELPER METHODS
    // ====================================

    /**
     * Check if the original product still exists
     */
    public function hasProduct(): bool
    {
        return !is_null($this->product);
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 2);
    }

    /**
     * Get formatted total
     */
    public function getFormattedTotalAttribute(): string
    {
        return number_format($this->total, 2);
    }
}