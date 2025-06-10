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
        'educational_card_id',
        'quantity',
        'price',
        'type', // 'product' or 'educational_card'
        'item_name' // Store name at time of purchase
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function educationalCard()
    {
        return $this->belongsTo(EducationalCard::class);
    }
    
    // Get the item (product or educational card)
    public function getItemAttribute()
    {
        if ($this->type === 'educational_card') {
            return $this->educationalCard;
        }
        return $this->product;
    }
    
    // Get display name (fallback to stored name if item deleted)
    public function getDisplayNameAttribute()
    {
        $item = $this->item;
        
        if ($item) {
            if ($this->type === 'educational_card') {
                return app()->getLocale() === 'ar' && $item->title_ar ? $item->title_ar : $item->title;
            }
            return $item->name;
        }
        
        // Fallback to stored name if item was deleted
        return $this->item_name ?: 'Deleted Item';
    }
    
    // Calculate total for this order item
    public function total()
    {
        return $this->quantity * $this->price;
    }
}