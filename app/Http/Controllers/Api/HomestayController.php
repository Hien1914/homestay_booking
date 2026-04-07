<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Homestay\CreateHomestayRequest;
use App\Http\Requests\Homestay\UpdateHomestayRequest;
use App\Http\Resources\HomestayResource;
use App\Http\Resources\ReviewResource;
use App\Models\Homestay;
use App\Models\HomestayImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomestayController extends Controller
{
    // GET /api/homestays — tìm kiếm & lọc (public)
    public function index(Request $request): JsonResponse
    {
        $query = Homestay::query()->where('status', 'published')->with('amenities');

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

        $sort = $request->sort_by ?? 'booking_count';
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
        if ($sort === 'popular') {
            $sort = 'booking_count';
            $dir = 'desc';
        }

        $homestays = $query
            ->orderBy($sort, $dir)
            ->orderByDesc('booking_count')
            ->paginate($request->per_page ?? 12);

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

    // GET /api/homestays/{id}
    public function show(Homestay $homestay): JsonResponse
    {
        $homestay->load('amenities', 'images');
        return response()->json(['success' => true, 'data' => new HomestayResource($homestay)]);
    }

    // POST /api/homestays — tạo homestay mới (auth)
    public function store(CreateHomestayRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['host_id'] = auth()->id();

        $homestay = Homestay::create($data);

        // Lưu amenities
        if ($request->has('amenities')) {
            $homestay->amenities()->attach($request->amenities);
        }

        // Lưu ảnh đại diện
        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('homestays', 'public');
            HomestayImage::create([
                'homestay_id' => $homestay->id,
                'image_url'   => $path,
                'is_primary'  => true,
            ]);
        }

        // Lưu ảnh phòng
        if ($request->hasFile('room_images')) {
            foreach ($request->file('room_images') as $image) {
                $path = $image->store('homestays', 'public');
                HomestayImage::create([
                    'homestay_id' => $homestay->id,
                    'image_url'   => $path,
                    'is_primary'  => false,
                ]);
            }
        }

        return response()->json(
            ['success' => true, 'message' => 'Tạo homestay thành công', 'data' => new HomestayResource($homestay)],
            201
        );
    }

    // PUT /api/homestays/{id} — cập nhật (auth + ownership)
    public function update(UpdateHomestayRequest $request, Homestay $homestay): JsonResponse
    {
        if ($homestay->host_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Bạn không có quyền cập nhật homestay này'], 403);
        }

        $data = $request->validated();
        $homestay->update($data);

        // Cập nhật amenities
        if ($request->has('amenities')) {
            $homestay->amenities()->sync($request->amenities);
        }

        // Xóa ảnh
        if ($request->has('delete_images')) {
            HomestayImage::whereIn('id', $request->delete_images)->delete();
        }

        // Cập nhật ảnh đại diện
        if ($request->hasFile('cover_image')) {
            $homestay->images()->where('is_primary', true)->delete();
            $path = $request->file('cover_image')->store('homestays', 'public');
            HomestayImage::create([
                'homestay_id' => $homestay->id,
                'image_url'   => $path,
                'is_primary'  => true,
            ]);
        }

        // Thêm ảnh phòng
        if ($request->hasFile('room_images')) {
            foreach ($request->file('room_images') as $image) {
                $path = $image->store('homestays', 'public');
                HomestayImage::create([
                    'homestay_id' => $homestay->id,
                    'image_url'   => $path,
                    'is_primary'  => false,
                ]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Cập nhật homestay thành công', 'data' => new HomestayResource($homestay)]);
    }

    // DELETE /api/homestays/{id} — xóa (auth + ownership)
    public function destroy(Homestay $homestay): JsonResponse
    {
        if ($homestay->host_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Bạn không có quyền xóa homestay này'], 403);
        }

        $homestay->delete();

        return response()->json(['success' => true, 'message' => 'Xóa homestay thành công']);
    }

    // GET /api/user/homestays — lấy homestays của user (auth)
    public function userHomestays(): JsonResponse
    {
        $homestays = Homestay::where('host_id', auth()->id())
            ->with('amenities', 'images')
            ->latest()
            ->paginate(12);

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

    // GET /api/homestays/{id}/availability
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

    // GET /api/homestays/{id}/reviews
    public function reviews(Homestay $homestay): JsonResponse
    {
        $reviews = $homestay->reviews()->with('user')->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'data'    => ReviewResource::collection($reviews),
            'meta'    => ['total' => $reviews->total(), 'avg_rating' => $homestay->avg_rating],
        ]);
    }

    // GET /api/homestays/{id}/bookings — lấy lịch đặt phòng
    public function bookings(Homestay $homestay): JsonResponse
    {
        $bookings = $homestay->bookings()
            ->where('status', '!=', 'cancelled')
            ->select('id', 'guest_id', 'check_in', 'check_out')
            ->with('guest:id,full_name')
            ->get()
            ->map(fn($booking) => [
                'id' => $booking->id,
                'guest_name' => $booking->guest->full_name ?? 'Khách',
                'check_in_date' => $booking->check_in,
                'check_out_date' => $booking->check_out,
            ]);

        return response()->json([
            'success' => true,
            'bookings' => $bookings,
        ]);
    }
}
