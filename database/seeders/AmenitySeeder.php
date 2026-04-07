<?php

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Seeder;

class AmenitySeeder extends Seeder
{
    public function run(): void
    {
        $amenities = [
            // Tiện nghi cơ bản
            'Wifi miễn phí',
            'Điều hòa',
            'Máy nước nóng',
            'Tivi',
            'Tủ lạnh',
            
            // Phòng bếp
            'Bếp nấu ăn',
            'Lò vi sóng',
            'Máy pha cà phê',
            'Bàn ăn',
            'Dụng cụ nấu ăn',
            
            // Phòng ngủ & giặt giũ
            'Máy giặt',
            'Máy sấy tóc',
            'Khăn tắm',
            
            // Giải trí
            'Truyền hình cáp',
            'Loa Bluetooth',
            'Sách/Tạp chí',
            'Trò chơi board game',
            
            // Ngoài trời
            'Hồ bơi',
            'Sân vườn',
            'BBQ/Nướng ngoài trời',
            'Ban công/Sân thượng',
            'Ghế tắm nắng',
            
            // An ninh & An toàn
            'Khóa cửa thông minh',
            'Báo cháy',
            'Bình chữa cháy',
            
            // Dịch vụ
            'Bãi đỗ xe miễn phí',
            'Dọn phòng hàng ngày',
            'Bữa sáng miễn phí',
            'Cho phép thú cưng',
            
            // Đặc biệt
            'View biển',
            'View núi',
            'Lò sưởi',
            'Phòng gym',
            'Spa/Massage',
        ];

        foreach ($amenities as $name) {
            Amenity::firstOrCreate(['name' => $name]);
        }
    }
}