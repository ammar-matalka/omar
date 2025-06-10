<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignId('educational_card_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['product', 'educational_card'])->default('product');
            $table->string('item_name')->nullable(); // Store name at purchase time
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['educational_card_id']);
            $table->dropColumn(['educational_card_id', 'type', 'item_name']);
        });
    }
};