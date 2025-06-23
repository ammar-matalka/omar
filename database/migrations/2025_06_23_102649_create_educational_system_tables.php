<?php
// database/migrations/2025_06_23_000001_create_educational_system_tables.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // أجيال الطلاب (سنوات الميلاد)
        Schema::create('generations', function (Blueprint $table) {
            $table->id();
            $table->integer('birth_year')->unique();
            $table->string('name'); // "جيل 2007"
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // المواد حسب الجيل
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('generation_id')->constrained('generations')->onDelete('cascade');
            $table->string('name');
            $table->string('code')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // المعلمين حسب المادة
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->string('name');
            $table->string('specialization')->nullable();
            $table->text('bio')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // المنصات حسب المعلم
        Schema::create('platforms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('website_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // أنواع المنتجات التعليمية
        Schema::create('educational_product_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // 'بطاقات تعليمية', 'دوسيات'
            $table->string('code')->unique(); // 'cards', 'booklets'
            $table->boolean('is_digital')->default(false); // TRUE للبطاقات، FALSE للدوسيات
            $table->boolean('requires_shipping')->default(true); // FALSE للبطاقات، TRUE للدوسيات
            $table->timestamps();
        });

        // باقات المنتجات التعليمية
        Schema::create('educational_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_type_id')->constrained('educational_product_types');
            $table->foreignId('platform_id')->constrained('platforms')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            // للبطاقات الرقمية
            $table->integer('duration_days')->nullable(); // مدة صلاحية البطاقة
            $table->integer('lessons_count')->nullable(); // عدد الدروس المسموحة (NULL = غير محدود)
            // للدوسيات الورقية
            $table->integer('pages_count')->nullable(); // عدد الصفحات
            $table->integer('weight_grams')->nullable(); // الوزن للحساب شحن
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // المناطق وأسعار الشحن (للدوسيات فقط)
        Schema::create('shipping_regions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('shipping_cost', 8, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // جدول التسعير للمنتجات التعليمية
        Schema::create('educational_pricing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('generation_id')->constrained('generations');
            $table->foreignId('subject_id')->constrained('subjects');
            $table->foreignId('teacher_id')->constrained('teachers');
            $table->foreignId('platform_id')->constrained('platforms');
            $table->foreignId('package_id')->constrained('educational_packages');
            $table->foreignId('region_id')->nullable()->constrained('shipping_regions'); // للدوسيات فقط
            $table->decimal('price', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['generation_id', 'subject_id', 'teacher_id', 'platform_id', 'package_id', 'region_id'], 'unique_pricing');
        });

        // المخزون للدوسيات فقط
        Schema::create('educational_inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('generation_id')->constrained('generations');
            $table->foreignId('subject_id')->constrained('subjects');
            $table->foreignId('teacher_id')->constrained('teachers');
            $table->foreignId('platform_id')->constrained('platforms');
            $table->foreignId('package_id')->constrained('educational_packages');
            $table->integer('quantity_available')->default(0);
            $table->integer('quantity_reserved')->default(0); // للطلبات قيد المعالجة
            $table->timestamps();
            
            $table->unique(['generation_id', 'subject_id', 'teacher_id', 'platform_id', 'package_id'], 'unique_inventory');
        });

        // البطاقات التعليمية الرقمية
        Schema::create('educational_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_item_id')->constrained('order_items');
            $table->string('card_code', 50)->unique();
            $table->string('pin_code', 20);
            $table->foreignId('generation_id')->constrained('generations');
            $table->foreignId('subject_id')->constrained('subjects');
            $table->foreignId('teacher_id')->constrained('teachers');
            $table->foreignId('platform_id')->constrained('platforms');
            $table->foreignId('package_id')->constrained('educational_packages');
            $table->enum('status', ['active', 'used', 'expired', 'cancelled'])->default('active');
            $table->foreignId('used_by_student_id')->nullable()->constrained('users');
            $table->timestamp('used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        // تتبع المنتجات الورقية (الدوسيات)
        Schema::create('educational_shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_item_id')->constrained('order_items');
            $table->string('tracking_number', 100)->nullable();
            $table->enum('status', ['preparing', 'printed', 'shipped', 'delivered', 'returned'])->default('preparing');
            $table->timestamp('printed_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('educational_shipments');
        Schema::dropIfExists('educational_cards');
        Schema::dropIfExists('educational_inventory');
        Schema::dropIfExists('educational_pricing');
        Schema::dropIfExists('shipping_regions');
        Schema::dropIfExists('educational_packages');
        Schema::dropIfExists('educational_product_types');
        Schema::dropIfExists('platforms');
        Schema::dropIfExists('teachers');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('generations');
    }
};