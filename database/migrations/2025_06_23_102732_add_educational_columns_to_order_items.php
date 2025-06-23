<?php
// database/migrations/2025_06_23_000002_add_educational_columns_to_order_items.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->boolean('is_educational')->default(false)->after('price');
            $table->foreignId('generation_id')->nullable()->constrained('generations')->after('is_educational');
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->after('generation_id');
            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->after('subject_id');
            $table->foreignId('platform_id')->nullable()->constrained('platforms')->after('teacher_id');
            $table->foreignId('package_id')->nullable()->constrained('educational_packages')->after('platform_id');
            $table->foreignId('region_id')->nullable()->constrained('shipping_regions')->after('package_id');
            $table->decimal('shipping_cost', 8, 2)->default(0)->after('region_id');
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