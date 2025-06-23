<?php
// app/Console/Commands/EducationalMaintenanceCommand.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\EducationalService;
use App\Models\EducationalInventory;
use App\Models\EducationalShipment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EducationalMaintenanceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'educational:maintenance {--type=all : نوع المهمة (all, expire-cards, low-stock, overdue-shipments)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'تشغيل مهام الصيانة للنظام التعليمي';

    /**
     * EducationalService instance
     */
    protected $educationalService;

    /**
     * Create a new command instance.
     */
    public function __construct(EducationalService $educationalService)
    {
        parent::__construct();
        $this->educationalService = $educationalService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->option('type');

        $this->info('🚀 بدء مهام صيانة النظام التعليمي...');

        switch ($type) {
            case 'expire-cards':
                $this->expireCards();
                break;
            case 'low-stock':
                $this->checkLowStock();
                break;
            case 'overdue-shipments':
                $this->checkOverdueShipments();
                break;
            case 'all':
            default:
                $this->expireCards();
                $this->checkLowStock();
                $this->checkOverdueShipments();
                $this->cleanupOldData();
                break;
        }

        $this->info('✅ تم الانتهاء من مهام الصيانة');
    }

    /**
     * تحديث البطاقات المنتهية الصلاحية
     */
    protected function expireCards(): void
    {
        $this->info('🔄 تحديث البطاقات المنتهية الصلاحية...');

        try {
            $updatedCount = $this->educationalService->updateExpiredCards();
            
            if ($updatedCount > 0) {
                $this->info("✅ تم تحديث {$updatedCount} بطاقة منتهية الصلاحية");
                Log::info('تم تحديث البطاقات المنتهية الصلاحية', ['count' => $updatedCount]);
            } else {
                $this->info('ℹ️ لا توجد بطاقات منتهية الصلاحية للتحديث');
            }
        } catch (\Exception $e) {
            $this->error('❌ خطأ في تحديث البطاقات المنتهية: ' . $e->getMessage());
            Log::error('خطأ في تحديث البطاقات المنتهية', ['error' => $e->getMessage()]);
        }
    }

    /**
     * فحص المخزون المنخفض
     */
    protected function checkLowStock(): void
    {
        $this->info('🔄 فحص المخزون المنخفض...');

        try {
            $lowStockItems = EducationalInventory::where('quantity_available', '>', 0)
                                                ->where('quantity_available', '<=', 10)
                                                ->with(['generation', 'subject', 'teacher', 'platform', 'package'])
                                                ->get();

            if ($lowStockItems->count() > 0) {
                $this->warn("⚠️ تم العثور على {$lowStockItems->count()} عنصر بمخزون منخفض");
                
                // عرض تفاصيل المخزون المنخفض
                $this->table(
                    ['المنتج', 'الكمية المتاحة', 'حالة المخزون'],
                    $lowStockItems->map(function($item) {
                        return [
                            $item->package->name . ' - ' . $item->teacher->name,
                            $item->quantity_available,
                            $item->stock_status
                        ];
                    })->toArray()
                );

                // إرسال تنبيه للإدارة (إذا كان مُعد)
                $this->sendLowStockAlert($lowStockItems);
                
                Log::warning('مخزون منخفض تم اكتشافه', ['items_count' => $lowStockItems->count()]);
            } else {
                $this->info('✅ جميع المنتجات لديها مخزون كافي');
            }
        } catch (\Exception $e) {
            $this->error('❌ خطأ في فحص المخزون: ' . $e->getMessage());
            Log::error('خطأ في فحص المخزون المنخفض', ['error' => $e->getMessage()]);
        }
    }

    /**
     * فحص الشحنات المتأخرة
     */
    protected function checkOverdueShipments(): void
    {
        $this->info('🔄 فحص الشحنات المتأخرة...');

        try {
            $overdueShipments = EducationalShipment::where('created_at', '<', now()->subDays(7))
                                                  ->whereIn('status', ['preparing', 'printed', 'shipped'])
                                                  ->with(['orderItem.order.user'])
                                                  ->get();

            if ($overdueShipments->count() > 0) {
                $this->warn("⚠️ تم العثور على {$overdueShipments->count()} شحنة متأخرة");
                
                // عرض تفاصيل الشحنات المتأخرة
                $this->table(
                    ['رقم الطلب', 'العميل', 'الحالة', 'الأيام المتأخرة'],
                    $overdueShipments->map(function($shipment) {
                        return [
                            $shipment->orderItem->order->id,
                            $shipment->orderItem->order->user->name,
                            $shipment->status_display,
                            $shipment->days_since_order
                        ];
                    })->toArray()
                );

                // إرسال تنبيه للإدارة
                $this->sendOverdueShipmentsAlert($overdueShipments);
                
                Log::warning('شحنات متأخرة تم اكتشافها', ['shipments_count' => $overdueShipments->count()]);
            } else {
                $this->info('✅ جميع الشحنات في الوقت المحدد');
            }
        } catch (\Exception $e) {
            $this->error('❌ خطأ في فحص الشحنات: ' . $e->getMessage());
            Log::error('خطأ في فحص الشحنات المتأخرة', ['error' => $e->getMessage()]);
        }
    }

    /**
     * تنظيف البيانات القديمة
     */
    protected function cleanupOldData(): void
    {
        $this->info('🔄 تنظيف البيانات القديمة...');

        try {
            // حذف البطاقات الملغية القديمة (أكثر من سنة)
            $oldCancelledCards = \App\Models\EducationalCard::where('status', 'cancelled')
                                                           ->where('updated_at', '<', now()->subYear())
                                                           ->count();

            if ($oldCancelledCards > 0) {
                \App\Models\EducationalCard::where('status', 'cancelled')
                                          ->where('updated_at', '<', now()->subYear())
                                          ->delete();
                
                $this->info("🗑️ تم حذف {$oldCancelledCards} بطاقة ملغية قديمة");
                Log::info('تم حذف البطاقات الملغية القديمة', ['count' => $oldCancelledCards]);
            }

            // تنظيف الشحنات المُسلمة القديمة (أكثر من سنتين)
            $oldDeliveredShipments = EducationalShipment::where('status', 'delivered')
                                                       ->where('delivered_at', '<', now()->subYears(2))
                                                       ->count();

            if ($oldDeliveredShipments > 0) {
                EducationalShipment::where('status', 'delivered')
                                  ->where('delivered_at', '<', now()->subYears(2))
                                  ->delete();
                
                $this->info("🗑️ تم حذف {$oldDeliveredShipments} شحنة قديمة مُسلمة");
                Log::info('تم حذف الشحنات القديمة المُسلمة', ['count' => $oldDeliveredShipments]);
            }

            if ($oldCancelledCards === 0 && $oldDeliveredShipments === 0) {
                $this->info('✅ لا توجد بيانات قديمة للحذف');
            }
        } catch (\Exception $e) {
            $this->error('❌ خطأ في تنظيف البيانات: ' . $e->getMessage());
            Log::error('خطأ في تنظيف البيانات القديمة', ['error' => $e->getMessage()]);
        }
    }

    /**
     * إرسال تنبيه المخزون المنخفض
     */
    protected function sendLowStockAlert($lowStockItems): void
    {
        try {
            // يمكنك إضافة منطق إرسال البريد الإلكتروني هنا
            // Mail::to(config('educational.admin_email'))->send(new LowStockAlert($lowStockItems));
            
            Log::info('تم إرسال تنبيه المخزون المنخفض', ['items_count' => $lowStockItems->count()]);
        } catch (\Exception $e) {
            Log::error('خطأ في إرسال تنبيه المخزون المنخفض', ['error' => $e->getMessage()]);
        }
    }

    /**
     * إرسال تنبيه الشحنات المتأخرة
     */
    protected function sendOverdueShipmentsAlert($overdueShipments): void
    {
        try {
            // يمكنك إضافة منطق إرسال البريد الإلكتروني هنا
            // Mail::to(config('educational.admin_email'))->send(new OverdueShipmentsAlert($overdueShipments));
            
            Log::info('تم إرسال تنبيه الشحنات المتأخرة', ['shipments_count' => $overdueShipments->count()]);
        } catch (\Exception $e) {
            Log::error('خطأ في إرسال تنبيه الشحنات المتأخرة', ['error' => $e->getMessage()]);
        }
    }
}