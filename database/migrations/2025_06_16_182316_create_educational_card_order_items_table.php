<?php

// 4. database/migrations/2024_01_01_000004_create_educational_card_order_items_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('educational_card_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('educational_card_orders')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('educational_subjects');
            $table->string('subject_name'); // حفظ الاسم وقت الطلب
            $table->decimal('price', 8, 2); // حفظ السعر وقت الطلب
            $table->integer('quantity')->default(1);
            $table->timestamps();
            
            $table->index(['order_id']);
            $table->index(['subject_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('educational_card_order_items');
    }
};