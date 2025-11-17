<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // ===== Roles (ikuti enum di migration: admin, agent, user)
    public const ROLE_ADMIN = 'admin';
    public const ROLE_AGENT = 'agent';
    public const ROLE_USER  = 'user';

    /**
     * Mass assignable.
     */
    protected $fillable = [
        'full_name',
        'email',
        'password',
        'phone_number',
        'role',
    ];

    /**
     * Hidden attributes.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts.
     * Catatan: tidak men-cast email_verified_at karena kolom itu tidak ada di migration Anda.
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * Relasi: satu user punya satu agent.
     */
    public function agent()
    {
        return $this->hasOne(Agent::class);
    }

    // ===== Helper methods
    public function isAdmin(): bool { return $this->role === self::ROLE_ADMIN; }
    public function isAgent(): bool { return $this->role === self::ROLE_AGENT; }
    public function isUser():  bool { return $this->role === self::ROLE_USER;  }
}
