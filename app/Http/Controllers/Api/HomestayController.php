<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HomestayResource;
use App\Http\Resources\ReviewResource;
use App\Models\Homestay;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomestayController extends Controller
{
    // GET /homestays — tìm kiếm & lọc (public)
    public function index(Request $request): JsonResponse
    {
        $query = Homestay::query()->where('status', 'active');

        if ($request->province) {
            $query->where('province', 'like', '%' . $request->province . '%');
        }

        if ($request->min_price) {
            $query->where('price_per_night', '>=', $request->min_price);
        }

        if ($request->max_price) {
            $query->where('price_per_night', '<=', $request->max_price);
        }

        if ($request->guests) {
            $query->where('max_guests', '>=', $request->guests);
        }

        if ($request->check_in && $request->check_out) {
            $query->whereDoesntHave('bookings', function ($q) use ($request) {
                $q->whereNotIn('status', ['cancelled', 'rejected'])
                    ->where('check_in_date', '<', $request->check_out)
                    ->where('check_out_date', '>', $request->check_in);
            });
        }

        $sort = $request->sort_by ?? 'created_at';
        $dir  = in_array($sort, ['price_per_night', 'avg_rating']) ? 'asc' : 'desc';
        if ($sort === 'price_desc') {
            $sort = 'price_per_night';
            $dir = 'desc';
        }
        if ($sort === 'price_asc') {
            $sort = 'price_per_night';
            $dir = 'asc';
        }
        if ($sort === 'rating') {
            $sort = 'avg_rating';
            $dir = 'desc';
        }

        $homestays = $query->orderBy($sort, $dir)->paginate($request->per_page ?? 12);

        return response()->json([
            'success' => true,
            'data'    => HomestayResource::collection($homestays),
            'meta'    => [
                'total'        => $homestays->total(),
                'current_page' => $homestays->currentPage(),
                'last_page'    => $homestays->lastPage(),
            ],
        ]);
    }

    // GET /homestays/{id}
    public function show(Homestay $homestay): JsonResponse
    {
        return response()->json(['success' => true, 'data' => new HomestayResource($homestay)]);
    }

    // GET /homestays/{id}/availability
    public function availability(Request $request, Homestay $homestay): JsonResponse
    {
        $request->validate([
            'check_in'  => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ]);

        $available = $homestay->isAvailable($request->check_in, $request->check_out);

        // Tính số đêm và tổng tiền nếu có ngày
        $nights     = \Carbon\Carbon::parse($request->check_in)->diffInDays($request->check_out);
        $subtotal   = $homestay->price_per_night * $nights;
        $serviceFee = round($subtotal * 0.10);

        return response()->json([
            'success' => true,
            'data'    => [
                'available'   => $available,
                'num_nights'  => $nights,
                'subtotal'    => $subtotal,
                'service_fee' => $serviceFee,
                'total'       => $subtotal + $serviceFee,
            ],
        ]);
    }

    // GET /homestays/{id}/reviews
    public function reviews(Homestay $homestay): JsonResponse
    {
        $reviews = $homestay->reviews()->with('user')->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'data'    => ReviewResource::collection($reviews),
            'meta'    => ['total' => $reviews->total(), 'avg_rating' => $homestay->avg_rating],
        ]);
    }
}
