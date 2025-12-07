<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    // Tabel agents (opsional karena mengikuti konvensi Laravel)
    protected $table = 'agents';

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'user_id',
        'name',                 
        'agent_type',           
        'address',
        'location',
        'contact_phone',
        'is_verified',
        'rating',
        'banner_image_url',
        'description',
    ];

    /**
     * Casts for specific fields.
     */
    protected $casts = [
        'is_verified' => 'boolean',
        'rating'      => 'decimal:1',
    ];

    /**
     * Constants for agent type.
     */
    public const TYPES = ['LOCAL_TOUR', 'TRANSPORT_RENTAL'];

    /**
     * RELASI: Agent milik satu user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * RELASI: Agent memiliki banyak LocalTourAgent.
     */
    public function localTourAgents()
    {
        return $this->hasMany(LocalTourAgent::class);
    }

    /**
     * RELASI: Agent memiliki banyak TourPackage melalui LocalTourAgent.
     */
    public function tourPackages()
    {
        return $this->hasManyThrough(TourPackage::class, LocalTourAgent::class);
    }

    /**
     * RELASI: Agent memiliki banyak kendaraan rental (Pasar Digital).
     */
    public function rentalVehicles()
    {
        return $this->hasMany(RentalVehicle::class);
    }

    // ===== Query Scopes
    public function scopeVerified($q)
    {
        return $q->where('is_verified', true);
    }

    public function scopePending($q)
    {
        return $q->where('is_verified', false);
    }
}
