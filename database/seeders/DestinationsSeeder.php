<?php

namespace Database\Seeders;

use App\Models\Destination;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DestinationsSeeder extends Seeder
{
    public function run()
    {
        $destinations = [
            ['name' => 'Đà Lạt', 'description' => 'Thành phố ngàn hoa, khí hậu mát mẻ quanh năm.', 'is_approved' => true],
            ['name' => 'Hội An', 'description' => 'Phố cổ đèn lồng, cổ kính và thơ mộng.', 'is_approved' => true],
            ['name' => 'Vịnh Hạ Long', 'description' => 'Kỳ quan thiên nhiên thế giới.', 'is_approved' => true],
            ['name' => 'Sa Pa', 'description' => 'Thị trấn mờ sương, ruộng bậc thang.', 'is_approved' => true],
            ['name' => 'Đà Nẵng', 'description' => 'Thành phố đáng sống, có cầu Rồng.', 'is_approved' => true],
            ['name' => 'Nha Trang', 'description' => 'Thành phố biển với những bãi tắm đẹp.', 'is_approved' => true],
            ['name' => 'Phú Quốc', 'description' => 'Đảo ngọc hoang sơ.', 'is_approved' => true],
            ['name' => 'Mộc Châu', 'description' => 'Cao nguyên xanh mướt, đồi chè.', 'is_approved' => true],
            ['name' => 'Huế', 'description' => 'Cố đô với sông Hương thơ mộng.', 'is_approved' => true],
            ['name' => 'Cần Thơ', 'description' => 'Trung tâm miền Tây, chợ nổi Cái Răng.', 'is_approved' => true],
        ];

        foreach ($destinations as $dest) {
            Destination::updateOrCreate(
                ['slug' => Str::slug($dest['name'])],
                [
                    'name' => $dest['name'],
                    'description' => $dest['description'],
                    'image' => null,
                    'homestay_count' => 0,
                    'is_approved' => $dest['is_approved'],
                    'created_by' => null, // không có admin, để null
                ]
            );
        }
    }
}