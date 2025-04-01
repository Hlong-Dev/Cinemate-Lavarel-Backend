<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Thêm role ADMIN
        Role::create([
            'name' => 'ROLE_ADMIN',
            'description' => 'Quyền quản trị hệ thống'
        ]);
        
        // Thêm role USER
        Role::create([
            'name' => 'ROLE_USER',
            'description' => 'Quyền người dùng thông thường'
        ]);
        
        // Thêm role MODERATOR nếu cần
        Role::create([
            'name' => 'ROLE_MODERATOR',
            'description' => 'Quyền điều hành'
        ]);
    }
}