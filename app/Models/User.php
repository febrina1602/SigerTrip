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
        'status',
        'verified_at',
        'gender',
        'profile_picture_url',
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
     *
     * Sesuaikan dengan kolom yang ada di migration.
     * Jika belum ada email_verified_at di tabel users, baris itu bisa dihapus.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'verified_at'       => 'datetime',
        'password'          => 'hashed',
    ];

    /**
     * Relasi: satu user punya satu agent.
     */
    public function agent()
    {
        return $this->hasOne(Agent::class);
    }

    // ===== Helper methods role
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isAgent(): bool
    {
        return $this->role === self::ROLE_AGENT;
    }

    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }

    // ===== Helper methods status/verifikasi
    public function isVerified(): bool
    {
        return $this->status === 'aktif' && !is_null($this->verified_at);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    // Accessor untuk name supaya $user->name mengembalikan full_name
    public function getNameAttribute()
    {
        return $this->full_name;
    }

    /**
     * When a user is created with role 'agent', ensure an Agent record exists.
     */
    protected static function booted()
    {
        static::created(function ($user) {
            if ($user->role === self::ROLE_AGENT && !$user->agent) {
                try {
                    $user->agent()->create([
                        'name' => $user->full_name ?? $user->email ?? 'Agent',
                        'agent_type' => 'LOCAL_TOUR',
                    ]);
                } catch (\Throwable $e) {
                    // Don't throw on user creation; log for investigation.
                    \Log::error('Failed to create Agent for user ' . $user->id . ': ' . $e->getMessage());
                }
            }
        });
    }
}
