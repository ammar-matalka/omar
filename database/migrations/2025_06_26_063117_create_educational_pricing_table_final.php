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
        // إنشاء جدول educational_pricing فقط
        if (!Schema::hasTable('educational_pricing')) {
            Schema::create('educational_pricing', function (Blueprint $table) {
                $table->id();
                
                // Foreign Keys
                $table->foreignId('generation_id')->constrained('generations')->onDelete('cascade');
                $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
                $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
                $table->foreignId('platform_id')->constrained('platforms')->onDelete('cascade');
                $table->foreignId('package_id')->constrained('educational_packages')->onDelete('cascade');
                $table->foreignId('region_id')->nullable()->constrained('shipping_regions')->onDelete('set null');
                
                // Pricing Information
                $table->decimal('price', 8, 2); // يدعم حتى 999,999.99
                $table->boolean('is_active')->default(true);
                
                // Timestamps
                $table->timestamps();
                
                // Indexes for better performance
                $table->index(['generation_id', 'subject_id'], 'idx_gen_subject');
                $table->index(['teacher_id', 'platform_id'], 'idx_teacher_platform');
                $table->index(['package_id', 'region_id'], 'idx_package_region');
                $table->index('is_active');
                $table->index('price');
                
                // Unique constraint to prevent duplicates
                $table->unique([
                    'generation_id', 
                    'subject_id', 
                    'teacher_id', 
                    'platform_id', 
                    'package_id', 
                    'region_id'
                ], 'unique_educational_pricing');
            });
            
            echo "✅ تم إنشاء جدول educational_pricing بنجاح\n";
        } else {
            echo "ℹ️ جدول educational_pricing موجود بالفعل\n";
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('educational_pricing');
    }
};