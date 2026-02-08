<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rental_vehicles', function (Blueprint $table) {
            $table->string('brand')->nullable();          // merk: Toyota, Honda, dll
            $table->string('model')->nullable();          // Avanza, Vario, dll
            $table->year('year')->nullable();             // tahun produksi
            $table->string('transmission')->nullable();   // Manual / Automatic
            $table->unsignedTinyInteger('seats')->nullable(); // kapasitas kursi
            $table->string('plate_number')->nullable();   // nomor polisi
            $table->string('fuel_type')->nullable();      // Bensin / Diesel / Listrik
            $table->boolean('include_driver')->default(false);
            $table->boolean('include_fuel')->default(false);
            $table->unsignedTinyInteger('min_rental_days')->default(1);
            $table->boolean('include_pickup_drop')->default(false);
            $table->text('terms_conditions')->nullable(); // syarat & ketentuan khusus
        });
    }

    public function down(): void
    {
        Schema::table('rental_vehicles', function (Blueprint $table) {
            $table->dropColumn([
                'brand',
                'model',
                'year',
                'transmission',
                'seats',
                'plate_number',
                'fuel_type',
                'include_driver',
                'include_fuel',
                'min_rental_days',
                'include_pickup_drop',
                'terms_conditions',
            ]);
        });
    }
};
