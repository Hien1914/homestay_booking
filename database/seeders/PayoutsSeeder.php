<?php

namespace Database\Seeders;

use App\Models\Payout;
use App\Models\User;
use Illuminate\Database\Seeder;

class PayoutsSeeder extends Seeder
{
    public function run()
    {
        $host = User::where('email', 'host1@example.com')->first();
        if ($host && $host->role === 'host') {
            Payout::updateOrCreate(
                ['host_id' => $host->id, 'amount' => 500000],
                ['status' => 'pending']
            );
        }
    }
}