<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $hosts = [
            ['full_name' => 'Nguyễn Minh Khôi', 'email' => 'host1@example.com', 'phone' => '0912345678', 'gender' => 'male', 'birthday' => '1987-03-12'],
            ['full_name' => 'Trần Thu Hà', 'email' => 'host2@example.com', 'phone' => '0912345679', 'gender' => 'female', 'birthday' => '1989-07-24'],
            ['full_name' => 'Lê Hoàng Nam', 'email' => 'host3@example.com', 'phone' => '0912345680', 'gender' => 'male', 'birthday' => '1991-11-08'],
            ['full_name' => 'Phạm Ngọc Anh', 'email' => 'host4@example.com', 'phone' => '0912345681', 'gender' => 'female', 'birthday' => '1993-01-16'],
        ];

        $users = [
            ['full_name' => 'Nguyễn Văn An', 'email' => 'khach1@example.com', 'phone' => '0909090901', 'gender' => 'male', 'birthday' => '1998-02-14'],
            ['full_name' => 'Trần Thị Bích', 'email' => 'khach2@example.com', 'phone' => '0909090902', 'gender' => 'female', 'birthday' => '1999-05-22'],
            ['full_name' => 'Lê Gia Hân', 'email' => 'khach3@example.com', 'phone' => '0909090903', 'gender' => 'female', 'birthday' => '1997-09-10'],
            ['full_name' => 'Phạm Quốc Bảo', 'email' => 'khach4@example.com', 'phone' => '0909090904', 'gender' => 'male', 'birthday' => '1996-12-01'],
            ['full_name' => 'Đỗ Nhật Minh', 'email' => 'khach5@example.com', 'phone' => '0909090905', 'gender' => 'male', 'birthday' => '2000-03-18'],
            ['full_name' => 'Võ Thanh Trúc', 'email' => 'khach6@example.com', 'phone' => '0909090906', 'gender' => 'female', 'birthday' => '1995-06-06'],
            ['full_name' => 'Bùi Khánh Linh', 'email' => 'khach7@example.com', 'phone' => '0909090907', 'gender' => 'female', 'birthday' => '2001-08-27'],
            ['full_name' => 'Hoàng Đức Long', 'email' => 'khach8@example.com', 'phone' => '0909090908', 'gender' => 'male', 'birthday' => '1994-04-09'],
            ['full_name' => 'Ngô Phương Mai', 'email' => 'khach9@example.com', 'phone' => '0909090909', 'gender' => 'female', 'birthday' => '1998-10-30'],
            ['full_name' => 'Mai Thành Đạt', 'email' => 'khach10@example.com', 'phone' => '0909090910', 'gender' => 'male', 'birthday' => '1997-01-25'],
        ];

        foreach ($hosts as $host) {
            User::updateOrCreate(
                ['email' => $host['email']],
                [
                    'full_name' => $host['full_name'],
                    'password' => Hash::make('host123'),
                    'phone' => $host['phone'],
                    'gender' => $host['gender'],
                    'birthday' => $host['birthday'],
                    'role' => 'host',
                    'auth_provider' => 'local',
                    'avatar_url' => $this->makeAvatarUrl($host['full_name']),
                ]
            );
        }

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'full_name' => $user['full_name'],
                    'password' => Hash::make('user123'),
                    'phone' => $user['phone'],
                    'gender' => $user['gender'],
                    'birthday' => $user['birthday'],
                    'role' => 'user',
                    'auth_provider' => 'local',
                    'avatar_url' => $this->makeAvatarUrl($user['full_name']),
                ]
            );
        }
    }

    private function makeAvatarUrl(string $name): string
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=305b39&color=ffffff&bold=true';
    }
}
