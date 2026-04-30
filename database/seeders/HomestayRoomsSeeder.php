<?php

namespace Database\Seeders;

use App\Models\Homestay;
use App\Models\HomestayRoom;
use Illuminate\Database\Seeder;

class HomestayRoomsSeeder extends Seeder
{
    public function run()
    {
        $roomBlueprints = [
            ['bedroom' => 3, 'bathroom' => 2],
            ['bedroom' => 4, 'bathroom' => 3, 'kitchen' => 1],
            ['bedroom' => 2, 'bathroom' => 2],
            ['bedroom' => 4, 'bathroom' => 4, 'pool' => 1],
            ['bedroom' => 3, 'bathroom' => 2],
            ['bedroom' => 3, 'bathroom' => 2],
            ['bedroom' => 2, 'bathroom' => 2],
            ['bedroom' => 2, 'bathroom' => 1],
            ['bedroom' => 3, 'bathroom' => 2],
            ['bedroom' => 2, 'bathroom' => 2],
        ];

        $homestays = Homestay::orderBy('id')->get();

        foreach ($homestays as $index => $homestay) {
            $blueprint = $roomBlueprints[$index % count($roomBlueprints)];

            foreach ($blueprint as $featureType => $quantity) {
                HomestayRoom::updateOrCreate(
                    ['homestay_id' => $homestay->id, 'feature_type' => $featureType],
                    ['quantity' => $quantity]
                );
            }
        }
    }
}
