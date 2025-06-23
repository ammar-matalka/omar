<?php
// app/Services/EducationalService.php

namespace App\Services;

use App\Models\EducationalPricing;
use App\Models\EducationalInventory;
use App\Models\EducationalPackage;
use App\Models\EducationalCard;
use App\Models\EducationalShipment;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EducationalService
{
    /**
     * التحقق من صحة السلسلة التعليمية
     */
    public function validateEducationalChain(array $data): bool
    {
        try {
            // التحقق من أن المادة تنتمي للجيل
            $subject = \App\Models\Subject::where('id', $data['subject_id'])
                                         ->where('generation_id', $data['generation_id'])
                                         ->first();
            if (!$subject) {
                return false;
            }

            // التحقق من أن المعلم يدرس المادة
            $teacher = \App\Models\Teacher::where('id', $data['teacher_id'])
                                         ->where('subject_id', $data['subject_id'])
                                         ->first();
            if (!$teacher) {
                return false;
            }

            // التحقق من أن المنصة تخص المعلم
            $platform = \App\Models\Platform::where('id', $data['platform_id'])
                                           ->where('teacher_id', $data['teacher_id'])
                                           ->first();
            if (!$platform) {
                return false;
            }

            // التحقق من أن الباقة تخص المنصة
            $package = EducationalPackage::where('id', $data['package_id'])
                                       ->where('platform_id', $data['platform_id'])
                                       ->first();
            if (!$package) {
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error('خطأ في التحقق من السلسلة التعليمية', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            return false;
        }
    }

    /**
     * حساب السعر النهائي للمنتج التعليمي
     */
    public function calculatePrice(array $data): array
    {
        try {
            // جلب التسعير
            $pricing = EducationalPricing::active()
                                       ->forSelection(
                                           $data['generation_id'],
                                           $data['subject_id'],
                                           $data['teacher_id'],
                                           $data['platform_id'],
                                           $data['package_id'],
                                           $data['region_id'] ?? null
                                       )
                                       ->with(['region', 'package'])
                                       ->first();

            if (!$pricing) {
                return [
                    'success' => false,
                    'message' => 'التسعير غير متوفر لهذا الاختيار'
                ];
            }

            $quantity = $data['quantity'] ?? 1;
            $calculation = $pricing->calculateTotal($quantity);

            return [
                'success' => true,
                'data' => [
                    'unit_price' => $calculation['unit_price'],
                    'shipping_cost' => $calculation['total_shipping'],
                    'subtotal' => $calculation['subtotal'],
                    'total_price' => $calculation['total_cost'],
                    'quantity' => $quantity,
                    'product_info' => $pricing->product_info,
                    'is_digital' => $pricing->package->is_digital,
                    'requires_shipping' => $pricing->package->requires_shipping
                ]
            ];
        } catch (\Exception $e) {
            Log::error('خطأ في حساب السعر', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);

            return [
                'success' => false,
                'message' => 'حدث خطأ في حساب السعر'
            ];
        }
    }

    /**
     * التحقق من توفر المخزون
     */
    public function checkInventory(array $data): array
    {
        try {
            // التحقق من نوع الباقة
            $package = EducationalPackage::with('productType')->find($data['package_id']);
            
            if (!$package) {
                return [
                    'success' => false,
                    'message' => 'الباقة غير موجودة'
                ];
            }

            // البطاقات الرقمية متوفرة دائماً
            if ($package->productType->is_digital) {
                return [
                    'success' => true,
                    'available' => true,
                    'message' => 'المنتج الرقمي متوفر دائماً'
                ];
            }

            // التحقق من المخزون للدوسيات الورقية
            $inventory = EducationalInventory::forSelection(
                $data['generation_id'],
                $data['subject_id'],
                $data['teacher_id'],
                $data['platform_id'],
                $data['package_id']
            )->first();

            if (!$inventory) {
                return [
                    'success' => false,
                    'available' => false,
                    'message' => 'المنتج غير متوفر في المخزون'
                ];
            }

            $quantity = $data['quantity'] ?? 1;
            $isAvailable = $inventory->isInStock($quantity);

            return [
                'success' => true,
                'available' => $isAvailable,
                'available_quantity' => $inventory->actual_available,
                'requested_quantity' => $quantity,
                'stock_status' => $inventory->stock_status,
                'message' => $isAvailable ? 'المنتج متوفر' : 'الكمية المطلوبة غير متوفرة'
            ];
        } catch (\Exception $e) {
            Log::error('خطأ في التحقق من المخزون', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);

            return [
                'success' => false,
                'message' => 'حدث خطأ في التحقق من المخزون'
            ];
        }
    }

    /**
     * معالجة طلب تعليمي بعد الدفع
     */
    public function processEducationalOrder(OrderItem $orderItem): array
    {
        if (!$orderItem->is_educational) {
            return [
                'success' => false,
                'message' => 'هذا ليس طلب تعليمي'
            ];
        }

        DB::beginTransaction();

        try {
            $results = [];

            if ($orderItem->isDigitalEducational()) {
                // إنتاج البطاقات الرقمية
                $cards = $this->generateEducationalCards($orderItem);
                $results['cards'] = $cards;
            } else {
                // إنشاء شحنة للدوسيات الورقية
                $shipment = $this->createEducationalShipment($orderItem);
                $results['shipment'] = $shipment;

                // تحديث المخزون
                $this->updateInventoryAfterOrder($orderItem);
            }

            DB::commit();

            return [
                'success' => true,
                'data' => $results
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('خطأ في معالجة الطلب التعليمي', [
                'error' => $e->getMessage(),
                'order_item_id' => $orderItem->id
            ]);

            return [
                'success' => false,
                'message' => 'حدث خطأ في معالجة الطلب التعليمي'
            ];
        }
    }

    /**
     * توليد البطاقات التعليمية
     */
    public function generateEducationalCards(OrderItem $orderItem): array
    {
        $cards = [];

        for ($i = 0; $i < $orderItem->quantity; $i++) {
            $card = EducationalCard::createFromOrderItem($orderItem);
            $cards[] = $card;
        }

        Log::info('تم توليد البطاقات التعليمية', [
            'order_item_id' => $orderItem->id,
            'cards_count' => count($cards)
        ]);

        return $cards;
    }

    /**
     * إنشاء شحنة تعليمية
     */
    public function createEducationalShipment(OrderItem $orderItem): EducationalShipment
    {
        $shipment = EducationalShipment::create([
            'order_item_id' => $orderItem->id,
            'status' => 'preparing'
        ]);

        Log::info('تم إنشاء شحنة تعليمية', [
            'order_item_id' => $orderItem->id,
            'shipment_id' => $shipment->id
        ]);

        return $shipment;
    }

    /**
     * تحديث المخزون بعد الطلب
     */
    public function updateInventoryAfterOrder(OrderItem $orderItem): void
    {
        $inventory = EducationalInventory::forSelection(
            $orderItem->generation_id,
            $orderItem->subject_id,
            $orderItem->teacher_id,
            $orderItem->platform_id,
            $orderItem->package_id
        )->first();

        if ($inventory) {
            $inventory->confirmSale($orderItem->quantity);
            
            Log::info('تم تحديث المخزون', [
                'order_item_id' => $orderItem->id,
                'inventory_id' => $inventory->id,
                'quantity_sold' => $orderItem->quantity
            ]);
        }
    }

    /**
     * إلغاء طلب تعليمي
     */
    public function cancelEducationalOrder(OrderItem $orderItem): array
    {
        if (!$orderItem->is_educational) {
            return [
                'success' => false,
                'message' => 'هذا ليس طلب تعليمي'
            ];
        }

        DB::beginTransaction();

        try {
            if ($orderItem->isDigitalEducational()) {
                // إلغاء البطاقات الرقمية
                $this->cancelEducationalCards($orderItem);
            } else {
                // إلغاء الشحنة وإرجاع المخزون
                $this->cancelEducationalShipment($orderItem);
                $this->restoreInventoryAfterCancel($orderItem);
            }

            DB::commit();

            return [
                'success' => true,
                'message' => 'تم إلغاء الطلب التعليمي بنجاح'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('خطأ في إلغاء الطلب التعليمي', [
                'error' => $e->getMessage(),
                'order_item_id' => $orderItem->id
            ]);

            return [
                'success' => false,
                'message' => 'حدث خطأ في إلغاء الطلب التعليمي'
            ];
        }
    }

    /**
     * إلغاء البطاقات التعليمية
     */
    public function cancelEducationalCards(OrderItem $orderItem): void
    {
        $cards = $orderItem->educationalCards;
        
        foreach ($cards as $card) {
            $card->cancel();
        }

        Log::info('تم إلغاء البطاقات التعليمية', [
            'order_item_id' => $orderItem->id,
            'cards_count' => $cards->count()
        ]);
    }

    /**
     * إلغاء الشحنة التعليمية
     */
    public function cancelEducationalShipment(OrderItem $orderItem): void
    {
        $shipment = $orderItem->educationalShipment;
        
        if ($shipment && $shipment->canBeCancelled()) {
            $shipment->markAsReturned('تم إلغاء الطلب');
            
            Log::info('تم إلغاء الشحنة التعليمية', [
                'order_item_id' => $orderItem->id,
                'shipment_id' => $shipment->id
            ]);
        }
    }

    /**
     * إرجاع المخزون بعد الإلغاء
     */
    public function restoreInventoryAfterCancel(OrderItem $orderItem): void
    {
        $inventory = EducationalInventory::forSelection(
            $orderItem->generation_id,
            $orderItem->subject_id,
            $orderItem->teacher_id,
            $orderItem->platform_id,
            $orderItem->package_id
        )->first();

        if ($inventory) {
            $inventory->addStock($orderItem->quantity);
            
            Log::info('تم إرجاع المخزون', [
                'order_item_id' => $orderItem->id,
                'inventory_id' => $inventory->id,
                'quantity_restored' => $orderItem->quantity
            ]);
        }
    }

    /**
     * تحديث البطاقات المنتهية الصلاحية
     */
    public function updateExpiredCards(): int
    {
        $expiredCards = EducationalCard::where('status', 'active')
                                      ->where('expires_at', '<', now())
                                      ->get();

        $updatedCount = 0;

        foreach ($expiredCards as $card) {
            $card->checkExpiration();
            $updatedCount++;
        }

        if ($updatedCount > 0) {
            Log::info('تم تحديث البطاقات المنتهية الصلاحية', [
                'updated_count' => $updatedCount
            ]);
        }

        return $updatedCount;
    }

    /**
     * الحصول على إحصائيات النظام التعليمي
     */
    public function getEducationalStatistics(): array
    {
        return [
            'total_cards' => EducationalCard::count(),
            'active_cards' => EducationalCard::where('status', 'active')->count(),
            'used_cards' => EducationalCard::where('status', 'used')->count(),
            'expired_cards' => EducationalCard::where('status', 'expired')->count(),
            'total_shipments' => EducationalShipment::count(),
            'pending_shipments' => EducationalShipment::where('status', 'preparing')->count(),
            'shipped_orders' => EducationalShipment::where('status', 'shipped')->count(),
            'delivered_orders' => EducationalShipment::where('status', 'delivered')->count(),
            'total_revenue' => DB::table('order_items')
                                ->where('is_educational', true)
                                ->sum('price'),
            'total_educational_orders' => DB::table('order_items')
                                           ->where('is_educational', true)
                                           ->count()
        ];
    }
}