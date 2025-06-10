<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreignId('educational_card_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('type', ['product', 'educational_card'])->default('product');
            
            // Make product_id nullable since we can have educational cards
            $table->foreignId('product_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign(['educational_card_id']);
            $table->dropColumn(['educational_card_id', 'type']);
        });
    }
};