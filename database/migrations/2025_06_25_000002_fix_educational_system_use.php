<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // التأكد من وجود الجداول المطلوبة أولاً
        if (!Schema::hasTable('educational_packages')) {
            throw new Exception('Table educational_packages does not exist. Please run educational system migration first.');
        }

        Schema::table('order_items', function (Blueprint $table) {
            // إضافة الأعمدة بدون foreign keys أولاً
            if (!Schema::hasColumn('order_items', 'is_educational')) {
                $table->boolean('is_educational')->default(false)->after('price');
            }
            
            if (!Schema::hasColumn('order_items', 'generation_id')) {
                $table->unsignedBigInteger('generation_id')->nullable()->after('is_educational');
            }
            
            if (!Schema::hasColumn('order_items', 'subject_id')) {
                $table->unsignedBigInteger('subject_id')->nullable()->after('generation_id');
            }
            
            if (!Schema::hasColumn('order_items', 'teacher_id')) {
                $table->unsignedBigInteger('teacher_id')->nullable()->after('subject_id');
            }
            
            if (!Schema::hasColumn('order_items', 'platform_id')) {
                $table->unsignedBigInteger('platform_id')->nullable()->after('teacher_id');
            }
            
            if (!Schema::hasColumn('order_items', 'package_id')) {
                $table->unsignedBigInteger('package_id')->nullable()->after('platform_id');
            }
            
            if (!Schema::hasColumn('order_items', 'region_id')) {
                $table->unsignedBigInteger('region_id')->nullable()->after('package_id');
            }
            
            if (!Schema::hasColumn('order_items', 'shipping_cost')) {
                $table->decimal('shipping_cost', 8, 2)->default(0)->after('region_id');
            }
        });

        // إضافة foreign keys في خطوة منفصلة
        Schema::table('order_items', function (Blueprint $table) {
            // التحقق من وجود الجداول قبل إضافة foreign keys
            if (Schema::hasTable('generations')) {
                $table->foreign('generation_id')->references('id')->on('generations')->onDelete('set null');
            }
            
            if (Schema::hasTable('subjects')) {
                $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('set null');
            }
            
            if (Schema::hasTable('teachers')) {
                $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('set null');
            }
            
            if (Schema::hasTable('platforms')) {
                $table->foreign('platform_id')->references('id')->on('platforms')->onDelete('set null');
            }
            
            if (Schema::hasTable('educational_packages')) {
                $table->foreign('package_id')->references('id')->on('educational_packages')->onDelete('set null');
            }
            
            if (Schema::hasTable('shipping_regions')) {
                $table->foreign('region_id')->references('id')->on('shipping_regions')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // حذف foreign keys أولاً
            $foreignKeys = [
                'order_items_generation_id_foreign',
                'order_items_subject_id_foreign', 
                'order_items_teacher_id_foreign',
                'order_items_platform_id_foreign',
                'order_items_package_id_foreign',
                'order_items_region_id_foreign'
            ];

            foreach ($foreignKeys as $key) {
                try {
                    $table->dropForeign([$key]);
                } catch (Exception $e) {
                    // تجاهل الخطأ إذا كان foreign key غير موجود
                }
            }

            // حذف الأعمدة
            $columns = [
                'is_educational',
                'generation_id',
                'subject_id', 
                'teacher_id',
                'platform_id',
                'package_id',
                'region_id',
                'shipping_cost'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('order_items', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};