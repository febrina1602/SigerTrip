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
        Schema::create('local_tour_agents', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel agents (agen yang membuat/mengelola agen tour lokal ini)
            $table->foreignId('agent_id')
                  ->constrained('agents')
                  ->onDelete('cascade');

            // Informasi dasar agen tour lokal
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('address')->nullable();
            $table->string('location')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('email')->nullable();

            // Media
            $table->string('banner_image_url')->nullable();
            $table->string('profile_picture_url')->nullable();

            // Rating dan status verifikasi
            $table->decimal('rating', 3, 2)->default(0);
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_featured')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('local_tour_agents');
    }
};
