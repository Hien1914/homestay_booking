<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, string $slug, Booking $booking)
    {
        if (($booking->homestay?->slug ?? null) !== $slug) {
            abort(404);
        }

        if ($booking->user_id !== Auth::id()) abort(403);
        if ($booking->status !== Booking::STATUS_COMPLETED) {
            return back()->with('error', 'Chỉ có thể đánh giá sau khi hoàn thành chuyến đi.');
        }
        if ($booking->review) {
            return back()->with('error', 'Bạn đã đánh giá booking này rồi.');
        }
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);
        Review::create([
            'booking_id' => $booking->id,
            'user_id' => Auth::id(),
            'homestay_id' => $booking->homestay_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);
        return back()->with('success', 'Cảm ơn bạn đã đánh giá.');
    }
}
