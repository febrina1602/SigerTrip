<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocalTourAgent extends Model
{
    use HasFactory;

    protected $table = 'local_tour_agents';

    /**
     * Mass assignable.
     */
    protected $fillable = [
        'agent_id',
        'name',
        'description',
        'address',
        'location',
        'contact_phone',
        'email',
        'banner_image_url',
        'profile_picture_url',
        'rating',
        'is_verified',
        'is_featured',
    ];

    /**
     * Casts.
     */
    protected $casts = [
        'rating'      => 'decimal:2',
        'is_verified' => 'boolean',
        'is_featured' => 'boolean',
    ];

    /**
     * RELASI: LocalTourAgent dimiliki oleh satu Agent.
     */
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    /**
     * RELASI: LocalTourAgent memiliki banyak TourPackage.
     */
    public function tourPackages()
    {
        return $this->hasMany(TourPackage::class);
    }
}
