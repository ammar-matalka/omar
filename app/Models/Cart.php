<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id'];

    // ====================================
    // RELATIONSHIPS
    // ====================================

    /**
     * Get the user that owns the cart
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the cart items
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    // ====================================
    // HELPER METHODS
    // ====================================

    /**
     * Calculate cart total
     */
    public function total(): float
    {
        $total = 0;
        foreach ($this->cartItems as $item) {
            $total += $item->quantity * $item->item_price;
        }
        return $total;
    }
    
    /**
     * Get cart total as formatted currency
     */
    public function getFormattedTotalAttribute(): string
    {
        return number_format($this->total(), 2) . ' ' . config('app.currency', '$');
    }
    
    /**
     * Count total items in cart
     */
    public function itemCount(): int
    {
        return $this->cartItems->sum('quantity');
    }

    /**
     * Count unique items in cart
     */
    public function uniqueItemCount(): int
    {
        return $this->cartItems->count();
    }

    /**
     * Check if cart is empty
     */
    public function isEmpty(): bool
    {
        return $this->cartItems->isEmpty();
    }

    /**
     * Check if cart has items
     */
    public function hasItems(): bool
    {
        return !$this->isEmpty();
    }

    /**
     * Get cart weight (for shipping calculation)
     */
    public function getTotalWeightAttribute(): float
    {
        $weight = 0;
        foreach ($this->cartItems as $item) {
            // Assuming products and educational cards have weight attribute
            $itemWeight = $item->item->weight ?? 0;
            $weight += $item->quantity * $itemWeight;
        }
        return $weight;
    }

    /**
     * Clear all items from cart
     */
    public function clearItems(): void
    {
        $this->cartItems()->delete();
    }

    /**
     * Get cart items grouped by type
     */
    public function getItemsByTypeAttribute(): array
    {
        return [
            'products' => $this->cartItems()->where('type', 'product')->with('product')->get(),
            'educational_cards' => $this->cartItems()->where('type', 'educational_card')->with('educationalCard')->get()
        ];
    }

    // ====================================
    // SCOPES
    // ====================================

    /**
     * Scope for carts with items
     */
    public function scopeWithItems($query)
    {
        return $query->whereHas('cartItems');
    }

    /**
     * Scope for empty carts
     */
    public function scopeEmpty($query)
    {
        return $query->whereDoesntHave('cartItems');
    }

    /**
     * Scope for carts with specific item type
     */
    public function scopeWithItemType($query, $type)
    {
        return $query->whereHas('cartItems', function($q) use ($type) {
            $q->where('type', $type);
        });
    }
}