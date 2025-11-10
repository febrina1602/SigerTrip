<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'address',
        'latitude',
        'longitude',
        'description',
        'facilities',
        'image_url',
        'rating',
        'price_per_person',
        'parking_price',
        'popular_activities',
        'is_featured',
    ];

    protected $casts = [
        'rating' => 'decimal:2',
        'price_per_person' => 'decimal:2',
        'parking_price' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_featured' => 'boolean',
    ];

    /**
     * Accessor untuk popular_activities
     * Mengubah JSON string menjadi array jika perlu
     */
    public function getPopularActivitiesAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        
        if (is_array($value)) {
            return $value;
        }
        
        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }
        
        return $value;
    }
    
    /**
     *  untuk mengambil destinasi yang featured (rekomendasi)
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * RELASI: Destinasi dimiliki oleh satu Kategori
     */
    public function category()
    {
        return $this->belongsTo(DestinationCategory::class);
    }
    /**
     * Scope untuk mengambil destinasi berdasarkan kategori
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }
}
