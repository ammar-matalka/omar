<?php
// app/Models/EducationalShipment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationalShipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_item_id',
        'tracking_number',
        'status',
        'printed_at',
        'shipped_at',
        'delivered_at',
        'notes'
    ];

    protected $casts = [
        'printed_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime'
    ];

    // ====================================
    // RELATIONSHIPS
    // ====================================

    /**
     * Get the order item for this shipment
     */
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    /**
     * Get the order through order item
     */
    public function order()
    {
        return $this->hasOneThrough(Order::class, OrderItem::class);
    }

    /**
     * Get the customer through order
     */
    public function customer()
    {
        return $this->hasOneThrough(User::class, OrderItem::class, 'id', 'id', 'order_item_id', 'order_id')
                    ->join('orders', 'orders.id', '=', 'order_items.order_id')
                    ->join('users', 'users.id', '=', 'orders.user_id');
    }

    // ====================================
    // SCOPES
    // ====================================

    /**
     * Scope for shipments by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for preparing shipments
     */
    public function scopePreparing($query)
    {
        return $query->where('status', 'preparing');
    }

    /**
     * Scope for printed shipments
     */
    public function scopePrinted($query)
    {
        return $query->where('status', 'printed');
    }

    /**
     * Scope for shipped shipments
     */
    public function scopeShipped($query)
    {
        return $query->where('status', 'shipped');
    }

    /**
     * Scope for delivered shipments
     */
    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    /**
     * Scope for returned shipments
     */
    public function scopeReturned($query)
    {
        return $query->where('status', 'returned');
    }

    /**
     * Scope for shipments with tracking number
     */
    public function scopeWithTracking($query)
    {
        return $query->whereNotNull('tracking_number');
    }

    /**
     * Scope for shipments without tracking number
     */
    public function scopeWithoutTracking($query)
    {
        return $query->whereNull('tracking_number');
    }

    // ====================================
    // ACCESSORS
    // ====================================

    /**
     * Get status display in Arabic
     */
    public function getStatusDisplayAttribute()
    {
        $statuses = [
            'preparing' => 'قيد التحضير',
            'printed' => 'تم الطباعة',
            'shipped' => 'تم الشحن',
            'delivered' => 'تم التسليم',
            'returned' => 'مرتجع'
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * Get status class for styling
     */
    public function getStatusClassAttribute()
    {
        $classes = [
            'preparing' => 'warning',
            'printed' => 'info',
            'shipped' => 'primary',
            'delivered' => 'success',
            'returned' => 'danger'
        ];

        return $classes[$this->status] ?? 'secondary';
    }

    /**
     * Get estimated delivery date
     */
    public function getEstimatedDeliveryAttribute()
    {
        if ($this->status === 'delivered') {
            return $this->delivered_at;
        }

        if ($this->shipped_at) {
            return $this->shipped_at->addDays(3); // Assume 3 days for delivery
        }

        if ($this->printed_at) {
            return $this->printed_at->addDays(5); // Assume 2 days to ship + 3 days delivery
        }

        return now()->addDays(7); // Default estimate
    }

    /**
     * Get days since order
     */
    public function getDaysSinceOrderAttribute()
    {
        return $this->created_at->diffInDays(now());
    }

    /**
     * Get shipment timeline
     */
    public function getTimelineAttribute()
    {
        $timeline = [
            [
                'status' => 'preparing',
                'label' => 'قيد التحضير',
                'date' => $this->created_at,
                'completed' => true
            ]
        ];

        if ($this->printed_at) {
            $timeline[] = [
                'status' => 'printed',
                'label' => 'تم الطباعة',
                'date' => $this->printed_at,
                'completed' => true
            ];
        }

        if ($this->shipped_at) {
            $timeline[] = [
                'status' => 'shipped',
                'label' => 'تم الشحن',
                'date' => $this->shipped_at,
                'completed' => true
            ];
        }

        if ($this->delivered_at) {
            $timeline[] = [
                'status' => 'delivered',
                'label' => 'تم التسليم',
                'date' => $this->delivered_at,
                'completed' => true
            ];
        }

        return $timeline;
    }

    // ====================================
    // HELPER METHODS
    // ====================================

    /**
     * Mark as printed
     */
    public function markAsPrinted()
    {
        $this->update([
            'status' => 'printed',
            'printed_at' => now()
        ]);
    }

    /**
     * Mark as shipped
     */
    public function markAsShipped($trackingNumber = null)
    {
        $updateData = [
            'status' => 'shipped',
            'shipped_at' => now()
        ];

        if ($trackingNumber) {
            $updateData['tracking_number'] = $trackingNumber;
        }

        $this->update($updateData);
    }

    /**
     * Mark as delivered
     */
    public function markAsDelivered()
    {
        $this->update([
            'status' => 'delivered',
            'delivered_at' => now()
        ]);
    }

    /**
     * Mark as returned
     */
    public function markAsReturned($reason = null)
    {
        $updateData = [
            'status' => 'returned'
        ];

        if ($reason) {
            $updateData['notes'] = $this->notes ? $this->notes . "\n\nسبب الإرجاع: " . $reason : "سبب الإرجاع: " . $reason;
        }

        $this->update($updateData);
    }

    /**
     * Add note
     */
    public function addNote($note)
    {
        $currentNotes = $this->notes ? $this->notes . "\n\n" : '';
        $timestamp = now()->format('Y-m-d H:i');
        $newNote = "[{$timestamp}] {$note}";
        
        $this->update([
            'notes' => $currentNotes . $newNote
        ]);
    }

    /**
     * Update tracking number
     */
    public function updateTrackingNumber($trackingNumber)
    {
        $this->update(['tracking_number' => $trackingNumber]);
    }

    /**
     * Check if shipment is overdue
     */
    public function isOverdue()
    {
        $daysSinceOrder = $this->days_since_order;
        
        if ($this->status === 'preparing' && $daysSinceOrder > 2) {
            return true;
        }
        
        if ($this->status === 'printed' && $daysSinceOrder > 4) {
            return true;
        }
        
        if ($this->status === 'shipped' && $daysSinceOrder > 7) {
            return true;
        }
        
        return false;
    }

    /**
     * Get tracking URL (can be customized based on shipping provider)
     */
    public function getTrackingUrlAttribute()
    {
        if (!$this->tracking_number) {
            return null;
        }

        // Default tracking URL - customize based on your shipping provider
        return "https://track.example.com/{$this->tracking_number}";
    }

    /**
     * Check if shipment can be cancelled
     */
    public function canBeCancelled()
    {
        return in_array($this->status, ['preparing', 'printed']);
    }

    /**
     * Check if shipment can be returned
     */
    public function canBeReturned()
    {
        return in_array($this->status, ['shipped', 'delivered']);
    }
}