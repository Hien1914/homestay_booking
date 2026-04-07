<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Homestay;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HomestayDetailController extends Controller
{
    private const DEFAULT_HOMESTAY_IMAGE = 'https://placehold.co/600x400';

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
                'url' => route('rooms.search') . '?category[]=' . urlencode($category),
            ];
        }

        $province = $homestay['province'] ?? '';
        if ($province) {
            $crumbs[] = [
                'label' => $province,
                'url' => route('rooms.search') . '?province=' . urlencode($province),
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
                ->where('status', 'available')
                ->withCount('reviews')
                ->withAvg('reviews', 'rating')
                ->with(['amenities', 'images' => fn ($query) => $query->orderByDesc('is_primary')->orderBy('id')])
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
        $images = $h->relationLoaded('images')
            ? $h->images
                ->pluck('image_url')
                ->filter()
                ->map(fn ($url) => Str::startsWith($url, ['http://', 'https://']) ? $url : asset('storage/' . ltrim($url, '/')))
                ->values()
                ->all()
            : [];

        if ($images === []) {
            $images = [self::DEFAULT_HOMESTAY_IMAGE];
        }

        $amenities = $h->relationLoaded('amenities')
            ? $h->amenities
                ->pluck('name')
                ->map(fn ($name) => RoomSearchController::amenityKeyFromName((string) $name))
                ->filter()
                ->values()
                ->all()
            : [];

        $reviews = $h->reviews()
            ->with('reviewer:id,full_name')
            ->latest('created_at')
            ->limit(20)
            ->get()
            ->map(fn ($r) => [
                'user_name' => $r->reviewer?->full_name ?? 'Khách',
                'rating' => (int) $r->rating,
                'comment' => $r->comment,
                'date' => optional($r->created_at)->diffForHumans(),
                'admin_reply' => null,
            ])
            ->toArray();

        $province = (string) $h->province;

        return [
            'id' => (string) $h->id,
            'name' => $h->title,
            'category' => $this->inferCategoryFromProvince($province),
            'description' => $h->description,
            'address' => $h->address,
            'province' => $province,
            'images' => $images,
            'price_per_night' => (int) $h->price_per_night,
            'price_label' => number_format((float) $h->price_per_night, 0, ',', '.') . 'đ',
            'max_guests' => (int) $h->max_guests,
            'num_bedrooms' => 1,
            'num_beds' => max(1, (int) ceil($h->max_guests / 2)),
            'num_bathrooms' => 1,
            'check_in_time' => '14:00',
            'check_out_time' => '12:00',
            'cancellation_policy' => 'moderate',
            'amenities' => array_values(array_unique($amenities)),
            'avg_rating' => $h->reviews_avg_rating !== null ? (float) $h->reviews_avg_rating : null,
            'reviews_count' => (int) $h->reviews_count,
            'reviews' => $reviews,
        ];
    }

    private function inferCategoryFromProvince(string $province): ?string
    {
        return $this->inferCategoryKey($province);
    }

    private function inferCategoryKey(string $province): string
    {
        $lower = Str::lower($province);

        foreach (['đà nẵng', 'kiên giang', 'quảng nam', 'quảng ninh', 'bình định', 'bình thuận'] as $item) {
            if (str_contains($lower, $item)) {
                return 'ven-bien';
            }
        }

        foreach (['lâm đồng', 'lào cai', 'sơn la', 'vĩnh phúc', 'gia lai'] as $item) {
            if (str_contains($lower, $item)) {
                return 'mien-nui';
            }
        }

        foreach (['ninh bình', 'an giang', 'bến tre', 'cần thơ', 'huế'] as $item) {
            if (str_contains($lower, $item)) {
                return 'ho-song';
            }
        }

        return 'thanh-thi';
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
        $image = self::DEFAULT_HOMESTAY_IMAGE;

        $items = [
            'd1' => [
                'name' => 'Pine Valley Retreat',
                'province' => 'Đà Lạt, Lâm Đồng',
                'category' => 'mien-nui',
                'images' => [$image],
                'price_label' => '800.000đ',
                'price_per_night' => 800000,
                'description' => 'Homestay giữa đồi thông với không khí mát mẻ và không gian riêng tư.',
                'address' => 'Đường Hồ Tuyền Lâm, Đà Lạt',
                'max_guests' => 4,
                'num_bedrooms' => 2,
                'num_beds' => 2,
                'num_bathrooms' => 2,
                'check_in_time' => '14:00',
                'check_out_time' => '12:00',
                'cancellation_policy' => 'flexible',
                'amenities' => ['wifi', 'parking', 'garden'],
                'avg_rating' => 4.9,
                'reviews_count' => 128,
                'reviews' => [],
            ],
            'd2' => [
                'name' => 'Lantern House Hội An',
                'province' => 'Hội An, Quảng Nam',
                'category' => 'ven-bien',
                'images' => [$image],
                'price_label' => '1.200.000đ',
                'price_per_night' => 1200000,
                'description' => 'Không gian ấm cúng gần phố cổ, phù hợp nhóm bạn và gia đình.',
                'address' => 'Cẩm Châu, Hội An',
                'max_guests' => 6,
                'num_bedrooms' => 3,
                'num_beds' => 4,
                'num_bathrooms' => 2,
                'check_in_time' => '14:00',
                'check_out_time' => '11:00',
                'cancellation_policy' => 'moderate',
                'amenities' => ['wifi', 'pool', 'parking'],
                'avg_rating' => 4.8,
                'reviews_count' => 94,
                'reviews' => [],
            ],
        ];

        foreach ($items as $key => $value) {
            $items[$key] = array_merge(['id' => $key], $value);
        }

        return $items;
    }
}
