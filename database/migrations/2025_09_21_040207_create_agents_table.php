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
        Schema::create('agents', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel users
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->unique();

            // Informasi dasar
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->text('address')->nullable();

            // Nomor telepon utama agent
            $table->string('phone_number')->nullable(); 

            // Tambahan kolom yang dibutuhkan controller
            $table->string('agent_type')->default('LOCAL_TOUR'); // <- wajib ada
            $table->string('contact_phone')->nullable();         // <- jika dipakai di controller

            // Banner / foto agent
            $table->string('banner_image_url')->nullable(); 

            // Status verifikasi
            $table->boolean('is_verified')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
