<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'is_educational')) {
                $table->boolean('is_educational')->default(false)->after('price');
            }
            if (!Schema::hasColumn('order_items', 'generation_id')) {
                $table->foreignId('generation_id')->nullable()->constrained('generations')->after('is_educational');
            }
            if (!Schema::hasColumn('order_items', 'subject_id')) {
                $table->foreignId('subject_id')->nullable()->constrained('subjects')->after('generation_id');
            }
            if (!Schema::hasColumn('order_items', 'teacher_id')) {
                $table->foreignId('teacher_id')->nullable()->constrained('teachers')->after('subject_id');
            }
            if (!Schema::hasColumn('order_items', 'platform_id')) {
                $table->foreignId('platform_id')->nullable()->constrained('platforms')->after('teacher_id');
            }
            if (!Schema::hasColumn('order_items', 'package_id')) {
                $table->foreignId('package_id')->nullable()->constrained('educational_packages')->after('platform_id');
            }
            if (!Schema::hasColumn('order_items', 'region_id')) {
                $table->foreignId('region_id')->nullable()->constrained('shipping_regions')->after('package_id');
            }
            if (!Schema::hasColumn('order_items', 'shipping_cost')) {
                $table->decimal('shipping_cost', 8, 2)->default(0)->after('region_id');
            }
        });
    }

    public function down(): void
    {
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
    }
};