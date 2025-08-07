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
        // إنشاء جدول generations إذا لم يكن موجوداً
        if (!Schema::hasTable('generations')) {
            Schema::create('generations', function (Blueprint $table) {
                $table->id();
                $table->integer('birth_year')->unique();
                $table->string('name');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });

            // إضافة بيانات أساسية
            $generations = [
                ['birth_year' => 2005, 'name' => 'جيل 2005'],
                ['birth_year' => 2006, 'name' => 'جيل 2006'],
                ['birth_year' => 2007, 'name' => 'جيل 2007'],
                ['birth_year' => 2008, 'name' => 'جيل 2008'],
                ['birth_year' => 2009, 'name' => 'جيل 2009'],
                ['birth_year' => 2010, 'name' => 'جيل 2010'],
            ];

            foreach ($generations as $generation) {
                DB::table('generations')->insert([
                    'birth_year' => $generation['birth_year'],
                    'name' => $generation['name'],
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generations');
    }
};