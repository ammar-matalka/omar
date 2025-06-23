<?php
// app/Models/OrderItem.php (Updated for Educational System)

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
        'item_name',
        // Educational fields
        'is_educational',
        'generation_id',
        'subject_id',
        'teacher_id',
        'platform_id',
        'package_id',
        'region_id',
        'shipping_cost'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'quantity' => 'integer',
        'is_educational' => 'boolean'
    ];

    // ====================================
    // EXISTING RELATIONSHIPS
    // ====================================

    /**
     * Get the order that owns the order item
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product (may be null if product was deleted or if educational)
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // ====================================
    // EDUCATIONAL RELATIONSHIPS
    // ====================================

    /**
     * Get the generation for educational items
     */
    public function generation()
    {
        return $this->belongsTo(Generation::class);
    }

    /**
     * Get the subject for educational items
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the teacher for educational items
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the platform for educational items
     */
    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    /**
     * Get the package for educational items
     */
    public function package()
    {
        return $this->belongsTo(EducationalPackage::class, 'package_id');
    }

    /**
     * Get the shipping region for educational items
     */
    public function region()
    {
        return $this->belongsTo(ShippingRegion::class, 'region_id');
    }

    /**
     * Get educational cards for this order item
     */
    public function educationalCards()
    {
        return $this->hasMany(EducationalCard::class);
    }

    /**
     * Get educational shipment for this order item
     */
    public function educationalShipment()
    {
        return $this->hasOne(EducationalShipment::class);
    }

    // ====================================
    // SCOPES
    // ====================================

    /**
     * Scope for educational order items
     */
    public function scopeEducational($query)
    {
        return $query->where('is_educational', true);
    }

    /**
     * Scope for regular product order items
     */
    public function scopeRegular($query)
    {
        return $query->where('is_educational', false);
    }

    /**
     * Scope for digital educational items
     */
    public function scopeDigitalEducational($query)
    {
        return $query->where('is_educational', true)->whereNull('region_id');
    }

    /**
     * Scope for physical educational items
     */
    public function scopePhysicalEducational($query)
    {
        return $query->where('is_educational', true)->whereNotNull('region_id');
    }

    // ====================================
    // ACCESSORS
    // ====================================
    
    /**
     * Get display name (updated for educational items)
     */
    public function getDisplayNameAttribute()
    {
        if ($this->is_educational) {
            return $this->getEducationalDisplayName();
        }

        // Regular product logic
        if ($this->product) {
            return $this->product->name;
        }
        
        return $this->item_name ?: 'Deleted Product';
    }

    /**
     * Get educational display name
     */
    public function getEducationalDisplayNameAttribute()
    {
        if (!$this->is_educational) {
            return null;
        }

        $parts = [];
        
        if ($this->package) {
            $parts[] = $this->package->name;
            $parts[] = $this->package->package_type;
        }
        
        if ($this->subject) {
            $parts[] = $this->subject->name;
        }
        
        if ($this->teacher) {
            $parts[] = "أ. {$this->teacher->name}";
        }
        
        if ($this->generation) {
            $parts[] = $this->generation->display_name;
        }

        return implode(' - ', $parts);
    }

    /**
     * Get product image (updated for educational items)
     */
    public function getDisplayImageAttribute()
    {
        if ($this->is_educational) {
            return $this->getEducationalDisplayImage();
        }

        // Regular product logic
        if ($this->product) {
            return $this->product->main_image_url;
        }
        
        return asset('images/no-image.jpg');
    }

    /**
     * Get educational display image
     */
    public function getEducationalDisplayImageAttribute()
    {
        if (!$this->is_educational) {
            return null;
        }

        // Check for teacher image first
        if ($this->teacher && $this->teacher->hasImage()) {
            return $this->teacher->image_url;
        }

        // Default educational image based on package type
        if ($this->package) {
            if ($this->package->is_digital) {
                return asset('images/educational-card-default.jpg');
            } else {
                return asset('images/educational-booklet-default.jpg');
            }
        }

        return asset('images/educational-default.jpg');
    }
    
    /**
     * Calculate total including shipping for educational items
     */
    public function getTotalAttribute()
    {
        $subtotal = $this->quantity * $this->price;
        
        if ($this->is_educational && $this->shipping_cost > 0) {
            $subtotal += $this->shipping_cost;
        }
        
        return $subtotal;
    }

    /**
     * Get item type display
     */
    public function getItemTypeAttribute()
    {
        if ($this->is_educational) {
            return $this->package ? $this->package->package_type : 'منتج تعليمي';
        }
        
        return 'منتج عادي';
    }

    /**
     * Get shipping info
     */
    public function getShippingInfoAttribute()
    {
        if (!$this->is_educational) {
            return null;
        }

        if ($this->shipping_cost == 0) {
            return 'شحن مجاني';
        }

        $info = "شحن: " . number_format($this->shipping_cost, 2) . " دينار";
        
        if ($this->region) {
            $info .= " - {$this->region->name}";
        }

        return $info;
    }

    /**
     * Get educational details
     */
    public function getEducationalDetailsAttribute()
    {
        if (!$this->is_educational) {
            return null;
        }

        return [
            'generation' => $this->generation?->display_name,
            'subject' => $this->subject?->name,
            'teacher' => $this->teacher?->name,
            'platform' => $this->platform?->name,
            'package' => $this->package?->name,
            'package_type' => $this->package?->package_type,
            'region' => $this->region?->name,
            'shipping_cost' => $this->shipping_cost,
            'is_digital' => $this->package?->is_digital ?? false,
            'requires_shipping' => $this->package?->requires_shipping ?? false
        ];
    }

    // ====================================
    // HELPER METHODS (UPDATED)
    // ====================================

    /**
     * Check if the original product still exists (updated for educational items)
     */
    public function hasProduct(): bool
    {
        if ($this->is_educational) {
            return true; // Educational items don't depend on products table
        }
        
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

    /**
     * Get formatted shipping cost
     */
    public function getFormattedShippingCostAttribute(): string
    {
        if ($this->shipping_cost == 0) {
            return 'مجاني';
        }
        
        return number_format($this->shipping_cost, 2) . ' دينار';
    }

    // ====================================
    // EDUCATIONAL HELPER METHODS
    // ====================================

    /**
     * Check if this is an educational item
     */
    public function isEducational(): bool
    {
        return $this->is_educational;
    }

    /**
     * Check if this is a regular product item
     */
    public function isRegular(): bool
    {
        return !$this->is_educational;
    }

    /**
     * Check if this is a digital educational item
     */
    public function isDigitalEducational(): bool
    {
        return $this->is_educational && is_null($this->region_id);
    }

    /**
     * Check if this is a physical educational item
     */
    public function isPhysicalEducational(): bool
    {
        return $this->is_educational && !is_null($this->region_id);
    }

    /**
     * Check if item requires shipping
     */
    public function requiresShipping(): bool
    {
        if ($this->is_educational) {
            return $this->isPhysicalEducational();
        }
        
        // Regular products always require shipping (unless specified otherwise)
        return true;
    }

    /**
     * Generate educational cards after payment
     */
    public function generateEducationalCards(): array
    {
        if (!$this->isDigitalEducational()) {
            return [];
        }

        $cards = [];
        
        for ($i = 0; $i < $this->quantity; $i++) {
            $card = EducationalCard::createFromOrderItem($this);
            $cards[] = $card;
        }

        return $cards;
    }

    /**
     * Create educational shipment for physical items
     */
    public function createEducationalShipment(): ?EducationalShipment
    {
        if (!$this->isPhysicalEducational()) {
            return null;
        }

        return EducationalShipment::create([
            'order_item_id' => $this->id,
            'status' => 'preparing'
        ]);
    }

    /**
     * Get educational cards for this item
     */
    public function getEducationalCardsAttribute()
    {
        if (!$this->isDigitalEducational()) {
            return collect();
        }

        return $this->educationalCards;
    }

    /**
     * Get educational shipment status
     */
    public function getEducationalShipmentStatusAttribute()
    {
        if (!$this->isPhysicalEducational()) {
            return null;
        }

        $shipment = $this->educationalShipment;
        return $shipment ? $shipment->status_display : 'لم يتم إنشاء الشحنة';
    }

    /**
     * Check if educational content is ready
     */
    public function isEducationalContentReady(): bool
    {
        if ($this->isDigitalEducational()) {
            return $this->educationalCards()->exists();
        }
        
        if ($this->isPhysicalEducational()) {
            $shipment = $this->educationalShipment;
            return $shipment && in_array($shipment->status, ['shipped', 'delivered']);
        }
        
        return false;
    }

    /**
     * Get delivery status for educational items
     */
    public function getEducationalDeliveryStatusAttribute()
    {
        if ($this->isDigitalEducational()) {
            $cardsCount = $this->educationalCards()->count();
            if ($cardsCount > 0) {
                return "تم توليد {$cardsCount} بطاقة رقمية";
            }
            return 'لم يتم توليد البطاقات';
        }
        
        if ($this->isPhysicalEducational()) {
            $shipment = $this->educationalShipment;
            if ($shipment) {
                return $shipment->status_display;
            }
            return 'لم يتم إنشاء الشحنة';
        }
        
        return null;
    }

    /**
     * Calculate educational inventory impact
     */
    public function updateEducationalInventory()
    {
        if (!$this->isPhysicalEducational()) {
            return; // Only physical items affect inventory
        }

        $inventory = EducationalInventory::forSelection(
            $this->generation_id,
            $this->subject_id,
            $this->teacher_id,
            $this->platform_id,
            $this->package_id
        )->first();

        if ($inventory) {
            $inventory->confirmSale($this->quantity);
        }
    }

    /**
     * Reserve educational inventory
     */
    public function reserveEducationalInventory()
    {
        if (!$this->isPhysicalEducational()) {
            return true; // Digital items don't need inventory reservation
        }

        $inventory = EducationalInventory::forSelection(
            $this->generation_id,
            $this->subject_id,
            $this->teacher_id,
            $this->platform_id,
            $this->package_id
        )->first();

        if (!$inventory) {
            return false; // No inventory record found
        }

        return $inventory->reserveQuantity($this->quantity);
    }

    /**
     * Release educational inventory reservation
     */
    public function releaseEducationalInventoryReservation()
    {
        if (!$this->isPhysicalEducational()) {
            return;
        }

        $inventory = EducationalInventory::forSelection(
            $this->generation_id,
            $this->subject_id,
            $this->teacher_id,
            $this->platform_id,
            $this->package_id
        )->first();

        if ($inventory) {
            $inventory->releaseReservedQuantity($this->quantity);
        }
    }
}