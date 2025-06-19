<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id', 
        'product_id', 
        'quantity',
    ];

    // ====================================
    // RELATIONSHIPS
    // ====================================

    /**
     * Get the cart that owns the cart item
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Get the product that is in the cart
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    // ====================================
    // ACCESSORS
    // ====================================
    
    /**
     * Get item name
     */
    public function getItemNameAttribute()
    {
        return $this->product ? $this->product->name : 'Unknown Item';
    }
    
    /**
     * Get item price
     */
    public function getItemPriceAttribute()
    {
        return $this->product ? $this->product->price : 0;
    }
    
    /**
     * Get item image
     */
    public function getItemImageAttribute()
    {
        return $this->product ? $this->product->main_image_url : asset('images/no-image.jpg');
    }
    
    /**
     * Calculate subtotal for each item
     */
    public function getSubtotalAttribute()
    {
        return $this->quantity * $this->item_price;
    }

    /**
     * Calculate subtotal for each item (method version)
     */
    public function subtotal()
    {
        return $this->quantity * $this->item_price;
    }

    // ====================================
    // HELPER METHODS
    // ====================================

    /**
     * Check if the product is still available
     */
    public function isAvailable(): bool
    {
        return $this->product && $this->product->is_active && $this->product->stock >= $this->quantity;
    }

    /**
     * Get available stock for this product
     */
    public function getAvailableStock(): int
    {
        return $this->product ? $this->product->stock : 0;
    }

    /**
     * Check if requested quantity exceeds available stock
     */
    public function exceedsStock(): bool
    {
        return $this->quantity > $this->getAvailableStock();
    }
}