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
        Schema::create('educational_card_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('academic_year'); // 2024, 2023, etc.
            $table->string('generation'); // Generation 2024, etc.
            $table->string('subject'); // Math, Science, etc.
            $table->string('teacher'); // Teacher name
            $table->string('semester'); // first, second, full_year
            $table->string('platform'); // Platform name
            $table->string('notebook_type'); // digital, physical, both
            $table->integer('quantity')->default(1);
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->boolean('is_processed')->default(false);
            $table->timestamps();

            // Indexes for better performance
            $table->index(['user_id', 'status']);
            $table->index(['academic_year', 'subject']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('educational_card_orders');
    }
};