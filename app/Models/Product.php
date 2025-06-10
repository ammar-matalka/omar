<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'image', // Keep for backward compatibility
        'is_active',
        'category_id',
        'featured'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'featured' => 'boolean',
        'price' => 'decimal:2'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
    
    // New relationship for product images
    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }
    
    // Get primary image
    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }
    
    // Accessor for getting the main image URL
    public function getMainImageAttribute()
    {
        $primaryImage = $this->primaryImage;
        
        if ($primaryImage) {
            return $primaryImage->image_path;
        }
        
        // Fallback to the first image
        $firstImage = $this->images()->first();
        
        if ($firstImage) {
            return $firstImage->image_path;
        }
        
        // Fallback to the old image column
        return $this->image;
    }

    // Accessor for getting the main image URL with asset helper
    public function getMainImageUrlAttribute()
    {
        $imagePath = $this->main_image;
        
        if ($imagePath) {
            return asset('storage/' . $imagePath);
        }
        
        return asset('images/no-image.jpg'); // Default image
    }

    // Check if product has any images
    public function hasImages()
    {
        return $this->images()->exists() || !empty($this->image);
    }

    public function wishlistUsers()
    {
        return $this->belongsToMany(User::class, 'wishlists');
    }
}