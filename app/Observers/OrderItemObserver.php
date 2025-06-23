<?php
// app/Observers/OrderItemObserver.php

namespace App\Observers;

use App\Models\OrderItem;
use App\Services\EducationalService;
use Illuminate\Support\Facades\Log;

class OrderItemObserver
{
    protected $educationalService;

    public function __construct(EducationalService $educationalService)
    {
        $this->educationalService = $educationalService;
    }

    /**
     * Handle the OrderItem "created" event.
     */
    public function created(OrderItem $orderItem): void
    {
        // معالجة الطلبات التعليمية بعد الإنشاء
        if ($orderItem->is_educational) {
            $this->processEducationalOrderItem($orderItem);
        }
    }

    /**
     * Handle the OrderItem "updated" event.
     */
    public function updated(OrderItem $orderItem): void
    {
        // لا نحتاج معالجة خاصة عند التحديث حالياً
    }

    /**
     * Handle the OrderItem "deleted" event.
     */
    public function deleted(OrderItem $orderItem): void
    {
        // إلغاء الطلبات التعليمية عند الحذف
        if ($orderItem->is_educational) {
            $this->cancelEducationalOrderItem($orderItem);
        }
    }

    /**
     * Handle the OrderItem "restored" event.
     */
    public function restored(OrderItem $orderItem): void
    {
        // إعادة معالجة الطلبات التعليمية عند الاستعادة
        if ($orderItem->is_educational) {
            $this->processEducationalOrderItem($orderItem);
        }
    }

    /**
     * Handle the OrderItem "force deleted" event.
     */
    public function forceDeleted(OrderItem $orderItem): void
    {
        // إلغاء نهائي للطلبات التعليمية
        if ($orderItem->is_educational) {
            $this->cancelEducationalOrderItem($orderItem);
        }
    }

    /**
     * معالجة الطلب التعليمي
     */
    private function processEducationalOrderItem(OrderItem $orderItem): void
    {
        try {
            // التأكد من أن الطلب مدفوع ومؤكد
            if ($orderItem->order->status === 'pending') {
                return; // انتظار حتى يتم الدفع
            }

            $result = $this->educationalService->processEducationalOrder($orderItem);
            
            if (!$result['success']) {
                Log::error('فشل في معالجة الطلب التعليمي', [
                    'order_item_id' => $orderItem->id,
                    'error' => $result['message']
                ]);
            }
        } catch (\Exception $e) {
            Log::error('خطأ في Observer معالجة الطلب التعليمي', [
                'order_item_id' => $orderItem->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * إلغاء الطلب التعليمي
     */
    private function cancelEducationalOrderItem(OrderItem $orderItem): void
    {
        try {
            $result = $this->educationalService->cancelEducationalOrder($orderItem);
            
            if (!$result['success']) {
                Log::error('فشل في إلغاء الطلب التعليمي', [
                    'order_item_id' => $orderItem->id,
                    'error' => $result['message']
                ]);
            }
        } catch (\Exception $e) {
            Log::error('خطأ في Observer إلغاء الطلب التعليمي', [
                'order_item_id' => $orderItem->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}