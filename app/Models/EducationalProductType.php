<?php
// app/Models/EducationalProductType.php (المُصحح)

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class EducationalProductType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'display_type',
        'is_digital',
        'requires_shipping',
        'description',
        'features',
        'is_active'
    ];

    protected $casts = [
        'is_digital' => 'boolean',
        'requires_shipping' => 'boolean',
        'is_active' => 'boolean',
        'features' => 'array'
    ];

    // ====================================
    // RELATIONSHIPS
    // ====================================

    /**
     * Get educational packages for this product type
     */
    public function educationalPackages()
    {
        return $this->hasMany(EducationalPackage::class, 'product_type_id');
    }

    /**
     * Get active educational packages for this product type
     */
    public function activeEducationalPackages()
    {
        return $this->hasMany(EducationalPackage::class, 'product_type_id')->where('is_active', true);
    }

    // ====================================
    // SCOPES
    // ====================================

    /**
     * Scope for active product types
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for digital product types
     */
    public function scopeDigital($query)
    {
        return $query->where('is_digital', true);
    }

    /**
     * Scope for physical product types
     */
    public function scopePhysical($query)
    {
        return $query->where('is_digital', false);
    }

    /**
     * Scope for product types that require shipping
     */
    public function scopeRequiresShipping($query)
    {
        return $query->where('requires_shipping', true);
    }

    /**
     * Scope for product types that don't require shipping
     */
    public function scopeNoShipping($query)
    {
        return $query->where('requires_shipping', false);
    }

    // ====================================
    // ACCESSORS
    // ====================================

    /**
     * Check if this is a digital product type
     */
    public function getIsDigitalTypeAttribute()
    {
        return $this->is_digital;
    }

    /**
     * Check if this is a physical product type
     */
    public function getIsPhysicalTypeAttribute()
    {
        return !$this->is_digital;
    }

    /**
     * Get display type
     */
    public function getDisplayTypeAttribute()
    {
        return $this->attributes['display_type'] ?? ($this->is_digital ? 'رقمي' : 'ورقي');
    }

    /**
     * Get shipping status
     */
    public function getShippingStatusAttribute()
    {
        return $this->requires_shipping ? 'مع شحن' : 'بدون شحن';
    }

    /**
     * Get full description
     */
    public function getFullDescriptionAttribute()
    {
        return "{$this->name} ({$this->display_type} - {$this->shipping_status})";
    }

    /**
     * Get expected properties for this product type
     */
    public function getExpectedPropertiesAttribute()
    {
        if ($this->is_digital) {
            return [
                'duration_days' => 'اختياري',
                'lessons_count' => 'اختياري',
                'pages_count' => 'محظور',
                'weight_grams' => 'محظور'
            ];
        } else {
            return [
                'duration_days' => 'محظور',
                'lessons_count' => 'محظور',
                'pages_count' => 'مطلوب',
                'weight_grams' => 'اختياري'
            ];
        }
    }

    // ====================================
    // HELPER METHODS
    // ====================================

    /**
     * Check if this is a digital product type
     */
    public function isDigital()
    {
        return $this->is_digital;
    }

    /**
     * Check if this is a physical product type
     */
    public function isPhysical()
    {
        return !$this->is_digital;
    }

    /**
     * Check if this product type requires shipping
     */
    public function requiresShipping()
    {
        return $this->requires_shipping;
    }

    /**
     * Get default digital card product type
     */
    public static function getDigitalCardType()
    {
        return static::where('is_digital', true)->first();
    }

    /**
     * Get default physical booklet product type
     */
    public static function getPhysicalBookletType()
    {
        return static::where('is_digital', false)->first();
    }

    /**
     * Validate package properties against product type
     */
    public function validatePackageProperties(array $properties)
    {
        $errors = [];

        if ($this->is_digital) {
            // Digital cards should not have physical properties
            if (isset($properties['pages_count']) && !is_null($properties['pages_count'])) {
                $errors[] = 'البطاقات الرقمية لا يجب أن تحتوي على عدد صفحات';
            }
            
            if (isset($properties['weight_grams']) && !is_null($properties['weight_grams'])) {
                $errors[] = 'البطاقات الرقمية لا يجب أن تحتوي على وزن';
            }
        } else {
            // Physical booklets should not have digital properties
            if (isset($properties['duration_days']) && !is_null($properties['duration_days'])) {
                $errors[] = 'الدوسيات الورقية لا يجب أن تحتوي على مدة صلاحية';
            }
            
            if (isset($properties['lessons_count']) && !is_null($properties['lessons_count'])) {
                $errors[] = 'الدوسيات الورقية لا يجب أن تحتوي على عدد دروس';
            }
            
            // Physical booklets must have pages_count
            if (!isset($properties['pages_count']) || is_null($properties['pages_count']) || $properties['pages_count'] <= 0) {
                $errors[] = 'الدوسيات الورقية يجب أن تحتوي على عدد صفحات صالح';
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Clean properties based on product type
     */
    public function cleanProperties(array $properties)
    {
        if ($this->is_digital) {
            // Remove physical properties from digital products
            unset($properties['pages_count'], $properties['weight_grams']);
        } else {
            // Remove digital properties from physical products
            unset($properties['duration_days'], $properties['lessons_count']);
        }

        return $properties;
    }

    /**
     * Get count of packages by status
     */
    public function getPackageCountByStatus($status = null)
    {
        $query = $this->educationalPackages();
        
        if ($status) {
            $query->where('is_active', $status === 'active');
        }
        
        return $query->count();
    }

    /**
     * Get statistics for this product type
     */
    public function getStatistics()
    {
        return [
            'total_packages' => $this->educationalPackages()->count(),
            'active_packages' => $this->educationalPackages()->where('is_active', true)->count(),
            'inactive_packages' => $this->educationalPackages()->where('is_active', false)->count(),
        ];
    }

    // ====================================
    // BOOT METHOD
    // ====================================

    protected static function boot()
    {
        parent::boot();

        // Auto-set display_type if not provided
        static::creating(function ($productType) {
            if (empty($productType->display_type)) {
                $productType->display_type = $productType->is_digital ? 'رقمي' : 'ورقي';
            }
        });

        // Validate consistency
        static::saving(function ($productType) {
            // Digital products should not require shipping
            if ($productType->is_digital && $productType->requires_shipping) {
                throw new \Exception('المنتجات الرقمية لا يمكن أن تتطلب شحن');
            }
            
            // Physical products should require shipping (in most cases)
            if (!$productType->is_digital && !$productType->requires_shipping) {
                // This is allowed but log a warning
                Log::warning("Physical product type '{$productType->name}' does not require shipping");
            }
        });
    }
}