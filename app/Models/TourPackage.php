<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'name',
        'description',
        'cover_image_url',
        'thumbnail_images',
        'price_per_person',
        'minimum_participants',
        'duration',
        'start_time',
        'end_time',
        'duration_days',
        'duration_nights',
        'availability_period',
        'facilities',
        'destinations_visited',
    ];

    
    protected $casts = [
        'price_per_person' => 'decimal:2',
        'duration_days' => 'integer',
        'duration_nights' => 'integer',
        'thumbnail_images' => 'array', 
        'destinations_visited' => 'array', 
    ];

    /**
     * RELASI: Tour Package dimiliki oleh satu Agent
     */
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    /**
     * RELASI: Tour Package memiliki banyak Destination melalui destinations_visited
     */
    public function destinations()
    {
        return Destination::whereIn('id', $this->destinations_visited ?? [])->get();
    }

    /**
     * Accessor untuk facilities - mengubah menjadi array jika JSON atau split jika text
     */
    public function getFacilitiesArrayAttribute()
    {
        if (empty($this->facilities)) {
            return [];
        }
        
        // Jika sudah array, return langsung
        if (is_array($this->facilities)) {
            return $this->facilities;
        }
        
        // decode JSON
        $decoded = json_decode($this->facilities, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }
        
        // Jika text biasa, split by newline atau comma
        return array_filter(array_map('trim', preg_split('/[\n,]+/', $this->facilities)));
    }

    /**
     * Scope untuk mengambil paket tour dari agent yang sudah diverifikasi
     */
    public function scopeFromVerifiedAgents($query)
    {
        return $query->whereHas('agent', function($q) {
            $q->where('is_verified', true);
        });
    }

    /**
     * Scope untuk mengambil paket tour berdasarkan tipe agent
     */
    public function scopeByAgentType($query, $agentType)
    {
        return $query->whereHas('agent', function($q) use ($agentType) {
            $q->where('agent_type', $agentType);
        });
    }
}

