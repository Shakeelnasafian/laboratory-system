<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    const ROLE_SUPERADMIN   = 'superadmin';
    const ROLE_LAB_ADMIN    = 'lab_admin';
    const ROLE_LAB_INCHARGE = 'lab_incharge';
    const ROLE_RECEPTIONIST = 'receptionist';
    const ROLE_TECHNICIAN   = 'technician';

    protected $fillable = [
        'lab_id', 'name', 'email', 'phone', 'password', 'is_active',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole(self::ROLE_SUPERADMIN);
    }

    public function isLabAdmin(): bool
    {
        return $this->hasRole(self::ROLE_LAB_ADMIN);
    }
}
