<?php

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
        // حذف foreign key constraint أولاً
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        // تعديل العمود ليكون nullable
        DB::statement('ALTER TABLE coupons MODIFY user_id BIGINT UNSIGNED NULL');

        // إعادة إضافة foreign key constraint مع SET NULL
        Schema::table('coupons', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // حذف foreign key constraint
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        // إرجاع العمود للحالة السابقة (NOT NULL)
        DB::statement('ALTER TABLE coupons MODIFY user_id BIGINT UNSIGNED NOT NULL');

        // إعادة إضافة foreign key constraint
        Schema::table('coupons', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
};