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

        // 🔽 DETAIL TAMBAHAN
        'brand',                // Merek kendaraan (Toyota, Honda, dll)
        'model',                // Model (Avanza, Brio, dll)
        'year',                 // Tahun kendaraan
        'transmission',         // Manual / Automatic
        'seats',                // Jumlah kursi
        'plate_number',         // Nomor polisi
        'fuel_type',            // Bensin / Solar / Hybrid

        // OPSI KEUNTUNGAN / INCLUDE PAKET
        'include_driver',       // apakah termasuk sopir (boolean)
        'include_fuel',         // apakah termasuk BBM (boolean)
        'include_pickup_drop',  // antar jemput

        // PERSYARATAN SEWA
        'min_rental_days',      // minimal hari sewa
        'terms_conditions',     // syarat & ketentuan tambahan
    ];

    /**
     * Relasi: kendaraan dimiliki oleh satu agent
     */
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
}
