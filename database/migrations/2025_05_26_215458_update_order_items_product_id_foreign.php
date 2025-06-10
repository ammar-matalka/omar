<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            // احذف المفتاح الأجنبي القديم أولاً
            $table->dropForeign(['product_id']);

            // عدل العمود ليصير nullable
            $table->foreignId('product_id')->nullable()->change();

            // أضف المفتاح الأجنبي الجديد مع ON DELETE SET NULL
            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->foreignId('product_id')->constrained()->change();
        });
    }
};
