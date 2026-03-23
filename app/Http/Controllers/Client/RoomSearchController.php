<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Homestay;
use Illuminate\Support\Str;

class RoomSearchController extends Controller
{
    public function index()
    {
        $rooms = $this->roomsForView();

        return view('clients.rooms.search', [
            'rooms' => $rooms,
            'categoryLabels' => self::categoryLabels(),
            'amenityLabels' => self::amenityLabels(),
        ]);
    }

    /** @return array<string, string> */
    public static function categoryLabels(): array
    {
        return [
            'ven-bien' => 'Ven biển',
            'mien-nui' => 'Miền núi',
            'thanh-thi' => 'Thành thị',
            'ho-song' => 'Hồ & sông',
            'sang-trong' => 'Sang trọng',
        ];
    }

    /** @return array<string, string> */
    public static function amenityLabels(): array
    {
        return [
            'wifi' => 'Wi-Fi',
            'parking' => 'Bãi đỗ xe',
            'pool' => 'Hồ bơi',
            'kitchen' => 'Bếp',
            'ac' => 'Điều hòa',
            'washing_machine' => 'Máy giặt',
            'pet_friendly' => 'Cho phép thú cưng',
            'garden' => 'Sân vườn',
            'fireplace' => 'Lò sưởi',
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function roomsForView(): array
    {
        $fromDb = $this->mapHomestaysToCards(
            Homestay::query()
                ->where('status', 'active')
                ->withCount('reviews')
                ->orderByDesc('avg_rating')
                ->limit(24)
                ->get()
        );

        if ($fromDb !== []) {
            return $fromDb;
        }

        return self::demoRooms();
    }

    /**
     * @param \Illuminate\Support\Collection<int, Homestay> $rows
     * @return list<array<string, mixed>>
     */
    private function mapHomestaysToCards($rows): array
    {
        $out = [];
        foreach ($rows as $h) {
            $images = $h->images ?? [];
            $firstImg = is_array($images) && $images !== [] ? (string) reset($images) : null;
            $img = $firstImg
                ? (Str::startsWith($firstImg, ['http://', 'https://']) ? $firstImg : asset('storage/'.$firstImg))
                : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=600&q=80';

            $amenities = [];
            if (is_array($h->amenities)) {
                foreach ($h->amenities as $k => $v) {
                    if (is_string($k) && ($v === true || $v === 1 || $v === '1')) {
                        $amenities[] = $k;
                    } elseif (is_int($k) && is_string($v)) {
                        $amenities[] = $v;
                    }
                }
            }

            $out[] = [
                'id' => (string) $h->id,
                'name' => $h->name,
                'location' => $h->province,
                'img' => $img,
                'price_per_night' => (int) $h->price_per_night,
                'price_label' => number_format((float) $h->price_per_night, 0, ',', '.').'đ',
                'rating' => $h->avg_rating !== null ? (float) $h->avg_rating : 4.5,
                'reviews_count' => (int) $h->reviews_count,
                'category' => self::inferCategoryFromProvince($h->province),
                'amenities' => array_values(array_unique($amenities)),
                'href' => route('homestay.show', ['id' => $h->id]),
            ];
        }

        return $out;
    }

    private static function inferCategoryFromProvince(string $province): string
    {
        $p = Str::lower($province);

        $coastal = ['đà nẵng', 'khánh hòa', 'kiên giang', 'bà rịa', 'vũng tàu', 'quảng nam'];
        foreach ($coastal as $c) {
            if (Str::contains($p, $c)) {
                return 'ven-bien';
            }
        }

        $mountain = ['lào cai', 'sơn la', 'hà giang', 'cao bằng', 'lâm đồng'];
        foreach ($mountain as $c) {
            if (Str::contains($p, $c)) {
                return 'mien-nui';
            }
        }

        $urban = ['hà nội', 'hồ chí minh', 'hải phòng', 'cần thơ'];
        foreach ($urban as $c) {
            if (Str::contains($p, $c)) {
                return 'thanh-thi';
            }
        }

        $water = ['ninh bình', 'huế', 'đồng tháp'];
        foreach ($water as $c) {
            if (Str::contains($p, $c)) {
                return 'ho-song';
            }
        }

        return 'thanh-thi';
    }

    /**
     * @return list<array<string, mixed>>
     */
    private static function demoRooms(): array
    {
        return [
            [
                'id' => 'd1',
                'name' => 'Pine Valley Retreat',
                'location' => 'Đà Lạt, Lâm Đồng',
                'img' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=600&q=80',
                'price_per_night' => 800_000,
                'price_label' => '800.000đ',
                'rating' => 4.9,
                'reviews_count' => 128,
                'category' => 'ho-song',
                'amenities' => ['wifi', 'parking', 'garden', 'ac', 'kitchen'],
                'href' => route('homestay.show', ['id' => 'd1']),
            ],
            [
                'id' => 'd2',
                'name' => 'Lantern House Hội An',
                'location' => 'Hội An, Quảng Nam',
                'img' => 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=600&q=80',
                'price_per_night' => 1_200_000,
                'price_label' => '1.200.000đ',
                'rating' => 4.8,
                'reviews_count' => 94,
                'category' => 'ven-bien',
                'amenities' => ['wifi', 'pool', 'ac', 'kitchen', 'washing_machine'],
                'href' => route('homestay.show', ['id' => 'd2']),
            ],
            [
                'id' => 'd3',
                'name' => 'Cloud Nine Sapa Lodge',
                'location' => 'Sapa, Lào Cai',
                'img' => 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?w=600&q=80',
                'price_per_night' => 650_000,
                'price_label' => '650.000đ',
                'rating' => 4.7,
                'reviews_count' => 76,
                'category' => 'mien-nui',
                'amenities' => ['wifi', 'fireplace', 'parking', 'ac'],
                'href' => route('homestay.show', ['id' => 'd3']),
            ],
            [
                'id' => 'd4',
                'name' => 'Lotus Valley Retreat',
                'location' => 'Ninh Bình',
                'img' => 'https://images.unsplash.com/photo-1613977257365-aaae5a9817ff?w=600&q=80',
                'price_per_night' => 920_000,
                'price_label' => '920.000đ',
                'rating' => 4.9,
                'reviews_count' => 112,
                'category' => 'ho-song',
                'amenities' => ['wifi', 'garden', 'parking', 'kitchen'],
                'href' => route('homestay.show', ['id' => 'd4']),
            ],
            [
                'id' => 'd5',
                'name' => 'Sea Breeze Villa',
                'location' => 'Phú Quốc, Kiên Giang',
                'img' => 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?w=600&q=80',
                'price_per_night' => 1_550_000,
                'price_label' => '1.550.000đ',
                'rating' => 4.8,
                'reviews_count' => 89,
                'category' => 'sang-trong',
                'amenities' => ['wifi', 'pool', 'parking', 'ac', 'kitchen', 'washing_machine'],
                'href' => route('homestay.show', ['id' => 'd5']),
            ],
            [
                'id' => 'd6',
                'name' => 'Pine Hill Cabin',
                'location' => 'Mộc Châu, Sơn La',
                'img' => 'https://images.unsplash.com/photo-1475855581690-80accde3a8a1?w=600&q=80',
                'price_per_night' => 780_000,
                'price_label' => '780.000đ',
                'rating' => 4.7,
                'reviews_count' => 64,
                'category' => 'mien-nui',
                'amenities' => ['wifi', 'fireplace', 'parking', 'pet_friendly'],
                'href' => route('homestay.show', ['id' => 'd6']),
            ],
            [
                'id' => 'd7',
                'name' => 'Skyline Loft Hà Nội',
                'location' => 'Ba Đình, Hà Nội',
                'img' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=600&q=80',
                'price_per_night' => 540_000,
                'price_label' => '540.000đ',
                'rating' => 4.5,
                'reviews_count' => 203,
                'category' => 'thanh-thi',
                'amenities' => ['wifi', 'ac', 'washing_machine', 'kitchen'],
                'href' => route('homestay.show', ['id' => 'd7']),
            ],
            [
                'id' => 'd8',
                'name' => 'District 1 Urban Stay',
                'location' => 'Quận 1, TP.HCM',
                'img' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=600&q=80',
                'price_per_night' => 680_000,
                'price_label' => '680.000đ',
                'rating' => 4.6,
                'reviews_count' => 156,
                'category' => 'thanh-thi',
                'amenities' => ['wifi', 'ac', 'parking'],
                'href' => route('homestay.show', ['id' => 'd8']),
            ],
            [
                'id' => 'd9',
                'name' => 'Nha Trang Blue Horizon',
                'location' => 'Nha Trang, Khánh Hòa',
                'img' => 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=600&q=80',
                'price_per_night' => 1_100_000,
                'price_label' => '1.100.000đ',
                'rating' => 4.85,
                'reviews_count' => 71,
                'category' => 'ven-bien',
                'amenities' => ['wifi', 'pool', 'ac', 'garden'],
                'href' => route('homestay.show', ['id' => 'd9']),
            ],
            [
                'id' => 'd10',
                'name' => 'Premium Đà Nẵng Ocean',
                'location' => 'Ngũ Hành Sơn, Đà Nẵng',
                'img' => 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=600&q=80',
                'price_per_night' => 2_400_000,
                'price_label' => '2.400.000đ',
                'rating' => 4.95,
                'reviews_count' => 42,
                'category' => 'sang-trong',
                'amenities' => ['wifi', 'pool', 'parking', 'ac', 'kitchen', 'washing_machine', 'garden'],
                'href' => route('homestay.show', ['id' => 'd10']),
            ],
        ];
    }
}
