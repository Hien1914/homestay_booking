<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Homestay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function storeBooking(Request $request, string $slug)
    {
        $request->validate([
            'check_in'      => 'required|date|after_or_equal:today',
            'check_out'     => 'required|date|after:check_in',
            'num_guests'    => 'required|integer|min:1',
            'payment_method'=> 'required|string',
        ]);

        $homestay = Homestay::where('slug', $slug)->where('is_approved', true)->firstOrFail();
        $nights = Carbon::parse($request->check_in)->diffInDays($request->check_out);
        $pricePerNight = $homestay->discounted_price;
        $subtotal = $nights * $pricePerNight;
        $maxGuests = $homestay->max_guests ?? 4;
        $extraGuests = max(0, $request->num_guests - $maxGuests);
        $extraFee = $extraGuests * 100000 * $nights; // 100k/khách/đêm
        $totalAmount = $subtotal + $extraFee;
        $adminEarn = (int) round($totalAmount * 0.1);
        $hostEarn = $totalAmount - $adminEarn;

        DB::beginTransaction();
        try {
            $booking = Booking::create([
                'user_id'       => Auth::id(),
                'homestay_id'   => $homestay->id,
                'check_in'      => $request->check_in,
                'check_out'     => $request->check_out,
                'num_guests'    => $request->num_guests,
                'total_amount'  => $totalAmount,
                'admin_earn'    => $adminEarn,
                'host_earn'     => $hostEarn,
                'status'        => Booking::STATUS_PENDING,
            ]);

            Payment::create([
                'booking_id'     => $booking->id,
                'payment_method' => $request->payment_method,
                'amount'         => $totalAmount,
                'payment_status' => Payment::STATUS_PENDING,
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'redirect' => route('payment.show', $booking->id),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function storeDemoBooking(Request $request, string $slug)
    {
        return $this->storeBooking($request, $slug);
    }

    public function show(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) abort(403);
        $booking->loadMissing(['payment', 'homestay', 'user']);

        if (!$booking->payment) {
            Payment::create([
                'booking_id'     => $booking->id,
                'payment_method' => 'bank_transfer',
                'amount'         => $booking->total_amount,
                'payment_status' => Payment::STATUS_PENDING,
            ]);
        }

        session([
            'booking_id' => $booking->id,
            'checkout_amount' => $booking->total_amount,
        ]);

        return view('clients.payment', compact('booking'));
    }

    public function confirmPayment(Request $request)
    {
        $request->validate(['booking_id' => 'required|exists:bookings,id']);
        $booking = Booking::with('payment')->findOrFail($request->booking_id);
        if ($booking->user_id !== Auth::id()) abort(403);

        if (!$booking->payment) {
            Payment::create([
                'booking_id' => $booking->id,
                'payment_method' => 'bank_transfer',
                'amount' => $booking->total_amount,
                'payment_status' => Payment::STATUS_PENDING,
                'paid_at' => now(),
            ]);
        } else {
            $booking->payment->update([
                'payment_status' => Payment::STATUS_PENDING,
                'paid_at' => now(),
            ]);
        }

        return redirect()
            ->route('bookings.history')
            ->with('success', 'Đã ghi nhận thanh toán. Vui lòng chờ xác nhận.');
    }

    public function generateQR(Request $request)
    {
        try {
            $bankAccount = env('BANK_ACCOUNT', '19074350511010');
            $bankName = env('BANK_NAME', 'Techcombank');
            $bankBin = env('BANK_BIN', '970407');
            $accountName = env('ACCOUNT_NAME', 'PHAM THUY HIEN');
            $amount = (int) session('checkout_amount', 0);
            $bookingId = session('booking_id');

            if ($bookingId) {
                $booking = Booking::find($bookingId);
                $transferMessage = 'BOOKING' . ($booking ? $booking->id : 'DEMO');
            } else {
                $transferMessage = 'DEMO' . time();
            }

            $expireTime = now()->addMinutes(15);
            $transferMessage = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $transferMessage));

            $vietQRUrl = "https://img.vietqr.io/image/{$bankBin}-{$bankAccount}-compact2.jpg?" . http_build_query([
                'amount' => $amount,
                'addInfo' => $transferMessage,
                'accountName' => $accountName,
            ]);

            session([
                'qr_code' => $transferMessage,
                'qr_expire' => $expireTime->format('Y-m-d H:i:s'),
            ]);

            return response()->json([
                'success' => true,
                'qr_image' => $vietQRUrl,
                'bank_info' => [
                    'bank' => $bankName,
                    'account' => $bankAccount,
                    'account_name' => $accountName,
                    'amount' => $amount,
                ],
                'transfer_code' => $transferMessage,
                'expire_time' => $expireTime->format('Y-m-d H:i:s'),
            ]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Không thể tạo mã QR.'], 500);
        }
    }

    public function proxyQR(Request $request)
    {
        $url = $request->query('url');
        if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
            return response('Invalid URL', 400);
        }

        try {
            $imageResponse = Http::timeout(5)->get($url);

            if (!$imageResponse->successful()) {
                return response('Failed to load QR', 500);
            }

            return response($imageResponse->body())->header('Content-Type', 'image/png');
        } catch (\Exception $e) {
            return response('Failed to load QR', 500);
        }
    }
}