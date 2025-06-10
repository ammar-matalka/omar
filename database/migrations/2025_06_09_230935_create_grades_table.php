<?php

// ==============================================
// Migration 2: create_grades_table.php
// ==============================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('platform_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('name_ar')->nullable();
            $table->integer('grade_number'); // 1-12
            $table->text('description')->nullable();
            $table->text('description_ar')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['platform_id', 'grade_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
