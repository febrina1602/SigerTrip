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
        Schema::create('tour_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('agents')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('cover_image_url')->nullable();
            $table->json('thumbnail_images')->nullable();
            $table->decimal('price_per_person', 12, 2)->default(0);
            $table->integer('minimum_participants')->nullable();
            $table->string('duration')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->integer('duration_days')->nullable();
            $table->integer('duration_nights')->nullable();
            $table->string('availability_period')->nullable();
            $table->json('facilities')->nullable();
            $table->json('destinations_visited')->nullable();
            $table->timestamps();
            
            $table->index('agent_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_packages');
    }
};
