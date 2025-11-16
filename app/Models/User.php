<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'full_name',
        'email',
        'password',
        'role',
        'status',
        'verified_at',
        'phone_number',
        'gender',
        'profile_picture_url',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Helper methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isAgent()
    {
        return $this->role === 'agent';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    public function isVerified()
    {
        return $this->status === 'aktif' && !is_null($this->verified_at);
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    // Accessor untuk nama
    public function getNameAttribute()
    {
        return $this->full_name;
    }
}