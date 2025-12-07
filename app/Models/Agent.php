<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    protected $table = 'agents';

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

    protected $casts = [
        'is_verified' => 'boolean',
        'rating'      => 'decimal:1',
    ];

    public const TYPES = ['LOCAL_TOUR', 'TRANSPORT_RENTAL'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function localTourAgents()
    {
        return $this->hasMany(LocalTourAgent::class);
    }

    public function tourPackages()
    {
        return $this->hasMany(TourPackage::class, 'agent_id');
    }

    public function rentalVehicles()
    {
        return $this->hasMany(RentalVehicle::class);
    }

    public function scopeVerified($q)
    {
        return $q->where('is_verified', true);
    }

    public function scopePending($q)
    {
        return $q->where('is_verified', false);
    }

    // ======= TAMBAHKAN ACCESSOR =======
    public function getIsProfileCompleteAttribute()
    {
        // Hitung profil lengkap jika semua field wajib ada
        return !empty($this->name)
            && !empty($this->address)
            && !empty($this->contact_phone)
            && !empty($this->description);
    }
}
