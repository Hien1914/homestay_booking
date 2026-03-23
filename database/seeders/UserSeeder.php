<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'              => 'Admin',
            'email'             => 'admin@homestay.vn',
            'password'          => bcrypt('password'),
            'role'              => 'admin',
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name'              => 'Tran Thi User',
            'email'             => 'user@homestay.vn',
            'password'          => bcrypt('password'),
            'role'              => 'user',
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);
    }
}
