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
        // إعادة إنشاء جدول الطلبات ليدعم النظام الجديد
        Schema::dropIfExists('educational_card_order_items');
        Schema::dropIfExists('educational_card_orders');

        Schema::create('educational_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('generation_id')->constrained('educational_generations')->onDelete('cascade');
            $table->string('student_name');
            $table->enum('order_type', ['card', 'dossier']); // نوع الطلب
            $table->enum('semester', ['first', 'second', 'both']);
            $table->integer('quantity')->default(1);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('educational_orders');
    }
};