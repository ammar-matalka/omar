<?php

// ==============================================
// Platform.php Model
// ==============================================

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_ar',
        'description',
        'description_ar',
        'image',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/no-platform-image.jpg');
    }
}

// ==============================================
// Grade.php Model
// ==============================================

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform_id',
        'name',
        'name_ar',
        'grade_number',
        'description',
        'description_ar',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'grade_number' => 'integer'
    ];

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
}

// ==============================================
// Subject.php Model
// ==============================================

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'grade_id',
        'name',
        'name_ar',
        'description',
        'description_ar',
        'image',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function educationalCards()
    {
        return $this->hasMany(EducationalCard::class);
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/no-subject-image.jpg');
    }
}

// ==============================================
// EducationalCard.php Model
// ==============================================

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationalCard extends Model
{
    use HasFactory;

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
        'card_type', // digital, physical, both
        'difficulty_level' // easy, medium, hard
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2'
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function images()
    {
        return $this->hasMany(EducationalCardImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(EducationalCardImage::class)->where('is_primary', true);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Get main image URL
    public function getMainImageUrlAttribute()
    {
        $primaryImage = $this->primaryImage;
        
        if ($primaryImage) {
            return asset('storage/' . $primaryImage->image_path);
        }
        
        $firstImage = $this->images()->first();
        if ($firstImage) {
            return asset('storage/' . $firstImage->image_path);
        }
        
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        
        return asset('images/no-card-image.jpg');
    }

    // Check if card has images
    public function hasImages()
    {
        return $this->images()->exists() || !empty($this->image);
    }
}

// ==============================================
// EducationalCardImage.php Model
// ==============================================

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationalCardImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'educational_card_id',
        'image_path',
        'is_primary',
        'sort_order'
    ];

    protected $casts = [
        'is_primary' => 'boolean'
    ];

    public function educationalCard()
    {
        return $this->belongsTo(EducationalCard::class);
    }
}