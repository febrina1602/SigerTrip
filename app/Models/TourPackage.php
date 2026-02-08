<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',             // Tambahkan ini
        'local_tour_agent_id',
        'name',
        'description',
        'cover_image_url',
        'price_per_person',
        'duration',
        'facilities',
        'destinations_visited',
        'is_published',         // Tambahkan ini (sesuai migrasi baru)
    ];

    protected $casts = [
        'price_per_person' => 'decimal:2',
        'destinations_visited' => 'array', 
        'is_published' => 'boolean', // Tambahkan casting boolean
    ];

    /**
     * RELASI: Tour Package dimiliki oleh satu LocalTourAgent (Cabang)
     */
    public function localTourAgent()
    {
        return $this->belongsTo(LocalTourAgent::class);
    }

    /**
     * RELASI: Tour Package dimiliki oleh Agent (Induk)
     */
    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }

    /**
     * RELASI: Tour Package memiliki banyak Destination melalui destinations_visited
     * (Hanya jika Anda menyimpan ID destinasi di JSON)
     */
    public function destinations()
    {
        // Pastikan destinations_visited berisi array ID
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
        
        // Coba decode JSON
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
     * Scope untuk mengambil paket tour yang sudah dipublikasikan
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Logic otomatis saat menyimpan data
     */
    protected static function booted()
    {
        static::saving(function ($model) {
            // Jika local_tour_agent_id diisi, pastikan agent_id (induk) sinkron
            if (!empty($model->local_tour_agent_id)) {
                $local = \App\Models\LocalTourAgent::find($model->local_tour_agent_id);
                if ($local) {
                    $model->agent_id = $local->agent_id;
                }
            }
        });
    }
}