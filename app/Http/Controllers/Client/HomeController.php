<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Destination;
use App\Models\Homestay;
use App\Models\Review;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    private const DEFAULT_HOMESTAY_IMAGE = 'https://placehold.co/600x400';

    public function __invoke()
    {
        $featuredHomestays = Homestay::query()
            ->where('status', 'available')
            ->with(['images' => fn ($query) => $query->orderByDesc('is_primary')->orderBy('id'), 'amenities'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->orderByDesc('reviews_avg_rating')
            ->orderByDesc('reviews_count')
            ->orderBy('price_per_night')
            ->limit(6)
            ->get();

        $featuredDestinations = Destination::query()
            ->withCount('homestays')
            ->orderByDesc('homestay_count')
            ->get()
            ->map(fn (Destination $d) => [
                'name'  => $d->name,
                'slug'  => $d->slug,
                'image' => $d->image
                    ? asset('storage/' . ltrim($d->image, '/'))
                    : self::DEFAULT_HOMESTAY_IMAGE,
            ]);

        $popularAmenities = Amenity::query()
            ->select('amenities.id', 'amenities.name', 'amenities.created_at')
            ->selectRaw('COUNT(homestay_amenities.homestay_id) as usage_count')
            ->leftJoin('homestay_amenities', 'homestay_amenities.amenity_id', '=', 'amenities.id')
            ->groupBy('amenities.id', 'amenities.name', 'amenities.created_at')
            ->orderByDesc('usage_count')
            ->limit(4)
            ->get()
            ->map(fn (Amenity $amenity) => [
                'name' => $amenity->name,
                'icon' => $this->amenityEmoji($amenity->name),
                'description' => $this->amenityDescription($amenity->name),
                'usage_count' => (int) $amenity->usage_count,
            ]);

        $testimonials = Review::query()
            ->with(['reviewer:id,full_name', 'homestay:id,title,province'])
            ->latest('created_at')
            ->limit(6)
            ->get()
            ->map(function (Review $review): array {
                return [
                    'avatar' => Str::upper(Str::substr($review->reviewer?->full_name ?? 'K', 0, 1)),
                    'name' => $review->reviewer?->full_name ?? 'Khach luu tru',
                    'role' => $review->homestay?->province ?? 'Khach hang NestAway',
                    'comment' => $review->comment ?: 'Trai nghiem tot va dich vu dung nhu mong doi.',
                    'rating' => (int) $review->rating,
                ];
            });

        $stats = [
            'homestays' => Homestay::query()->where('status', 'available')->count(),
            'provinces' => Homestay::query()->where('status', 'available')->distinct('province')->count('province'),
            'reviews' => Review::query()->count(),
            'avg_rating' => round((float) (Review::query()->avg('rating') ?? 0), 1),
        ];

        return view('clients.home', [
            'featuredDestinations' => $featuredDestinations,
            'featuredHomestays' => $featuredHomestays->map(fn (Homestay $homestay) => $this->mapFeaturedHomestay($homestay)),
            'popularAmenities' => $popularAmenities,
            'testimonials' => $testimonials,
            'stats' => $stats,
        ]);
    }

    private function mapFeaturedHomestay(Homestay $homestay): array
    {
        $amenities = $homestay->amenities->take(3)->map(function (Amenity $amenity): array {
            return [
                'icon' => $this->amenityAsset($amenity->name),
                'text' => $amenity->name,
            ];
        })->values()->all();

        return [
            'id' => (string) $homestay->id,
            'img' => $this->homestayImage($homestay),
            'badge' => ['class' => 'bg-primary', 'text' => 'De xuat'],
            'location' => $homestay->province,
            'name' => $homestay->title,
            'description' => Str::limit((string) $homestay->description, 110),
            'amenities' => $amenities,
            'price' => number_format((float) $homestay->price_per_night, 0, ',', '.') . 'd',
            'rating' => number_format((float) ($homestay->reviews_avg_rating ?? 4.8), 1),
            'reviews' => '(' . $homestay->reviews_count . ')',
        ];
    }

    private function homestayImage(?Homestay $homestay): string
    {
        $image = $homestay?->images->first();

        if ($image?->image_url) {
            return Str::startsWith($image->image_url, ['http://', 'https://'])
                ? $image->image_url
                : asset('storage/' . ltrim($image->image_url, '/'));
        }

        return self::DEFAULT_HOMESTAY_IMAGE;
    }

    private function destinationLabel(string $province): string
    {
        $labels = [
            'Lam Dong' => 'Tron pho len rung thong',
            'Kien Giang' => 'Nghi duong gan bien dao',
            'Ha Noi' => 'City break that tinh gon',
            'Quang Nam' => 'O giua pho co va song nuoc',
            'Quang Ninh' => 'View vinh va ban cong lon',
            'Can Tho' => 'Nhip song mien Tay thu tha',
        ];

        return $labels[$province] ?? 'Diem den dang duoc tim nhieu';
    }

    private function destinationType(string $province): string
    {
        $coastal = ['Kien Giang', 'Quang Nam', 'Quang Ninh', 'Binh Dinh', 'Ba Ria - Vung Tau', 'Binh Thuan'];
        $mountain = ['Lam Dong', 'Lao Cai', 'Son La', 'Vinh Phuc', 'Gia Lai'];
        $urban = ['Ha Noi', 'Can Tho'];

        if (in_array($province, $coastal, true)) {
            return 'Bien va nghi duong';
        }

        if (in_array($province, $mountain, true)) {
            return 'Doi nui va khi hau mat';
        }

        if (in_array($province, $urban, true)) {
            return 'Pho thi va trai nghiem dia phuong';
        }

        return 'Diem den da trai nghiem';
    }

    private function amenityEmoji(string $name): string
    {
        return match (RoomSearchController::amenityKeyFromName($name)) {
            'wifi' => 'Wi-Fi',
            'pool' => 'Ho boi',
            'parking' => 'Do xe',
            'kitchen' => 'Bep',
            'ac' => 'May lanh',
            'washing_machine' => 'May giat',
            'pet_friendly' => 'Thu cung',
            'garden' => 'San vuon',
            'fireplace' => 'Lo suoi',
            default => 'Tien ich',
        };
    }

    private function amenityAsset(string $name): string
    {
        return match (RoomSearchController::amenityKeyFromName($name)) {
            'pool' => 'pool.svg',
            'parking' => 'location.svg',
            'garden' => 'leaf.svg',
            default => 'done.svg',
        };
    }

    private function amenityDescription(string $name): string
    {
        $descriptions = [
            'Wi-Fi toc do cao' => 'Ket noi on dinh cho lam viec tu xa, xem phim va goi video.',
            'Ho boi ngoai troi' => 'Khong gian thu gian phu hop cho ky nghi cung gia dinh hoac nhom ban.',
            'Bai do xe mien phi' => 'Thuan tien cho khach di xe rieng va can cho de xe an toan.',
            'Bep rieng' => 'De dang chuan bi bua an nhe hoac nau nuong trong chuyen di dai ngay.',
            'May lanh' => 'Giu khong gian mat me va de chiu trong nhung ngay thoi tiet nong.',
            'San vuon' => 'Phu hop cho nhung buoi sang yen tinh, uong ca phe hoac doc sach.',
        ];

        return $descriptions[$name] ?? 'Tien ich pho bien thuong duoc khach uu tien khi chon cho nghi.';
    }
}
