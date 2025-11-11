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
        'popular_activities' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(DestinationCategory::class, 'category_id');
    }
}
