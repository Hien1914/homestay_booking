<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /** % đặt cọc theo ngưỡng (đồng nhất với API) */
    private const DEPOSIT_THRESHOLD = 500_000;
    private const DEPOSIT_RATE_MEDIUM = 0.15;
    private const DEPOSIT_RATE_HIGH = 0.20;
    private const EXTRA_GUEST_FEE = 100_000;

    public function show(Request $request)
    {
        $code   = $request->query('code');
        $demo   = $request->query('demo');
        $method = $request->query('method', 'vnpay');

        if ($demo) {
            $data = session('demo_booking');
            if (! $data) {
                return redirect()->route('home')->with('error', 'Phiên đặt phòng đã hết hạn.');
            }

            return view('clients.payment.show', [
                'booking'       => (object) $data,
                'amountToPay'   => $data['amount_to_pay'] ?? $data['total_amount'],
                'isDeposit'     => $data['is_deposit'] ?? false,
                'paymentMethod' => in_array($method, ['vnpay', 'momo']) ? $method : 'vnpay',
                'isDemo'        => true,
            ]);
        }

        if (! $code) {
            return redirect()->route('home')->with('error', 'Mã đặt phòng không hợp lệ.');
        }

        $booking = Booking::where('booking_code', $code)->with('homestay')->first();
        if (! $booking) {
            return redirect()->route('home')->with('error', 'Không tìm thấy đơn đặt phòng.');
        }

        $amountToPay = $this->getAmountToPay((float) $booking->total_amount);
        $isDeposit   = $amountToPay < (float) $booking->total_amount;

        return view('clients.payment.show', [
            'booking'       => $booking,
            'amountToPay'   => $amountToPay,
            'isDeposit'     => $isDeposit,
            'paymentMethod' => in_array($method, ['vnpay', 'momo']) ? $method : 'vnpay',
            'isDemo'        => false,
        ]);
    }

    public function storeDemoBooking(Request $request, string $id)
    {
        $homestay = HomestayDetailController::getDemoHomestay($id);
        if (! $homestay) {
            return response()->json(['success' => false, 'message' => 'Homestay không tồn tại.'], 404);
        }

        $valid = $request->validate([
            'check_in_date'  => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'num_guests'     => 'required|integer|min:1',
            'payment_method' => 'required|in:vnpay,momo',
        ]);

        $amounts = $this->computeAmounts($homestay, $valid['check_in_date'], $valid['check_out_date'], (int) $valid['num_guests']);
        $deposit = $this->getDeposit($amounts['total_amount']);

        $booking = [
            'booking_code'   => 'DEMO-' . strtoupper(substr(md5(uniqid()), 0, 8)),
            'homestay_name'  => $homestay['name'],
            'check_in_date'  => $valid['check_in_date'],
            'check_out_date' => $valid['check_out_date'],
            'num_nights'     => $amounts['num_nights'],
            'num_guests'     => $valid['num_guests'],
            'total_amount'   => $amounts['total_amount'],
            'amount_to_pay'  => $deposit['amount_to_pay'],
            'is_deposit'     => $deposit['is_deposit'],
            'payment_method' => $valid['payment_method'],
        ];

        session(['demo_booking' => $booking]);

        return response()->json([
            'success' => true,
            'redirect' => route('payment.show', ['demo' => 1, 'method' => $valid['payment_method']]),
        ]);
    }

    private function computeAmounts(array $homestay, string $checkIn, string $checkOut, int $numGuests): array
    {
        $nights     = (int) (strtotime($checkOut) - strtotime($checkIn)) / 86400;
        $price      = (int) ($homestay['price_per_night'] ?? 0);
        $maxGuests  = (int) ($homestay['max_guests'] ?? 4);
        $subtotal   = $price * $nights;
        $extra      = max(0, $numGuests - $maxGuests) * self::EXTRA_GUEST_FEE * $nights;
        $beforeFee  = $subtotal + $extra;
        $serviceFee = round($beforeFee * 0.10);
        $total      = $beforeFee + $serviceFee;

        return [
            'num_nights' => $nights,
            'subtotal'   => $subtotal,
            'total_amount' => $total,
        ];
    }

    private function getDeposit(float $total): array
    {
        if ($total < self::DEPOSIT_THRESHOLD) {
            return ['amount_to_pay' => $total, 'is_deposit' => false];
        }
        $rate = $total >= 2_000_000 ? self::DEPOSIT_RATE_HIGH : self::DEPOSIT_RATE_MEDIUM;

        return [
            'amount_to_pay' => round($total * $rate),
            'is_deposit'    => true,
        ];
    }

    private function getAmountToPay(float $total): float
    {
        $d = $this->getDeposit($total);

        return $d['amount_to_pay'];
    }

}
