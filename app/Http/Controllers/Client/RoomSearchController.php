<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Favorite;
use App\Models\Homestay;
use Illuminate\Support\Str;

class RoomSearchController extends Controller
{
    private const DEFAULT_HOMESTAY_IMAGE = 'https://placehold.co/600x400';

    public function index()
    {
        $rooms = $this->roomsForView();
        $destinations = Destination::where('is_approved', true)->orderBy('name')->get();
        $favoriteHomestayIds = auth()->check()
            ? Favorite::query()
                ->where('user_id', auth()->id())
                ->pluck('homestay_id')
                ->map(fn($id) => (string) $id)
                ->all()
            : [];

        return view('clients.search', [
            'rooms' => $rooms,
            'destinations' => $destinations,
            'amenityLabels' => self::amenityLabels(),
            'favoriteHomestayIds' => $favoriteHomestayIds,
        ]);
    }

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

    private function roomsForView(): array
    {
        $fromDb = $this->mapHomestaysToCards(
            Homestay::query()
                ->where('is_approved', true)
                ->withCount('reviews')
                ->withAvg('reviews', 'rating')
                ->with(['amenities', 'promotion', 'rooms', 'images' => fn($query) => $query->orderByDesc('is_primary')->orderBy('id')])
                ->latest('created_at')
                ->orderByDesc('reviews_avg_rating')
                ->orderByDesc('reviews_count')
                ->orderBy('price_per_night')
                ->get()
        );

        if ($fromDb !== []) {
            return $fromDb;
        }

        return self::demoRooms();
    }

    private function mapHomestaysToCards($rows): array
    {
        $out = [];
        foreach ($rows as $h) {
            $firstImage = $h->images->first()?->image_url;
            $img = $firstImage
                ? (Str::startsWith($firstImage, ['http://', 'https://']) ? $firstImage : asset('storage/' . ltrim($firstImage, '/')))
                : self::DEFAULT_HOMESTAY_IMAGE;

            $amenities = $h->relationLoaded('amenities')
                ? $h->amenities->pluck('name')->map(fn($name) => self::amenityKeyFromName((string) $name))->filter()->values()->all()
                : [];

            // Xử lý room items mà không cần ROOM_TYPES constant
            $roomItems = [];
            $count = 0;
            foreach ($h->rooms as $room) {
                if ($room->quantity > 0) {
                    $roomItems[] = [
                        'icon' => $this->roomAsset($room->feature_type),
                        'text' => $room->quantity . ' ' . $this->getRoomTypeLabel($room->feature_type),
                    ];
                    $count++;
                    if ($count >= 4) break;
                }
            }

            $out[] = [
                'id' => (string) $h->id,
                'name' => $h->title,
                'location' => $h->province,
                'description' => Str::limit((string) $h->description, 110),
                'destination_id' => (string) ($h->destination_id ?? ''),
                'img' => $img,
                'price_per_night' => (int) $h->price_per_night,
                'discounted_price_per_night' => (int) round($h->discounted_price),
                'price_label' => number_format((float) $h->discounted_price, 0, ',', '.') . 'đ',
                'original_price_label' => number_format((float) $h->price_per_night, 0, ',', '.') . 'đ',
                'has_discount' => $h->active_promotion !== null && (float) $h->discounted_price < (float) $h->price_per_night,
                'rating' => $h->reviews_avg_rating !== null ? (float) $h->reviews_avg_rating : 0.0,
                'reviews_count' => (int) $h->reviews_count,
                'amenities' => array_values(array_unique($amenities)),
                'room_items' => $roomItems,
                'href' => route('homestay.show', ['slug' => $h->slug]),
            ];
        }
        return $out;
    }

    private function getRoomTypeLabel(string $type): string
    {
        $labels = [
            'bedroom' => 'Phòng ngủ',
            'bathroom' => 'Phòng tắm',
            'kitchen' => 'Phòng bếp',
            'living_room' => 'Phòng khách',
            'pool' => 'Hồ bơi',
            'garden' => 'Sân vườn',
            'laundry' => 'Phòng giặt',
            'parking' => 'Bãi đỗ xe',
            'balcony' => 'Ban công',
            'rooftop' => 'Sân thượng',
            'fireplace' => 'Lò sưởi',
        ];
        return $labels[$type] ?? $type;
    }

    private static function demoRooms(): array
    {
        $img = self::DEFAULT_HOMESTAY_IMAGE;

        return [
            [
                'id' => 'd1',
                'name' => 'Pine Valley Retreat',
                'location' => 'Đà Lạt, Lâm Đồng',
                'img' => $img,
                'description' => 'Không gian riêng giữa rừng thông, phù hợp cho chuyến nghỉ dưỡng yên tĩnh cuối tuần.',
                'price_per_night' => 800_000,
                'price_label' => '800.000đ',
                'rating' => 4.9,
                'reviews_count' => 128,
                'amenities' => ['wifi', 'parking', 'garden'],
                'room_items' => [
                    ['icon' => 'bed.svg', 'text' => '1 Phòng ngủ'],
                    ['icon' => 'bath.svg', 'text' => '1 Phòng tắm / Vệ sinh'],
                ],
                'href' => route('homestay.show', ['slug' => 'd1']),
            ],
            [
                'id' => 'd2',
                'name' => 'Lantern House Hội An',
                'location' => 'Hội An, Quảng Nam',
                'img' => $img,
                'description' => 'Căn nhà ấm cúng gần phố cổ, dễ di chuyển và phù hợp nhóm bạn nhỏ.',
                'price_per_night' => 1_200_000,
                'price_label' => '1.200.000đ',
                'rating' => 4.8,
                'reviews_count' => 94,
                'amenities' => ['pool', 'wifi', 'parking'],
                'room_items' => [
                    ['icon' => 'bed.svg', 'text' => '2 Phòng ngủ'],
                    ['icon' => 'pool.svg', 'text' => '1 Bể bơi'],
                ],
                'href' => route('homestay.show', ['slug' => 'd2']),
            ],
        ];
    }

    private function amenityIcon(string $key): string
    {
        return match ($key) {
            'pool' => 'pool.svg',
            'parking' => 'location.svg',
            'garden' => 'leaf.svg',
            default => 'done.svg',
        };
    }

    private function roomAsset(string $type): string
    {
        return match ($type) {
            'bedroom' => 'bed.svg',
            'bathroom' => 'bath.svg',
            'pool' => 'pool.svg',
            'living_room' => 'living-room.svg',
            'kitchen', 'dining_room' => 'kitchen.svg',
            'garden', 'balcony' => 'leaf.svg',
            'garage' => 'location.svg',
            default => 'done.svg',
        };
    }
}
