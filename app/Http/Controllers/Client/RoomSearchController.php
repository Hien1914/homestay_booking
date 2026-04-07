<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Homestay;
use Illuminate\Support\Str;

class RoomSearchController extends Controller
{
    private const DEFAULT_HOMESTAY_IMAGE = 'https://placehold.co/600x400';

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

    public static function amenityKeyFromName(string $name): ?string
    {
        $normalized = Str::of($name)->lower()->ascii()->replaceMatches('/[^a-z0-9]+/', ' ')->trim()->value();

        return match ($normalized) {
            'wi fi', 'wifi', 'internet' => 'wifi',
            'bai do xe', 'do xe', 'parking' => 'parking',
            'ho boi', 'be boi', 'pool' => 'pool',
            'bep', 'bep rieng', 'kitchen' => 'kitchen',
            'dieu hoa', 'may lanh', 'ac' => 'ac',
            'may giat', 'washing machine' => 'washing_machine',
            'cho phep thu cung', 'thu cung', 'pet friendly' => 'pet_friendly',
            'san vuon', 'vuon', 'garden' => 'garden',
            'lo suoi', 'fireplace' => 'fireplace',
            default => null,
        };
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function roomsForView(): array
    {
        $fromDb = $this->mapHomestaysToCards(
            Homestay::query()
                ->where('status', 'available')
                ->withCount('reviews')
                ->withAvg('reviews', 'rating')
                ->with(['amenities', 'images' => fn ($query) => $query->orderByDesc('is_primary')->orderBy('id')])
                ->orderByDesc('reviews_avg_rating')
                ->orderByDesc('reviews_count')
                ->orderBy('price_per_night')
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
            $firstImage = $h->images->first()?->image_url;
            $img = $firstImage
                ? (Str::startsWith($firstImage, ['http://', 'https://']) ? $firstImage : asset('storage/' . ltrim($firstImage, '/')))
                : self::DEFAULT_HOMESTAY_IMAGE;

            $amenities = $h->relationLoaded('amenities')
                ? $h->amenities
                    ->pluck('name')
                    ->map(fn ($name) => self::amenityKeyFromName((string) $name))
                    ->filter()
                    ->values()
                    ->all()
                : [];

            $out[] = [
                'id' => (string) $h->id,
                'name' => $h->title,
                'location' => $h->province,
                'img' => $img,
                'price_per_night' => (int) $h->price_per_night,
                'price_label' => number_format((float) $h->price_per_night, 0, ',', '.') . 'đ',
                'rating' => $h->reviews_avg_rating !== null ? (float) $h->reviews_avg_rating : 4.5,
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

        $coastal = ['đà nẵng', 'khánh hòa', 'kiên giang', 'bà rịa', 'vũng tàu', 'quảng nam', 'quảng ninh', 'bình thuận'];
        foreach ($coastal as $c) {
            if (Str::contains($p, $c)) {
                return 'ven-bien';
            }
        }

        $mountain = ['lào cai', 'sơn la', 'hà giang', 'cao bằng', 'lâm đồng', 'vĩnh phúc', 'gia lai'];
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

        $water = ['ninh bình', 'huế', 'đồng tháp', 'an giang', 'bến tre'];
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
        $img = self::DEFAULT_HOMESTAY_IMAGE;

        return [
            [
                'id' => 'd1',
                'name' => 'Pine Valley Retreat',
                'location' => 'Đà Lạt, Lâm Đồng',
                'img' => $img,
                'price_per_night' => 800_000,
                'price_label' => '800.000đ',
                'rating' => 4.9,
                'reviews_count' => 128,
                'category' => 'ho-song',
                'amenities' => ['wifi', 'parking', 'garden'],
                'href' => route('homestay.show', ['id' => 'd1']),
            ],
            [
                'id' => 'd2',
                'name' => 'Lantern House Hội An',
                'location' => 'Hội An, Quảng Nam',
                'img' => $img,
                'price_per_night' => 1_200_000,
                'price_label' => '1.200.000đ',
                'rating' => 4.8,
                'reviews_count' => 94,
                'category' => 'ven-bien',
                'amenities' => ['pool', 'wifi', 'parking'],
                'href' => route('homestay.show', ['id' => 'd2']),
            ],
            [
                'id' => 'd3',
                'name' => 'Cloud Nine Sapa Lodge',
                'location' => 'Sa Pa, Lào Cai',
                'img' => $img,
                'price_per_night' => 650_000,
                'price_label' => '650.000đ',
                'rating' => 4.7,
                'reviews_count' => 76,
                'category' => 'mien-nui',
                'amenities' => ['wifi', 'garden', 'parking'],
                'href' => route('homestay.show', ['id' => 'd3']),
            ],
        ];
    }
}
