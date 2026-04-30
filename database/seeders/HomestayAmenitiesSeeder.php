<?php

namespace Database\Seeders;

use App\Models\Homestay;
use App\Models\Amenity;
use Illuminate\Database\Seeder;

class HomestayAmenitiesSeeder extends Seeder
{
    public function run()
    {
        $villaDalat = Homestay::where('title', 'Villa Đà Lạt view đồi thông')->first();
        $lantern = Homestay::where('title', 'Lantern House Hội An')->first();
        $villaAnBang = Homestay::where('title', 'Villa An Bàng Hội An')->first();

        $wifi = Amenity::where('name', 'Wi-Fi')->first();
        $ac = Amenity::where('name', 'Điều hòa')->first();
        $parking = Amenity::where('name', 'Bãi đỗ xe')->first();
        $pool = Amenity::where('name', 'Hồ bơi')->first();

        if ($villaDalat && $wifi && $ac && $parking) {
            $villaDalat->amenities()->syncWithoutDetaching([
                $wifi->id => ['quantity' => 1],
                $ac->id => ['quantity' => 3],
                $parking->id => ['quantity' => 2],
            ]);
        }

        if ($lantern && $wifi && $ac && $parking) {
            $lantern->amenities()->syncWithoutDetaching([
                $wifi->id => ['quantity' => 1],
                $ac->id => ['quantity' => 2],
                $parking->id => ['quantity' => 1],
            ]);
        }

        if ($villaAnBang && $wifi && $ac && $parking && $pool) {
            $villaAnBang->amenities()->syncWithoutDetaching([
                $wifi->id => ['quantity' => 1],
                $ac->id => ['quantity' => 4],
                $parking->id => ['quantity' => 3],
                $pool->id => ['quantity' => 1],
            ]);
        }
    }
}