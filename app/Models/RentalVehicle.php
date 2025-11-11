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
    ];

    // Relasi: satu kendaraan dimiliki oleh satu agen
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
}
