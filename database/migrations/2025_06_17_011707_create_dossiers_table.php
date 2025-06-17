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
        Schema::create('dossiers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('generation_id')->constrained('educational_generations')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('educational_subjects')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->foreignId('platform_id')->constrained('platforms')->onDelete('cascade');
            $table->enum('semester', ['first', 'second', 'both']);
            $table->decimal('price', 10, 2)->default(0);
            $table->text('description')->nullable();
            $table->integer('pages_count')->nullable(); // عدد الصفحات
            $table->string('file_size')->nullable(); // حجم الملف
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dossiers');
    }
};