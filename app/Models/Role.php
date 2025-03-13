<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'name',
        'description',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
// Thay đổi cho commit #4: Create authentication controllers
// Ngày: 2025-04-01

// Thay đổi cho commit #14: Set up user service and repository pattern
// Ngày: 2025-03-07
