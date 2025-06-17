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
        Schema::create('educational_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('educational_orders')->onDelete('cascade');
            
            // للبطاقات التعليمية
            $table->foreignId('subject_id')->nullable()->constrained('educational_subjects')->onDelete('cascade');
            $table->string('subject_name')->nullable();
            
            // للدوسيات
            $table->foreignId('dossier_id')->nullable()->constrained('dossiers')->onDelete('cascade');
            $table->string('dossier_name')->nullable();
            
            // مشترك
            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->onDelete('cascade');
            $table->string('teacher_name')->nullable();
            $table->foreignId('platform_id')->nullable()->constrained('platforms')->onDelete('cascade');
            $table->string('platform_name')->nullable();
            
            $table->decimal('price', 10, 2)->default(0);
            $table->integer('quantity')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('educational_order_items');
    }
};