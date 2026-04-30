<?php

namespace Database\Seeders;

use App\Models\HostApplication;
use App\Models\User;
use Illuminate\Database\Seeder;

class HostApplicationsSeeder extends Seeder
{
    public function run()
    {
        $user = User::where('email', 'khach1@example.com')->first();
        if ($user && $user->role === 'user') {
            HostApplication::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'id_card' => '123456789012',
                    'bank_acc' => '1234567890',
                    'bank_name' => 'Vietcombank',
                    'bank_holder' => 'Nguyễn Văn An',
                    'status' => 'pending',
                ]
            );
        }
    }
}