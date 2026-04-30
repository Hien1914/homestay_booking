<?php

namespace Database\Seeders;

use App\Models\Destination;
use App\Models\Homestay;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class HomestaysSeeder extends Seeder
{
    public function run()
    {
        $hosts = User::where('role', 'host')->get()->keyBy('email');
        $destinations = Destination::all()->keyBy('name');

        $homestays = [
            ['title' => 'Pine Hill Villa Đà Lạt', 'host' => 'host1@example.com', 'destination' => 'Đà Lạt', 'address' => '12 Hồ Xuân Hương', 'ward' => 'Phường 9', 'province' => 'Lâm Đồng', 'price' => 1400000, 'guests' => 6, 'status' => 'available', 'approved' => true, 'description' => 'Villa ấm cúng giữa rừng thông, phù hợp nhóm bạn và gia đình.'],
            ['title' => 'Cloudy Garden Retreat', 'host' => 'host1@example.com', 'destination' => 'Đà Lạt', 'address' => '88 Ankroet', 'ward' => 'Phường 7', 'province' => 'Lâm Đồng', 'price' => 1750000, 'guests' => 8, 'status' => 'available', 'approved' => true, 'description' => 'Không gian yên tĩnh, sân vườn rộng và có khu BBQ riêng.'],
            ['title' => 'Lantern House Hội An', 'host' => 'host2@example.com', 'destination' => 'Hội An', 'address' => '15 Nguyễn Thái Học', 'ward' => 'Cẩm Châu', 'province' => 'Quảng Nam', 'price' => 1600000, 'guests' => 4, 'status' => 'available', 'approved' => true, 'description' => 'Nhà phố cổ gần trung tâm, decor đèn lồng và ban công thoáng.'],
            ['title' => 'An Bàng Sea Breeze Villa', 'host' => 'host2@example.com', 'destination' => 'Hội An', 'address' => '26 Hai Bà Trưng', 'ward' => 'Cẩm An', 'province' => 'Quảng Nam', 'price' => 2800000, 'guests' => 8, 'status' => 'available', 'approved' => true, 'description' => 'Villa gần biển, có hồ bơi nhỏ và khu sinh hoạt chung rộng rãi.'],
            ['title' => 'Sapa Misty Valley Lodge', 'host' => 'host3@example.com', 'destination' => 'Sa Pa', 'address' => '5 Mường Hoa', 'ward' => 'Sa Pa', 'province' => 'Lào Cai', 'price' => 1900000, 'guests' => 5, 'status' => 'available', 'approved' => true, 'description' => 'View thung lũng đẹp, hợp cặp đôi và nhóm nhỏ yêu thiên nhiên.'],
            ['title' => 'Halong Pearl Homestay', 'host' => 'host3@example.com', 'destination' => 'Vịnh Hạ Long', 'address' => '120 Bãi Cháy', 'ward' => 'Bãi Cháy', 'province' => 'Quảng Ninh', 'price' => 2100000, 'guests' => 6, 'status' => 'available', 'approved' => true, 'description' => 'Homestay hiện đại, gần biển và khu vui chơi trung tâm.'],
            ['title' => 'Sunrise Beachfront Nha Trang', 'host' => 'host4@example.com', 'destination' => 'Nha Trang', 'address' => '22 Trần Phú', 'ward' => 'Lộc Thọ', 'province' => 'Khánh Hòa', 'price' => 2300000, 'guests' => 6, 'status' => 'available', 'approved' => true, 'description' => 'Căn hộ biển cao cấp với cửa kính lớn đón bình minh.'],
            ['title' => 'Dragon Bridge Loft Đà Nẵng', 'host' => 'host4@example.com', 'destination' => 'Đà Nẵng', 'address' => '77 Trần Hưng Đạo', 'ward' => 'An Hải Tây', 'province' => 'Đà Nẵng', 'price' => 1850000, 'guests' => 4, 'status' => 'available', 'approved' => true, 'description' => 'Loft trẻ trung sát sông Hàn, tiện khám phá trung tâm thành phố.'],
            ['title' => 'Moc Chau Green Hills', 'host' => 'host1@example.com', 'destination' => 'Mộc Châu', 'address' => '31 Bản Áng', 'ward' => 'Đông Sang', 'province' => 'Sơn La', 'price' => 1500000, 'guests' => 5, 'status' => 'available', 'approved' => true, 'description' => 'Nhà gỗ giữa đồi chè, sáng săn mây và tối đốt lửa trại.'],
            ['title' => 'Hue Riverside Garden Stay', 'host' => 'host2@example.com', 'destination' => 'Huế', 'address' => '9 Kim Long', 'ward' => 'Kim Long', 'province' => 'Thừa Thiên Huế', 'price' => 1700000, 'guests' => 5, 'status' => 'available', 'approved' => true, 'description' => 'Không gian nhẹ nhàng bên sông, phù hợp nghỉ dưỡng chậm và êm.'],
        ];

        foreach ($homestays as $item) {
            $host = $hosts->get($item['host']);
            $destination = $destinations->get($item['destination']);

            if (!$host || !$destination) {
                continue;
            }

            Homestay::updateOrCreate(
                ['slug' => Str::slug($item['title'])],
                [
                    'owner_id' => $host->id,
                    'destination_id' => $destination->id,
                    'title' => $item['title'],
                    'description' => $item['description'],
                    'address' => $item['address'],
                    'ward' => $item['ward'],
                    'province' => $item['province'],
                    'price_per_night' => $item['price'],
                    'max_guests' => $item['guests'],
                    'status' => $item['status'],
                    'is_approved' => $item['approved'],
                ]
            );
        }
    }
}
