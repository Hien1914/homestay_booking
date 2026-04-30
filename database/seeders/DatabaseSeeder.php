<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(DestinationsSeeder::class);
        $this->call(AmenitiesSeeder::class);
        $this->call(HomestaysSeeder::class);
        $this->call(HomestayRoomsSeeder::class);
        $this->call(HomestayAmenitiesSeeder::class);
        $this->call(BookingsSeeder::class);
        $this->call(PaymentsSeeder::class);
        $this->call(ReviewsSeeder::class);
        $this->call(PromotionsSeeder::class);
        $this->call(HostApplicationsSeeder::class);
        $this->call(PayoutsSeeder::class);
    }
}