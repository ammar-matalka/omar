<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // In database/migrations/xxxx_xx_xx_create_conversations_table.php
public function up()
{
    Schema::create('conversations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained();
        $table->string('title')->nullable();
        $table->boolean('is_read_by_user')->default(true);
        $table->boolean('is_read_by_admin')->default(false);
        $table->timestamps();
    });
}
};