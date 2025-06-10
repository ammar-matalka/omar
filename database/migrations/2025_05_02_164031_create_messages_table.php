<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // In database/migrations/xxxx_xx_xx_create_messages_table.php
public function up()
{
    Schema::create('messages', function (Blueprint $table) {
        $table->id();
        $table->foreignId('conversation_id')->constrained()->onDelete('cascade');
        $table->foreignId('user_id')->constrained(); // Sender ID
        $table->text('message');
        $table->boolean('is_from_admin')->default(false);
        $table->timestamps();
    });
}
};
