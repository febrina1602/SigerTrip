<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;


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
        'is_verified' => 'boolean', // Ubah 0/1 menjadi true/false
        'rating' => 'decimal:1',    // Rating dengan 1 desimal
    ];

    /**
     * RELASI: Agent dimiliki oleh satu User
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
}
