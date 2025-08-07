<?php
// database/migrations/2025_06_27_000001_ensure_educational_product_types_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // إنشاء جدول educational_product_types إذا لم يكن موجود
        if (!Schema::hasTable('educational_product_types')) {
            Schema::create('educational_product_types', function (Blueprint $table) {
                $table->id();
                $table->string('name'); // 'بطاقة رقمية', 'دوسية ورقية'
                $table->string('code')->unique(); // 'DIGITAL_CARD', 'PHYSICAL_BOOKLET'
                $table->string('display_type'); // 'رقمي', 'ورقي'
                $table->boolean('is_digital')->default(false); // TRUE للبطاقات، FALSE للدوسيات
                $table->boolean('requires_shipping')->default(true); // FALSE للبطاقات، TRUE للدوسيات
                $table->text('description')->nullable(); // وصف نوع المنتج
                $table->json('features')->nullable(); // مميزات خاصة بكل نوع
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                
                // Indexes
                $table->index('is_digital');
                $table->index('requires_shipping');
                $table->index('is_active');
            });
            
            echo "✅ تم إنشاء جدول educational_product_types\n";
        } else {
            echo "ℹ️ جدول educational_product_types موجود بالفعل\n";
        }

        // إضافة البيانات الأساسية
        $this->seedProductTypes();
        
        // التأكد من وجود العمود في جدول educational_packages
        if (Schema::hasTable('educational_packages') && !Schema::hasColumn('educational_packages', 'product_type_id')) {
            Schema::table('educational_packages', function (Blueprint $table) {
                $table->foreignId('product_type_id')->nullable()->after('id')->constrained('educational_product_types');
            });
            echo "✅ تم إضافة عمود product_type_id لجدول educational_packages\n";
            
            // تحديث الباقات الموجودة
            $this->updateExistingPackages();
        }
    }

    /**
     * إضافة أنواع المنتجات الأساسية
     */
    private function seedProductTypes(): void
    {
        $productTypes = [
            [
                'name' => 'بطاقة تعليمية رقمية',
                'code' => 'DIGITAL_CARD',
                'display_type' => 'رقمي',
                'is_digital' => true,
                'requires_shipping' => false,
                'description' => 'بطاقات تعليمية رقمية يتم توليدها تلقائياً وإرسالها للطلاب إلكترونياً',
                'features' => json_encode([
                    'تسليم فوري',
                    'لا تحتاج شحن',
                    'صلاحية قابلة للتخصيص',
                    'تتبع الاستخدام',
                    'عدد دروس قابل للتحديد'
                ]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'دوسية ورقية',
                'code' => 'PHYSICAL_BOOKLET',
                'display_type' => 'ورقي',
                'is_digital' => false,
                'requires_shipping' => true,
                'description' => 'دوسيات ورقية مطبوعة يتم شحنها للطلاب',
                'features' => json_encode([
                    'طباعة عالية الجودة',
                    'شحن للمنزل',
                    'إدارة مخزون',
                    'تتبع الشحنات',
                    'مواصفات فيزيائية (صفحات، وزن)'
                ]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($productTypes as $productType) {
            // التحقق من عدم وجود النوع مسبقاً
            $exists = DB::table('educational_product_types')
                       ->where('code', $productType['code'])
                       ->exists();
            
            if (!$exists) {
                DB::table('educational_product_types')->insert($productType);
                echo "✅ تم إضافة نوع المنتج: {$productType['name']}\n";
            } else {
                echo "ℹ️ نوع المنتج موجود مسبقاً: {$productType['name']}\n";
            }
        }
    }

    /**
     * تحديث الباقات الموجودة لربطها بأنواع المنتجات
     */
    private function updateExistingPackages(): void
    {
        // جلب أنواع المنتجات
        $digitalType = DB::table('educational_product_types')
                        ->where('is_digital', true)
                        ->first();
        
        $physicalType = DB::table('educational_product_types')
                         ->where('is_digital', false)
                         ->first();

        if (!$digitalType || !$physicalType) {
            echo "⚠️ أنواع المنتجات غير متوفرة لتحديث الباقات الموجودة\n";
            return;
        }

        // تحديث الباقات الموجودة بناءً على خصائصها
        
        // البطاقات الرقمية (التي لها duration_days أو lessons_count)
        $digitalPackagesUpdated = DB::table('educational_packages')
                                   ->where('product_type_id', null)
                                   ->where(function($query) {
                                       $query->whereNotNull('duration_days')
                                             ->orWhereNotNull('lessons_count');
                                   })
                                   ->update(['product_type_id' => $digitalType->id]);

        // الدوسيات الورقية (التي لها pages_count أو weight_grams)
        $physicalPackagesUpdated = DB::table('educational_packages')
                                    ->where('product_type_id', null)
                                    ->where(function($query) {
                                        $query->whereNotNull('pages_count')
                                              ->orWhereNotNull('weight_grams');
                                    })
                                    ->update(['product_type_id' => $physicalType->id]);

        // الباقات المتبقية (افتراضياً دوسيات ورقية)
        $remainingPackagesUpdated = DB::table('educational_packages')
                                     ->where('product_type_id', null)
                                     ->update(['product_type_id' => $physicalType->id]);

        echo "✅ تم تحديث {$digitalPackagesUpdated} باقة رقمية\n";
        echo "✅ تم تحديث {$physicalPackagesUpdated} دوسية ورقية\n";
        
        if ($remainingPackagesUpdated > 0) {
            echo "✅ تم تحديث {$remainingPackagesUpdated} باقة إضافية كدوسيات ورقية\n";
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // إزالة العمود من educational_packages
        if (Schema::hasTable('educational_packages') && Schema::hasColumn('educational_packages', 'product_type_id')) {
            Schema::table('educational_packages', function (Blueprint $table) {
                $table->dropForeign(['product_type_id']);
                $table->dropColumn('product_type_id');
            });
        }

        // حذف جدول educational_product_types
        Schema::dropIfExists('educational_product_types');
    }
};