<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\CreateBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Homestay;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /** Phí thêm mỗi khách vượt quy định (đ/người/đêm) */
    private const EXTRA_GUEST_FEE_PER_NIGHT = 100_000;

    /** Ngưỡng và % đặt cọc: total < 500k trả full, 500k-2tr cọc 15%, >= 2tr cọc 20% */
    private const DEPOSIT_THRESHOLD = 500_000;
    private const DEPOSIT_RATE_MEDIUM = 0.15;
    private const DEPOSIT_RATE_HIGH = 0.20;

    // POST /bookings/calculate
    public function calculate(Request $request): JsonResponse
    {
        $request->validate([
            'homestay_id'  => 'required|integer|exists:homestays,id',
            'check_in'     => 'required|date|after_or_equal:today',
            'check_out'    => 'required|date|after:check_in',
            'num_guests'   => 'required|integer|min:1',
        ]);

        $homestay = Homestay::findOrFail($request->homestay_id);

        if (! $homestay->isAvailable($request->check_in, $request->check_out)) {
            return response()->json([
                'success' => false,
                'message' => 'Homestay không còn trống trong khoảng thời gian này',
            ], 422);
        }

        $result = $this->computeBookingAmount($homestay, $request->check_in, $request->check_out, $request->num_guests);

        return response()->json([
            'success' => true,
            'data'    => array_merge([
                'homestay_name' => $homestay->name,
                'check_in'      => $request->check_in,
                'check_out'     => $request->check_out,
            ], $result),
        ]);
    }

    // POST /bookings
    public function store(CreateBookingRequest $request): JsonResponse
    {
        $homestay = Homestay::findOrFail($request->homestay_id);

        if (! $homestay->isAvailable($request->check_in_date, $request->check_out_date)) {
            return response()->json([
                'success' => false,
                'message' => 'Homestay không còn trống trong khoảng thời gian này',
            ], 422);
        }

        $amounts = $this->computeBookingAmount($homestay, $request->check_in_date, $request->check_out_date, $request->num_guests);

        $booking = Booking::create([
            'booking_code'     => Booking::generateCode(),
            'user_id'          => $request->user()->id,
            'homestay_id'      => $homestay->id,
            'check_in_date'    => $request->check_in_date,
            'check_out_date'   => $request->check_out_date,
            'num_nights'       => $amounts['num_nights'],
            'num_guests'       => $request->num_guests,
            'price_per_night'  => $homestay->price_per_night,
            'service_fee'      => $amounts['service_fee'],
            'total_amount'     => $amounts['total_amount'],
            'special_requests' => $request->special_requests,
            'status'           => 'pending',
            'payment_status'   => 'unpaid',
        ]);

        $deposit = $this->computeDeposit($amounts['total_amount']);

        $resource = (new BookingResource($booking->load('homestay')))->toArray(request());

        return response()->json([
            'success'        => true,
            'message'        => 'Tạo booking thành công. Vui lòng tiến hành thanh toán.',
            'data'           => array_merge($resource, [
                'deposit_amount' => $deposit['amount'],
                'amount_to_pay'  => $deposit['amount_to_pay'],
                'is_deposit'     => $deposit['is_deposit'],
            ]),
            'payment_method' => $request->payment_method,
        ], 201);
    }

    /**
     * @return array{num_nights: int, subtotal: float, extra_guests_fee: float, service_fee: float, total_amount: float}
     */
    private function computeBookingAmount(Homestay $homestay, string $checkIn, string $checkOut, int $numGuests): array
    {
        $nights           = (int) Carbon::parse($checkIn)->diffInDays($checkOut);
        $subtotal         = (float) $homestay->price_per_night * $nights;
        $extraGuests      = max(0, $numGuests - $homestay->max_guests);
        $extraGuestsFee   = $extraGuests * self::EXTRA_GUEST_FEE_PER_NIGHT * $nights;
        $beforeServiceFee = $subtotal + $extraGuestsFee;
        $serviceFee       = round($beforeServiceFee * 0.10);

        $totalAmount = $beforeServiceFee + $serviceFee;
        $deposit     = $this->computeDeposit($totalAmount);

        return [
            'num_nights'       => $nights,
            'subtotal'         => $subtotal,
            'extra_guests_fee' => $extraGuestsFee,
            'service_fee'      => $serviceFee,
            'total_amount'     => $totalAmount,
            'deposit_amount'   => $deposit['amount'],
            'amount_to_pay'    => $deposit['amount_to_pay'],
            'is_deposit'       => $deposit['is_deposit'],
        ];
    }

    /**
     * @return array{amount: float, amount_to_pay: float, is_deposit: bool}
     */
    private function computeDeposit(float $totalAmount): array
    {
        if ($totalAmount < self::DEPOSIT_THRESHOLD) {
            return [
                'amount'       => 0,
                'amount_to_pay' => $totalAmount,
                'is_deposit'   => false,
            ];
        }
        $rate = $totalAmount >= 2_000_000 ? self::DEPOSIT_RATE_HIGH : self::DEPOSIT_RATE_MEDIUM;
        $amount = round($totalAmount * $rate);

        return [
            'amount'        => $amount,
            'amount_to_pay' => $amount,
            'is_deposit'    => true,
        ];
    }

    // GET /bookings/{id}
    public function show(Request $request, Booking $booking): JsonResponse
    {
        if ($booking->user_id !== $request->user()->id && ! $request->user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Không có quyền xem'], 403);
        }

        return response()->json([
            'success' => true,
            'data'    => new BookingResource($booking->load('homestay')),
        ]);
    }

    // GET /user/bookings
    public function myBookings(Request $request): JsonResponse
    {
        $bookings = Booking::with('homestay')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data'    => BookingResource::collection($bookings),
            'meta'    => ['total' => $bookings->total(), 'current_page' => $bookings->currentPage()],
        ]);
    }

    // PUT /bookings/{id}/cancel
    public function cancel(Request $request, Booking $booking): JsonResponse
    {
        $request->validate(['reason' => 'nullable|string|max:500']);

        if ($booking->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Không có quyền'], 403);
        }

        if (! in_array($booking->status, ['pending', 'confirmed'])) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể hủy booking ở trạng thái ' . $booking->status,
            ], 422);
        }

        $booking->update([
            'status'              => 'cancelled',
            'cancellation_reason' => $request->reason,
        ]);

        // TODO: Xử lý hoàn tiền nếu đã thanh toán

        return response()->json(['success' => true, 'message' => 'Hủy booking thành công']);
    }
}