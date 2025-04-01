<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'username',
        'password',
        'email',
        'phone',
        'address',
        'provider',
        'provider_id',
        'avt_url',
        'account_non_locked',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'account_non_locked' => 'boolean',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole($roleName)
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    public function isAdmin()
    {
        return $this->hasRole('ROLE_ADMIN');
    }
}