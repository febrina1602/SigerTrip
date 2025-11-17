<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    // Tabel 'agents' sudah sesuai konvensi, properti $table sebenarnya opsional.
    protected $table = 'agents';

    /**
     * Mass assignable.
     */
    protected $fillable = [
        'user_id',
        'name',             // nama instansi/perusahaan agen
        'agent_type',       // 'LOCAL_TOUR' atau 'TRANSPORT_RENTAL'
        'address',
        'location',
        'contact_phone',
        'is_verified',
        'rating',
        'banner_image_url',
        'description',
    ];

    /**
     * Casts.
     */
    protected $casts = [
        'is_verified' => 'boolean',   // ubah 0/1 menjadi true/false
        'rating'      => 'decimal:1', // rating dengan 1 desimal
    ];

    /**
     * Konstanta jenis agen (opsional, untuk acuan validasi/UI).
     */
    public const TYPES = ['LOCAL_TOUR', 'TRANSPORT_RENTAL'];

    /**
     * Relasi ke user (kebalikan dari hasOne di User).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * RELASI: Agent memiliki banyak Tour Package
     */
    public function tourPackages()
    {
        return $this->hasMany(TourPackage::class);
    }

    // ===== Query Scopes (memudahkan filter di controller)
    public function scopeVerified($q)
    {
        return $q->where('is_verified', true);
    }

    public function scopePending($q)
    {
        return $q->where('is_verified', false);
    }
}
