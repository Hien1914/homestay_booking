<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Homestay;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HomestayDetailController extends Controller
{
    public function show(Request $request, string $id)
    {
        $homestay = $this->resolveHomestay($id);

        if (! $homestay) {
            abort(404);
        }

        $categoryLabels = RoomSearchController::categoryLabels();
        $breadcrumbs = $this->buildBreadcrumbs($homestay, $categoryLabels);

        return view('clients.homestays.show', [
            'homestay' => $homestay,
            'amenityLabels' => RoomSearchController::amenityLabels(),
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    public function booking(Request $request, string $id)
    {
        $homestay = $this->resolveHomestay($id);

        if (! $homestay) {
            abort(404);
        }

        $categoryLabels = RoomSearchController::categoryLabels();
        $breadcrumbs = $this->buildBreadcrumbs($homestay, $categoryLabels);
        $breadcrumbs[] = ['label' => 'Đặt phòng'];

        return view('clients.homestays.booking', [
            'homestay' => $homestay,
            'amenityLabels' => RoomSearchController::amenityLabels(),
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    /**
     * @param  array<string, mixed>  $homestay
     * @param  array<string, string>  $categoryLabels
     * @return list<array{label: string, url?: string}>
     */
    private function buildBreadcrumbs(array $homestay, array $categoryLabels): array
    {
        $crumbs = [
            ['label' => 'Trang chủ', 'url' => route('home')],
            ['label' => 'Chỗ nghỉ', 'url' => route('rooms.search')],
        ];
        $category = $homestay['category'] ?? null;
        if ($category && isset($categoryLabels[$category])) {
            $crumbs[] = [
                'label' => $categoryLabels[$category],
                'url' => route('rooms.search').'?category[]='.urlencode($category),
            ];
        }
        $province = $homestay['province'] ?? '';
        if ($province) {
            $crumbs[] = [
                'label' => $province,
                'url' => route('rooms.search').'?province='.urlencode($province),
            ];
        }
        $crumbs[] = ['label' => $homestay['name'] ?? 'Chi tiết phòng'];

        return $crumbs;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function resolveHomestay(string $id): ?array
    {
        if (is_numeric($id)) {
            $h = Homestay::query()
                ->where('status', 'active')
                ->withCount('reviews')
                ->find($id);

            return $h ? $this->mapHomestayToDetail($h) : null;
        }

        return self::demoDetailById($id);
    }

    /**
     * @return array<string, mixed>
     */
    private function mapHomestayToDetail(Homestay $h): array
    {
        $images = $h->images ?? [];
        if (! is_array($images) || $images === []) {
            $images = ['https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&q=80'];
        }
        $images = array_map(function ($url) {
            return Str::startsWith($url, ['http://', 'https://'])
                ? $url
                : asset('storage/'.$url);
        }, array_values($images));

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

        $reviews = $h->reviews()
            ->with('user')
            ->latest()
            ->limit(20)
            ->get()
            ->map(fn ($r) => [
                'user_name' => $r->user?->name ?? 'Khách',
                'rating' => (int) $r->rating,
                'comment' => $r->comment,
                'date' => $r->created_at->diffForHumans(),
                'admin_reply' => $r->admin_reply,
            ])
            ->toArray();

        $province = (string) $h->province;

        return [
            'id' => (string) $h->id,
            'name' => $h->name,
            'category' => $this->inferCategoryFromProvince($province),
            'description' => $h->description,
            'address' => $h->address,
            'province' => $province,
            'images' => $images,
            'price_per_night' => (int) $h->price_per_night,
            'price_label' => number_format((float) $h->price_per_night, 0, ',', '.').'đ',
            'max_guests' => (int) $h->max_guests,
            'num_bedrooms' => (int) $h->num_bedrooms,
            'num_beds' => (int) $h->num_beds,
            'num_bathrooms' => (int) $h->num_bathrooms,
            'check_in_time' => substr((string) $h->check_in_time, 0, 5),
            'check_out_time' => substr((string) $h->check_out_time, 0, 5),
            'cancellation_policy' => $h->cancellation_policy,
            'amenities' => array_values(array_unique($amenities)),
            'avg_rating' => $h->avg_rating !== null ? (float) $h->avg_rating : null,
            'reviews_count' => (int) $h->reviews_count,
            'reviews' => $reviews,
        ];
    }

    private function inferCategoryFromProvince(string $province): ?string
    {
        $map = [
            'đà lạt' => 'ho-song', 'lam đồng' => 'ho-song',
            'sapa' => 'mien-nui', 'lào cai' => 'mien-nui', 'mộc châu' => 'mien-nui', 'sơn la' => 'mien-nui', 'hà giang' => 'mien-nui',
            'hội an' => 'ven-bien', 'quảng nam' => 'ven-bien',
            'phú quốc' => 'ven-bien', 'kiên giang' => 'ven-bien',
            'nha trang' => 'ven-bien', 'khánh hòa' => 'ven-bien',
            'đà nẵng' => 'ven-bien', 'ngũ hành sơn' => 'ven-bien',
            'ninh bình' => 'ho-song', 'hoa lư' => 'ho-song',
            'hà nội' => 'thanh-thi', 'ba đình' => 'thanh-thi',
            'tp.hcm' => 'thanh-thi', 'quận 1' => 'thanh-thi', 'sài gòn' => 'thanh-thi',
        ];
        $lower = mb_strtolower($province);
        foreach ($map as $key => $cat) {
            if (str_contains($lower, $key)) {
                return $cat;
            }
        }

        return null;
    }

    /** @return array<string, mixed>|null */
    public static function getDemoHomestay(string $id): ?array
    {
        return self::demoDetailById($id);
    }

    /**
     * @return array<string, mixed>|null
     */
    private static function demoDetailById(string $id): ?array
    {
        $demos = self::demoDetails();

        return $demos[$id] ?? null;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private static function demoDetails(): array
    {
        $base = [
            'd1' => [
                'name' => 'Pine Valley Retreat',
                'province' => 'Đà Lạt, Lâm Đồng',
                'category' => 'ho-song',
                'images' => [
                    'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&q=80',
                    'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=800&q=80',
                    'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?w=800&q=80',
                ],
                'price_label' => '800.000đ',
                'price_per_night' => 800_000,
                'description' => 'Homestay nằm giữa thung lũng thông xanh mát, view hồ và núi. Không gian yên tĩnh, phù hợp cặp đôi và gia đình nhỏ. Có sân vườn, bếp dùng chung, wifi ổn định.',
                'address' => 'Đường Hồ Tuyền Lâm, Phường 3, Đà Lạt, Lâm Đồng',
                'max_guests' => 4,
                'num_bedrooms' => 2,
                'num_beds' => 2,
                'num_bathrooms' => 2,
                'check_in_time' => '14:00',
                'check_out_time' => '12:00',
                'cancellation_policy' => 'flexible',
                'amenities' => ['wifi', 'parking', 'garden', 'ac', 'kitchen'],
                'avg_rating' => 4.9,
                'reviews_count' => 128,
                'reviews' => [
                    ['user_name' => 'Linh Nguyễn', 'rating' => 5, 'comment' => 'Phòng sạch, view đẹp và quản trị viên phản hồi rất nhanh. Mình ở 2 đêm mà thấy cực kỳ thoải mái.', 'date' => '2 ngày trước', 'admin_reply' => null],
                    ['user_name' => 'Minh Trần', 'rating' => 5, 'comment' => 'Vị trí thuận tiện, đi lại dễ dàng. Tiện ích đầy đủ đúng như mô tả.', 'date' => '1 tuần trước', 'admin_reply' => 'Cảm ơn bạn đã chọn lưu trú. Hẹn gặp lại!'],
                    ['user_name' => 'Anh Anh', 'rating' => 5, 'comment' => 'Không khí trong phòng dễ chịu, giường ngủ thoải mái. Đặc biệt thích khu vực sân vườn buổi sáng.', 'date' => '2 tuần trước', 'admin_reply' => null],
                ],
            ],
            'd2' => [
                'name' => 'Lantern House Hội An',
                'province' => 'Hội An, Quảng Nam',
                'category' => 'ven-bien',
                'images' => [
                    'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=800&q=80',
                    'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&q=80',
                ],
                'price_label' => '1.200.000đ',
                'price_per_night' => 1_200_000,
                'description' => 'Nhà phố cổ gần phố Hội, có hồ bơi riêng và vườn nhỏ. Nội thất gỗ ấm cúng, cách chợ Hội An vài phút đi bộ.',
                'address' => 'Cẩm Châu, Hội An, Quảng Nam',
                'max_guests' => 6,
                'num_bedrooms' => 3,
                'num_beds' => 4,
                'num_bathrooms' => 2,
                'check_in_time' => '14:00',
                'check_out_time' => '11:00',
                'cancellation_policy' => 'moderate',
                'amenities' => ['wifi', 'pool', 'ac', 'kitchen', 'washing_machine'],
                'avg_rating' => 4.8,
                'reviews_count' => 94,
                'reviews' => [
                    ['user_name' => 'Ngọc Nhi', 'rating' => 5, 'comment' => 'Đúng kiểu "chill" luôn. Nhà có hồ bơi sạch, chụp ảnh đẹp.', 'date' => '3 ngày trước', 'admin_reply' => null],
                    ['user_name' => 'Khánh Kỳ', 'rating' => 5, 'comment' => 'Trang trí dễ thương, phòng đủ đồ dùng cơ bản.', 'date' => '5 ngày trước', 'admin_reply' => null],
                ],
            ],
            'd3' => [
                'name' => 'Cloud Nine Sapa Lodge',
                'province' => 'Sapa, Lào Cai',
                'category' => 'mien-nui',
                'images' => [
                    'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?w=800&q=80',
                ],
                'price_label' => '650.000đ',
                'price_per_night' => 650_000,
                'description' => 'Lodge view núi Fansipan, có lò sưởi. Phù hợp trải nghiệm Sapa mùa lạnh.',
                'address' => 'Sa Pả, Sapa, Lào Cai',
                'max_guests' => 2,
                'num_bedrooms' => 1,
                'num_beds' => 1,
                'num_bathrooms' => 1,
                'check_in_time' => '14:00',
                'check_out_time' => '12:00',
                'cancellation_policy' => 'strict',
                'amenities' => ['wifi', 'fireplace', 'parking', 'ac'],
                'avg_rating' => 4.7,
                'reviews_count' => 76,
                'reviews' => [
                    ['user_name' => 'Hương Phạm', 'rating' => 5, 'comment' => 'Sapa se lạnh nên mình rất thích lò sưởi. Phòng ấm áp, sạch sẽ.', 'date' => '1 ngày trước', 'admin_reply' => null],
                ],
            ],
        ];

        $extras = [
            'd4' => ['name' => 'Lotus Valley Retreat', 'province' => 'Ninh Bình', 'category' => 'ho-song', 'images' => ['https://images.unsplash.com/photo-1613977257365-aaae5a9817ff?w=800&q=80'], 'price_label' => '920.000đ', 'price_per_night' => 920_000, 'description' => 'Homestay gần Tràng An, view núi. Không gian yên tĩnh.', 'address' => 'Ninh Hải, Hoa Lư, Ninh Bình', 'max_guests' => 4, 'num_bedrooms' => 2, 'num_beds' => 2, 'num_bathrooms' => 2, 'check_in_time' => '14:00', 'check_out_time' => '12:00', 'cancellation_policy' => 'moderate', 'amenities' => ['wifi', 'garden', 'parking', 'kitchen'], 'avg_rating' => 4.9, 'reviews_count' => 112, 'reviews' => []],
            'd5' => ['name' => 'Sea Breeze Villa', 'province' => 'Phú Quốc, Kiên Giang', 'category' => 'sang-trong', 'images' => ['https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?w=800&q=80'], 'price_label' => '1.550.000đ', 'price_per_night' => 1_550_000, 'description' => 'Biệt thự hồ bơi riêng view biển.', 'address' => 'Bãi Trường, Phú Quốc', 'max_guests' => 8, 'num_bedrooms' => 3, 'num_beds' => 4, 'num_bathrooms' => 3, 'check_in_time' => '15:00', 'check_out_time' => '11:00', 'cancellation_policy' => 'moderate', 'amenities' => ['wifi', 'pool', 'parking', 'ac', 'kitchen', 'washing_machine'], 'avg_rating' => 4.8, 'reviews_count' => 89, 'reviews' => []],
            'd6' => ['name' => 'Pine Hill Cabin', 'province' => 'Mộc Châu, Sơn La', 'category' => 'mien-nui', 'images' => ['https://images.unsplash.com/photo-1475855581690-80accde3a8a1?w=800&q=80'], 'price_label' => '780.000đ', 'price_per_night' => 780_000, 'description' => 'Cabin nhỏ trên đồi chè, pet friendly.', 'address' => 'Mộc Châu, Sơn La', 'max_guests' => 2, 'num_bedrooms' => 1, 'num_beds' => 1, 'num_bathrooms' => 1, 'check_in_time' => '14:00', 'check_out_time' => '12:00', 'cancellation_policy' => 'flexible', 'amenities' => ['wifi', 'fireplace', 'parking', 'pet_friendly'], 'avg_rating' => 4.7, 'reviews_count' => 64, 'reviews' => []],
            'd7' => ['name' => 'Skyline Loft Hà Nội', 'province' => 'Ba Đình, Hà Nội', 'category' => 'thanh-thi', 'images' => ['https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800&q=80'], 'price_label' => '540.000đ', 'price_per_night' => 540_000, 'description' => 'Căn hộ hiện đại trung tâm Hà Nội.', 'address' => 'Ba Đình, Hà Nội', 'max_guests' => 4, 'num_bedrooms' => 1, 'num_beds' => 2, 'num_bathrooms' => 1, 'check_in_time' => '14:00', 'check_out_time' => '12:00', 'cancellation_policy' => 'moderate', 'amenities' => ['wifi', 'ac', 'washing_machine', 'kitchen'], 'avg_rating' => 4.5, 'reviews_count' => 203, 'reviews' => []],
            'd8' => ['name' => 'District 1 Urban Stay', 'province' => 'Quận 1, TP.HCM', 'category' => 'thanh-thi', 'images' => ['https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800&q=80'], 'price_label' => '680.000đ', 'price_per_night' => 680_000, 'description' => 'Căn hộ gần Bùi Viện, thuận tiện khám phá Sài Gòn.', 'address' => 'Quận 1, TP.HCM', 'max_guests' => 3, 'num_bedrooms' => 1, 'num_beds' => 1, 'num_bathrooms' => 1, 'check_in_time' => '14:00', 'check_out_time' => '12:00', 'cancellation_policy' => 'flexible', 'amenities' => ['wifi', 'ac', 'parking'], 'avg_rating' => 4.6, 'reviews_count' => 156, 'reviews' => []],
            'd9' => ['name' => 'Nha Trang Blue Horizon', 'province' => 'Nha Trang, Khánh Hòa', 'category' => 'ven-bien', 'images' => ['https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=800&q=80'], 'price_label' => '1.100.000đ', 'price_per_night' => 1_100_000, 'description' => 'Homestay view biển Nha Trang.', 'address' => 'Nha Trang, Khánh Hòa', 'max_guests' => 6, 'num_bedrooms' => 2, 'num_beds' => 3, 'num_bathrooms' => 2, 'check_in_time' => '14:00', 'check_out_time' => '12:00', 'cancellation_policy' => 'moderate', 'amenities' => ['wifi', 'pool', 'ac', 'garden'], 'avg_rating' => 4.85, 'reviews_count' => 71, 'reviews' => []],
            'd10' => ['name' => 'Premium Đà Nẵng Ocean', 'province' => 'Ngũ Hành Sơn, Đà Nẵng', 'category' => 'sang-trong', 'images' => ['https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=800&q=80'], 'price_label' => '2.400.000đ', 'price_per_night' => 2_400_000, 'description' => 'Biệt thự cao cấp view biển Đà Nẵng.', 'address' => 'Ngũ Hành Sơn, Đà Nẵng', 'max_guests' => 10, 'num_bedrooms' => 4, 'num_beds' => 5, 'num_bathrooms' => 4, 'check_in_time' => '15:00', 'check_out_time' => '11:00', 'cancellation_policy' => 'strict', 'amenities' => ['wifi', 'pool', 'parking', 'ac', 'kitchen', 'washing_machine', 'garden'], 'avg_rating' => 4.95, 'reviews_count' => 42, 'reviews' => []],
        ];

        $out = [];
        foreach (array_merge($base, $extras) as $k => $v) {
            $out[$k] = array_merge(['id' => $k], $v);
        }

        return $out;
    }
}
