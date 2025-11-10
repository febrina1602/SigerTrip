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
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('destination_categories')->nullOnDelete();
            $table->string('name');
            $table->text('address')->nullable();
            $table->text('description')->nullable();
            $table->text('facilities')->nullable();
            $table->string('image_url')->nullable();
            $table->decimal('rating', 3, 2)->default(0); 
            $table->decimal('price_per_person', 12, 2)->default(0); 
            $table->decimal('parking_price', 12, 2)->default(0); 
            $table->text('popular_activities')->nullable();
            $table->boolean('is_featured')->default(false); 
            $table->decimal('latitude', 10, 8); 
            $table->decimal('longitude', 11, 8); 
            $table->timestamps(); 
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destinations');
    }
};