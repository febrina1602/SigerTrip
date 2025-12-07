<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalVehicle extends Model
{
    use HasFactory;

    protected $table = 'rental_vehicles';

    protected $fillable = [
        'agent_id',
        'name',
        'vehicle_type',
        'price_per_day',
        'location',
        'description',
        'image_url',

        // DETAIL KENDARAAN
        'brand',                // Merek kendaraan (Toyota, Honda, dll)
        'model',                // Model (Avanza, Brio, dll)
        'year',                 // Tahun kendaraan
        'transmission',         // Manual / Automatic
        'seats',                // Jumlah kursi
        'plate_number',         // Nomor polisi
        'fuel_type',            // Bensin / Solar / Hybrid

        // OPSI INCLUDE / KEUNTUNGAN PAKET
        'include_driver',       // Boolean
        'include_fuel',         // Boolean
        'include_pickup_drop',  // Antar-jemput

        // PERSYARATAN SEWA
        'min_rental_days',      // Minimal lama sewa
        'terms_conditions',     // S&K tambahan
    ];

    /**
     * Relasi: kendaraan dimiliki oleh satu agent
     */
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
}
