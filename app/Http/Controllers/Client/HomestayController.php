<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Homestay;
use Illuminate\Support\Facades\Auth;

class HomestayController extends Controller
{
    public function show($slug)
    {
        $homestay = Homestay::where('slug', $slug)
            ->where('is_approved', true)
            ->with(['images', 'destination', 'promotion', 'amenities', 'rooms'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->firstOrFail();
        
        $relatedQuery = Homestay::query()
            ->where('is_approved', true)
            ->where('status', 'available')
            ->where('id', '!=', $homestay->id)
            ->with(['images' => fn ($query) => $query->orderByDesc('is_primary')])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews');

        $related = (clone $relatedQuery)
            ->where('destination_id', $homestay->destination_id)
            ->inRandomOrder()
            ->limit(6)
            ->get();

        if ($related->count() < 6) {
            $excludedIds = $related->pluck('id')->push($homestay->id);

            $additional = (clone $relatedQuery)
                ->whereNotIn('id', $excludedIds)
                ->inRandomOrder()
                ->limit(6 - $related->count())
                ->get();

            $related = $related->concat($additional);
        }
        
        $reviews = $homestay->reviews()->with('user')->latest()->paginate(15);

        $reviewableBooking = null;
        if (Auth::check()) {
            $reviewableBooking = Booking::where('user_id', Auth::id())
                ->where('homestay_id', $homestay->id)
                ->where('status', Booking::STATUS_COMPLETED)
                ->whereDoesntHave('review')
                ->latest('check_out')
                ->first();
        }

        $reviewBreakdown = collect(range(5, 1))->map(function (int $star) use ($homestay) {
            $count = $homestay->reviews()->where('rating', $star)->count();
            $total = max(1, (int) $homestay->reviews_count);

            return [
                'star' => $star,
                'label' => $star === 5 ? 'Rất tốt' : ($star === 4 ? 'Tốt' : ($star === 3 ? 'Ổn' : ($star === 2 ? 'Kém' : 'Rất kém'))),
                'count' => $count,
                'percent' => (int) round(($count / $total) * 100),
            ];
        })->all();
        
        return view('clients.homestay-detail', compact('homestay', 'related', 'reviews', 'reviewableBooking', 'reviewBreakdown'));
    }
}

