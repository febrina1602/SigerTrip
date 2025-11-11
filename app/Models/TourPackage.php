<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourPackage extends Model
{
    use HasFactory;

    protected $table = 'tour_packages';

    protected $fillable = [
        'agent_id',
        'name',
        'description',
        'cover_image_url',
        'price_per_person',
        'duration',
        'facilities',
        'destinations_visited',
    ];

    protected $casts = [
        'destinations_visited' => 'array',
    ];

    // Relasi: satu paket wisata dimiliki oleh satu agen
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
}
