<?php

namespace Database\Seeders;

use App\Models\Homestay;
use App\Models\Promotion;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PromotionsSeeder extends Seeder
{
    public function run()
    {
        $promotions = [
            [
                'host_email' => 'host1@example.com',
                'homestay_title' => 'Pine Hill Villa Đà Lạt',
                'title' => 'Giảm 15% cho kỳ nghỉ cuối tuần',
                'discount_percent' => 15,
                'min_nights' => 2,
            ],
            [
                'host_email' => 'host2@example.com',
                'homestay_title' => 'Lantern House Hội An',
                'title' => 'Ở 3 đêm giảm 10%',
                'discount_percent' => 10,
                'min_nights' => 3,
            ],
        ];

        foreach ($promotions as $item) {
            $host = User::where('email', $item['host_email'])->first();
            $homestay = Homestay::where('title', $item['homestay_title'])->first();

            if (!$host || !$homestay) {
                continue;
            }

            $promotion = Promotion::updateOrCreate(
                ['user_id' => $host->id, 'title' => $item['title']],
                [
                    'discount_percent' => $item['discount_percent'],
                    'start_date' => Carbon::now()->subDays(7),
                    'end_date' => Carbon::now()->addDays(30),
                    'min_nights' => $item['min_nights'],
                    'is_active' => true,
                ]
            );

            $homestay->update(['promotion_id' => $promotion->id]);
        }
    }
}
