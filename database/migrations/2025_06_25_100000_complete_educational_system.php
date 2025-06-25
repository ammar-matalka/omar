<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // التأكد من وجود الجداول الأساسية أولاً، وإنشاؤها إذا لم تكن موجودة
        
        // أجيال الطلاب (سنوات الميلاد)
        if (!Schema::hasTable('generations')) {
            Schema::create('generations', function (Blueprint $table) {
                $table->id();
                $table->integer('birth_year')->unique();
                $table->string('name'); // "جيل 2007"
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // المواد حسب الجيل
        if (!Schema::hasTable('subjects')) {
            Schema::create('subjects', function (Blueprint $table) {
                $table->id();
                $table->foreignId('generation_id')->constrained('generations')->onDelete('cascade');
                $table->string('name');
                $table->string('code')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // المعلمين حسب المادة
        if (!Schema::hasTable('teachers')) {
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
        }

        // المنصات حسب المعلم
        if (!Schema::hasTable('platforms')) {
            Schema::create('platforms', function (Blueprint $table) {
                $table->id();
                $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('website_url')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // أنواع المنتجات التعليمية
        if (!Schema::hasTable('educational_product_types')) {
            Schema::create('educational_product_types', function (Blueprint $table) {
                $table->id();
                $table->string('name'); // 'بطاقات تعليمية', 'دوسيات'
                $table->string('code')->unique(); // 'cards', 'booklets'
                $table->boolean('is_digital')->default(false);
                $table->boolean('requires_shipping')->default(true);
                $table->timestamps();
            });
        }

        // باقات المنتجات التعليمية
        if (!Schema::hasTable('educational_packages')) {
            Schema::create('educational_packages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_type_id')->constrained('educational_product_types');
                $table->foreignId('platform_id')->constrained('platforms')->onDelete('cascade');
                $table->string('name');
                $table->text('description')->nullable();
                $table->integer('duration_days')->nullable();
                $table->integer('lessons_count')->nullable();
                $table->integer('pages_count')->nullable();
                $table->integer('weight_grams')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // المناطق وأسعار الشحن
        if (!Schema::hasTable('shipping_regions')) {
            Schema::create('shipping_regions', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->decimal('shipping_cost', 8, 2)->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // إضافة الأعمدة التعليمية لجدول order_items
        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'is_educational')) {
                $table->boolean('is_educational')->default(false)->after('price');
                $table->unsignedBigInteger('generation_id')->nullable()->after('is_educational');
                $table->unsignedBigInteger('subject_id')->nullable()->after('generation_id');
                $table->unsignedBigInteger('teacher_id')->nullable()->after('subject_id');
                $table->unsignedBigInteger('platform_id')->nullable()->after('teacher_id');
                $table->unsignedBigInteger('package_id')->nullable()->after('platform_id');
                $table->unsignedBigInteger('region_id')->nullable()->after('package_id');
                $table->decimal('shipping_cost', 8, 2)->default(0)->after('region_id');
            }
        });

        // إضافة foreign keys للـ order_items
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreign('generation_id')->references('id')->on('generations')->onDelete('set null');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('set null');
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('set null');
            $table->foreign('platform_id')->references('id')->on('platforms')->onDelete('set null');
            $table->foreign('package_id')->references('id')->on('educational_packages')->onDelete('set null');
            $table->foreign('region_id')->references('id')->on('shipping_regions')->onDelete('set null');
        });

        // إضافة البيانات الأساسية
        $this->insertBasicData();
    }

    private function insertBasicData()
    {
        // إضافة أنواع المنتجات التعليمية
        if (DB::table('educational_product_types')->count() == 0) {
            DB::table('educational_product_types')->insert([
                [
                    'name' => 'بطاقات تعليمية',
                    'code' => 'cards',
                    'is_digital' => true,
                    'requires_shipping' => false,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'دوسيات ورقية',
                    'code' => 'booklets',
                    'is_digital' => false,
                    'requires_shipping' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ]);
        }

        // إضافة مناطق الشحن
        if (DB::table('shipping_regions')->count() == 0) {
            DB::table('shipping_regions')->insert([
                [
                    'name' => 'عمان والزرقاء',
                    'shipping_cost' => 2.00,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'باقي المحافظات',
                    'shipping_cost' => 3.50,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ]);
        }

        // إضافة أجيال أساسية
        if (DB::table('generations')->count() == 0) {
            for ($year = 2005; $year <= 2010; $year++) {
                DB::table('generations')->insert([
                    'birth_year' => $year,
                    'name' => "جيل $year",
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }

    public function down(): void
    {
        // حذف foreign keys من order_items أولاً
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['generation_id']);
            $table->dropForeign(['subject_id']);
            $table->dropForeign(['teacher_id']);
            $table->dropForeign(['platform_id']);
            $table->dropForeign(['package_id']);
            $table->dropForeign(['region_id']);
            
            $table->dropColumn([
                'is_educational',
                'generation_id',
                'subject_id',
                'teacher_id',
                'platform_id',
                'package_id',
                'region_id',
                'shipping_cost'
            ]);
        });

        // حذف الجداول بالترتيب المعكوس
        Schema::dropIfExists('educational_packages');
        Schema::dropIfExists('educational_product_types');
        Schema::dropIfExists('shipping_regions');
        Schema::dropIfExists('platforms');
        Schema::dropIfExists('teachers');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('generations');
    }
};