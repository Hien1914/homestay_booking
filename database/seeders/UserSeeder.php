<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Mật khẩu mặc định: Guest@123 (có chữ hoa, thường, số, ký tự đặc biệt)
        $guestPassword = Hash::make('Guest@123');
        
        $guests = [
            [
                'full_name'         => 'Phạm Thanh Hà',
                'email'             => 'guest1@gmail.com',
                'phone'             => '0911111111',
                'password_hash'     => $guestPassword,
                'role'              => 'guest',
                'is_email_verified' => true,
            ],
            [
                'full_name'         => 'Hoàng Đức Anh',
                'email'             => 'guest2@gmail.com',
                'phone'             => '0912222222',
                'password_hash'     => $guestPassword,
                'role'              => 'guest',
                'is_email_verified' => true,
            ],
            [
                'full_name'         => 'Vũ Thị Lan',
                'email'             => 'guest3@gmail.com',
                'phone'             => '0913333333',
                'password_hash'     => $guestPassword,
                'role'              => 'guest',
                'is_email_verified' => false,
            ],
            [
                'full_name'         => 'Đỗ Quang Minh',
                'email'             => 'guest4@gmail.com',
                'phone'             => '0914444444',
                'password_hash'     => $guestPassword,
                'role'              => 'guest',
                'is_email_verified' => true,
            ],
            [
                'full_name'         => 'Bùi Thị Hồng',
                'email'             => 'guest5@gmail.com',
                'phone'             => '0915555555',
                'password_hash'     => $guestPassword,
                'role'              => 'guest',
                'is_email_verified' => true,
            ],
        ];

        foreach ($guests as $guest) {
            User::create($guest);
        }
    }
}
