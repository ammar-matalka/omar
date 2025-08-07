<?php
// app/Models/EducationalPackage.php (النسخة المُحدثة بالكامل)

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class EducationalPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_type_id',
        'platform_id',
        'name',
        'description',
        'duration_days',
        'lessons_count',
        'pages_count',
        'weight_grams',
        'is_active'
    ];

    protected $casts = [
        'duration_days' => 'integer',
        'lessons_count' => 'integer',
        'pages_count' => 'integer',
        'weight_grams' => 'integer',
        'is_active' => 'boolean'
    ];

    // ====================================
    // RELATIONSHIPS
    // ====================================

    /**
     * Get the product type that owns this package
     */
    public function productType()
    {
        return $this->belongsTo(EducationalProductType::class, 'product_type_id');
    }

    /**
     * Get the platform that owns this package
     */
    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    /**
     * Get the teacher through platform
     */
    public function teacher()
    {
        return $this->hasOneThrough(Teacher::class, Platform::class, 'id', 'id', 'platform_id', 'teacher_id');
    }

    /**
     * Get the subject through platform and teacher
     */
    public function subject()
    {
        return $this->hasOneThrough(
            Subject::class,
            Teacher::class,
            'id', // Foreign key on teachers table
            'id', // Foreign key on subjects table
            'platform_id', // Local key on packages table (through platform)
            'subject_id' // Local key on teachers table
        )->join('platforms', 'platforms.teacher_id', '=', 'teachers.id')
         ->where('platforms.id', $this->platform_id);
    }

    /**
     * Get educational pricing for this package
     */
    public function educationalPricing()
    {
        return $this->hasMany(EducationalPricing::class, 'package_id');
    }

    /**
     * Get educational inventory for this package (Physical booklets only)
     */
    public function educationalInventory()
    {
        return $this->hasMany(EducationalInventory::class, 'package_id');
    }

    /**
     * Get educational cards for this package (Digital cards only)
     */
    public function educationalCards()
    {
        return $this->hasMany(EducationalCard::class, 'package_id');
    }

    /**
     * Get order items for this package
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'package_id');
    }

    // ====================================
    // SCOPES للفصل الصحيح بين النوعين
    // ====================================

    /**
     * Scope for active packages
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for packages by platform
     */
    public function scopeByPlatform($query, $platformId)
    {
        return $query->where('platform_id', $platformId);
    }

    /**
     * Scope for packages by product type
     */
    public function scopeByProductType($query, $productTypeId)
    {
        return $query->where('product_type_id', $productTypeId);
    }

    /**
     * Scope for digital packages ONLY
     */
    public function scopeDigital($query)
    {
        return $query->whereHas('productType', function($q) {
            $q->where('is_digital', true);
        });
    }

    /**
     * Scope for physical packages ONLY
     */
    public function scopePhysical($query)
    {
        return $query->whereHas('productType', function($q) {
            $q->where('is_digital', false);
        });
    }

    /**
     * Scope for digital cards only (strict validation)
     */
    public function scopeDigitalCardsOnly($query)
    {
        $digitalProductType = EducationalProductType::getDigitalCardType();
        
        if (!$digitalProductType) {
            return $query->whereRaw('1 = 0'); // Return empty result
        }
        
        return $query->where('product_type_id', $digitalProductType->id)
                    ->whereNull('pages_count')
                    ->whereNull('weight_grams');
    }

    /**
     * Scope for physical booklets only (strict validation)
     */
    public function scopePhysicalBookletsOnly($query)
    {
        $physicalProductType = EducationalProductType::getPhysicalBookletType();
        
        if (!$physicalProductType) {
            return $query->whereRaw('1 = 0'); // Return empty result
        }
        
        return $query->where('product_type_id', $physicalProductType->id)
                    ->whereNotNull('pages_count')
                    ->where('pages_count', '>', 0)
                    ->whereNull('duration_days')
                    ->whereNull('lessons_count');
    }

    /**
     * Scope for packages with data integrity issues
     */
    public function scopeWithIntegrityIssues($query)
    {
        return $query->where(function($q) {
            // Digital cards with physical properties
            $q->whereHas('productType', function($productTypeQuery) {
                $productTypeQuery->where('is_digital', true);
            })->where(function($digitalQuery) {
                $digitalQuery->whereNotNull('pages_count')
                           ->orWhereNotNull('weight_grams');
            });
        })->orWhere(function($q) {
            // Physical booklets with digital properties
            $q->whereHas('productType', function($productTypeQuery) {
                $productTypeQuery->where('is_digital', false);
            })->where(function($physicalQuery) {
                $physicalQuery->whereNotNull('duration_days')
                            ->orWhereNotNull('lessons_count');
            });
        })->orWhere(function($q) {
            // Physical booklets without required pages_count
            $q->whereHas('productType', function($productTypeQuery) {
                $productTypeQuery->where('is_digital', false);
            })->where(function($pagesQuery) {
                $pagesQuery->whereNull('pages_count')
                          ->orWhere('pages_count', '<=', 0);
            });
        });
    }

    // ====================================
    // ACCESSORS المُحدثة
    // ====================================

    /**
     * Check if this is a digital package
     */
    public function getIsDigitalAttribute()
    {
        return $this->productType ? $this->productType->is_digital : false;
    }

    /**
     * Check if this is a physical package
     */
    public function getIsPhysicalAttribute()
    {
        return $this->productType ? !$this->productType->is_digital : false;
    }

    /**
     * Check if this package requires shipping
     */
    public function getRequiresShippingAttribute()
    {
        return $this->productType ? $this->productType->requires_shipping : false;
    }

    /**
     * Get package type display
     */
    public function getPackageTypeAttribute()
    {
        if ($this->is_digital) {
            return 'بطاقة رقمية';
        } else {
            return 'دوسية ورقية';
        }
    }

    /**
     * Get package type code for routing
     */
    public function getPackageTypeCodeAttribute()
    {
        return $this->productType ? $this->productType->code : 'unknown';
    }

    /**
     * Get admin route name based on package type
     */
    public function getAdminRouteNameAttribute()
    {
        if ($this->is_digital) {
            return 'admin.educational.digital-cards';
        } else {
            return 'admin.educational.physical-booklets';
        }
    }

    /**
     * Get duration display for digital packages
     */
    public function getDurationDisplayAttribute()
    {
        if (!$this->is_digital || !$this->duration_days) {
            return null;
        }

        if ($this->duration_days == 30) {
            return 'شهر واحد';
        } elseif ($this->duration_days == 90) {
            return '3 أشهر';
        } elseif ($this->duration_days == 180) {
            return '6 أشهر';
        } elseif ($this->duration_days == 365) {
            return 'سنة كاملة';
        }

        return "{$this->duration_days} يوم";
    }

    /**
     * Get lessons display for digital packages
     */
    public function getLessonsDisplayAttribute()
    {
        if (!$this->is_digital) {
            return null;
        }

        if (!$this->lessons_count) {
            return 'دروس غير محدودة';
        }

        return "{$this->lessons_count} درس";
    }

    /**
     * Get pages display for physical packages
     */
    public function getPagesDisplayAttribute()
    {
        if ($this->is_digital || !$this->pages_count) {
            return null;
        }

        return "{$this->pages_count} صفحة";
    }

    /**
     * Get weight display for physical packages
     */
    public function getWeightDisplayAttribute()
    {
        if ($this->is_digital || !$this->weight_grams) {
            return null;
        }

        if ($this->weight_grams >= 1000) {
            $kg = $this->weight_grams / 1000;
            return number_format($kg, 2) . ' كيلو';
        }

        return "{$this->weight_grams} جرام";
    }

    /**
     * Get full package info
     */
    public function getFullInfoAttribute()
    {
        $info = $this->name;

        if ($this->is_digital) {
            if ($this->duration_display) {
                $info .= " - {$this->duration_display}";
            }
            if ($this->lessons_display) {
                $info .= " - {$this->lessons_display}";
            }
        } else {
            if ($this->pages_display) {
                $info .= " - {$this->pages_display}";
            }
        }

        return $info;
    }

    /**
     * Get teaching chain info
     */
    public function getTeachingChainAttribute()
    {
        if (!$this->platform) {
            return 'غير محدد';
        }

        $platform = $this->platform;
        $teacher = $platform->teacher;
        
        if (!$teacher) {
            return $platform->name;
        }

        $subject = $teacher->subject;
        
        if (!$subject) {
            return "{$platform->name} - {$teacher->name}";
        }

        $generation = $subject->generation;
        
        if (!$generation) {
            return "{$platform->name} - {$teacher->name} - {$subject->name}";
        }

        return "{$generation->display_name} - {$subject->name} - {$teacher->name} - {$platform->name}";
    }

    // ====================================
    // VALIDATION METHODS المُحدثة
    // ====================================

    /**
     * Check if package data is valid for digital cards
     */
    public function isValidDigitalCard()
    {
        if (!$this->productType || !$this->productType->is_digital) {
            return false;
        }

        // Digital cards should not have physical properties
        if (!is_null($this->pages_count) || !is_null($this->weight_grams)) {
            return false;
        }

        return true;
    }

    /**
     * Check if package data is valid for physical booklets
     */
    public function isValidPhysicalBooklet()
    {
        if (!$this->productType || $this->productType->is_digital) {
            return false;
        }

        // Physical booklets should not have digital properties
        if (!is_null($this->duration_days) || !is_null($this->lessons_count)) {
            return false;
        }

        // Physical booklets must have pages_count
        if (is_null($this->pages_count) || $this->pages_count <= 0) {
            return false;
        }

        return true;
    }

    /**
     * Validate package data integrity
     */
    public function validatePackageIntegrity()
    {
        if (!$this->productType) {
            return [
                'valid' => false,
                'errors' => ['نوع المنتج غير محدد']
            ];
        }

        if ($this->productType->is_digital) {
            // Digital cards validation
            $errors = [];
            
            if (!is_null($this->pages_count)) {
                $errors[] = 'البطاقات الرقمية لا يجب أن تحتوي على عدد صفحات';
            }
            
            if (!is_null($this->weight_grams)) {
                $errors[] = 'البطاقات الرقمية لا يجب أن تحتوي على وزن';
            }
            
            return [
                'valid' => empty($errors),
                'errors' => $errors
            ];
        } else {
            // Physical booklets validation
            $errors = [];
            
            if (!is_null($this->duration_days)) {
                $errors[] = 'الدوسيات الورقية لا يجب أن تحتوي على مدة صلاحية';
            }
            
            if (!is_null($this->lessons_count)) {
                $errors[] = 'الدوسيات الورقية لا يجب أن تحتوي على عدد دروس';
            }
            
            if (is_null($this->pages_count) || $this->pages_count <= 0) {
                $errors[] = 'الدوسيات الورقية يجب أن تحتوي على عدد صفحات صالح';
            }
            
            return [
                'valid' => empty($errors),
                'errors' => $errors
            ];
        }
    }

    /**
     * Get data integrity issues
     */
    public function getIntegrityIssues()
    {
        $validation = $this->validatePackageIntegrity();
        
        if ($validation['valid']) {
            return [];
        }
        
        return $validation['errors'];
    }

    /**
     * Check if package has data integrity issues
     */
    public function hasIntegrityIssues()
    {
        return !$this->validatePackageIntegrity()['valid'];
    }

    // ====================================
    // HELPER METHODS المُحدثة
    // ====================================

    /**
     * Check if package is digital
     */
    public function isDigital()
    {
        return $this->is_digital;
    }

    /**
     * Check if package is physical
     */
    public function isPhysical()
    {
        return $this->is_physical;
    }

    /**
     * Check if package requires shipping
     */
    public function requiresShipping()
    {
        return $this->requires_shipping;
    }

    /**
     * Get weight in kilograms
     */
    public function getWeightInKg()
    {
        return $this->weight_grams ? $this->weight_grams / 1000 : 0;
    }

    /**
     * Check if package has unlimited lessons (digital only)
     */
    public function hasUnlimitedLessons()
    {
        return $this->is_digital && !$this->lessons_count;
    }

    /**
     * Check if package has duration limit (digital only)
     */
    public function hasDurationLimit()
    {
        return $this->is_digital && $this->duration_days;
    }

    /**
     * Clean package data based on type (IMPROVED)
     */
    public function cleanDataBasedOnType()
    {
        if (!$this->productType) {
            return $this;
        }

        if ($this->productType->is_digital) {
            // Clean physical properties from digital cards
            $this->pages_count = null;
            $this->weight_grams = null;
        } else {
            // Clean digital properties from physical booklets
            $this->duration_days = null;
            $this->lessons_count = null;
        }
        
        return $this;
    }

    /**
     * Auto-fix data integrity issues
     */
    public function fixIntegrityIssues()
    {
        if (!$this->hasIntegrityIssues()) {
            return ['fixed' => false, 'message' => 'لا توجد مشاكل للإصلاح'];
        }

        $this->cleanDataBasedOnType();
        
        $this->save();
        
        return ['fixed' => true, 'message' => 'تم إصلاح مشاكل سلامة البيانات'];
    }

    /**
     * Get admin URL for this package
     */
    public function getAdminUrl($action = 'show')
    {
        $routeName = $this->admin_route_name . '.' . $action;
        
        return route($routeName, $this);
    }

    /**
     * Get edit URL for admin
     */
    public function getEditUrlAttribute()
    {
        return $this->getAdminUrl('edit');
    }

    /**
     * Get show URL for admin
     */
    public function getShowUrlAttribute()
    {
        return $this->getAdminUrl('show');
    }

    // ====================================
    // STATIC HELPER METHODS
    // ====================================

    /**
     * Create digital card with validation
     */
    public static function createDigitalCard(array $data)
    {
        $digitalProductType = EducationalProductType::getDigitalCardType();
        
        if (!$digitalProductType) {
            throw new \Exception('نوع المنتج الرقمي غير متوفر');
        }

        // Clean data for digital card
        $cleanData = $digitalProductType->cleanProperties($data);
        $cleanData['product_type_id'] = $digitalProductType->id;

        // Validate data
        $validation = $digitalProductType->validatePackageProperties($cleanData);
        if (!$validation['valid']) {
            throw new \Exception('بيانات البطاقة الرقمية غير صحيحة: ' . implode(', ', $validation['errors']));
        }

        return static::create($cleanData);
    }

    /**
     * Create physical booklet with validation
     */
    public static function createPhysicalBooklet(array $data)
    {
        $physicalProductType = EducationalProductType::getPhysicalBookletType();
        
        if (!$physicalProductType) {
            throw new \Exception('نوع المنتج الورقي غير متوفر');
        }

        // Clean data for physical booklet
        $cleanData = $physicalProductType->cleanProperties($data);
        $cleanData['product_type_id'] = $physicalProductType->id;

        // Validate data
        $validation = $physicalProductType->validatePackageProperties($cleanData);
        if (!$validation['valid']) {
            throw new \Exception('بيانات الدوسية الورقية غير صحيحة: ' . implode(', ', $validation['errors']));
        }

        return static::create($cleanData);
    }

    /**
     * Get packages with integrity issues
     */
    public static function getPackagesWithIntegrityIssues()
    {
        return static::withIntegrityIssues()->with('productType')->get();
    }

    /**
     * Fix all integrity issues
     */
    public static function fixAllIntegrityIssues()
    {
        $packagesWithIssues = static::getPackagesWithIntegrityIssues();
        $fixedCount = 0;

        foreach ($packagesWithIssues as $package) {
            $result = $package->fixIntegrityIssues();
            if ($result['fixed']) {
                $fixedCount++;
            }
        }

        return [
            'total_issues' => $packagesWithIssues->count(),
            'fixed_count' => $fixedCount,
            'success' => $fixedCount > 0
        ];
    }

    // ====================================
    // BOOT METHOD للتأكد من النظافة التلقائية
    // ====================================

    protected static function boot()
    {
        parent::boot();

        // Auto-clean data before saving
        static::saving(function ($package) {
            $package->cleanDataBasedOnType();
        });

        // Validate data integrity before saving
        static::saving(function ($package) {
            $validation = $package->validatePackageIntegrity();
            if (!$validation['valid']) {
                throw new \Exception('Package data integrity violation: ' . implode(', ', $validation['errors']));
            }
        });

        // Log integrity issues
        static::saved(function ($package) {
            if ($package->hasIntegrityIssues()) {
                Log::warning('Package saved with integrity issues', [
                    'package_id' => $package->id,
                    'issues' => $package->getIntegrityIssues()
                ]);
            }
        });
    }
}