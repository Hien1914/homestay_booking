<?php

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Seeder;

class AmenitiesSeeder extends Seeder
{
    public function run()
    {
        $amenities = [
            'Wi-Fi', 'Điều hòa', 'Bãi đỗ xe', 'Hồ bơi', 'Bếp riêng',
            'Máy giặt', 'Cho phép thú cưng', 'Sân vườn', 'Lò sưởi',
            'Nhà hàng', 'Dịch vụ phòng', 'Xe đưa đón', 'Bữa sáng', 'Ban công',
            'Phòng tắm riêng', 'Tủ lạnh', 'Tivi', 'Máy sấy tóc'
        ];

        foreach ($amenities as $name) {
            Amenity::updateOrCreate(
                ['name' => $name],
                ['is_approved' => true, 'created_by' => null]
            );
        }
    }
}