<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DestinationCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon_url',
    ];

   
    public function destinations()
    {
        return $this->hasMany(Destination::class);
    }
}
