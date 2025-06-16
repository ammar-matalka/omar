
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('educational_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('generation_id')->constrained('educational_generations')->onDelete('cascade');
            $table->string('name');
            $table->decimal('price', 8, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
            
            $table->index(['generation_id', 'is_active', 'order']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('educational_subjects');
    }
};