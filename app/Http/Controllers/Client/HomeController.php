<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Homestay;
use App\Models\Destination;
use App\Models\Review;

class HomeController extends Controller
{
    public function index()
    {
        $featuredHomestays = Homestay::where('is_approved', true)
            ->with(['images' => fn($q) => $q->orderByDesc('is_primary'), 'destination'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->latest()
            ->limit(6)
            ->get();

        $destinations = Destination::where('is_approved', true)->withCount('homestays')->limit(6)->get();

        $stats = [
            'homestays' => Homestay::where('is_approved', true)->count(),
            'provinces' => Homestay::distinct('province')->count('province'),
            'reviews' => Review::count(),
            'avg_rating' => round(Review::avg('rating') ?? 0, 1),
        ];

        $testimonials = Review::with(['user', 'homestay'])
            ->latest()
            ->limit(6)
            ->get()
            ->map(function ($review) {
                $userName = $review->user?->full_name ?: 'Khách hàng';

                return [
                    'name' => $userName,
                    'avatar_url' => $review->user?->avatar_url,
                    'initial' => mb_strtoupper(mb_substr($userName, 0, 1)),
                    'role' => $review->homestay?->title ?: 'Khách lưu trú',
                    'rating' => (int) $review->rating,
                    'comment' => $review->comment,
                ];
            });

        return view('clients.home', compact('featuredHomestays', 'destinations', 'stats', 'testimonials'));
    }
}
