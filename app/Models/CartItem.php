<?php

// ==============================================
// Updated CartItem.php Model
// ==============================================

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id', 
        'product_id', 
        'educational_card_id',
        'quantity',
        'type' // 'product' or 'educational_card'
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
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
    
    // Get item name
    public function getItemNameAttribute()
    {
        $item = $this->item;
        if (!$item) return 'Unknown Item';
        
        if ($this->type === 'educational_card') {
            return app()->getLocale() === 'ar' && $item->title_ar ? $item->title_ar : $item->title;
        }
        
        return $item->name;
    }
    
    // Get item price
    public function getItemPriceAttribute()
    {
        $item = $this->item;
        return $item ? $item->price : 0;
    }
    
    // Get item image
    public function getItemImageAttribute()
    {
        $item = $this->item;
        if (!$item) return asset('images/no-image.jpg');
        
        if ($this->type === 'educational_card') {
            return $item->main_image_url;
        }
        
        return $item->main_image_url;
    }
    
    // Calculate subtotal for each item
    public function subtotal()
    {
        return $this->quantity * $this->item_price;
    }
}
