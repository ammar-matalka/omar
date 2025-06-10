<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Accessor for getting the image URL
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        
        return asset('images/no-category-image.jpg'); // Default image
    }

    // Check if category has image
    public function hasImage()
    {
        return !empty($this->image);
    }
}