<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Homestay;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function store(Request $request, Homestay $homestay)
    {
        $request->validate([
            'check_in'   => 'required|date|after_or_equal:today',
            'check_out'  => 'required|date|after:check_in',
            'num_guests' => 'required|integer|min:1',
        ]);

        $existing = Booking::where('homestay_id', $homestay->id)
            ->where('status', '!=', 'cancelled')
            ->where(function($q) use ($request) {
                $q->whereBetween('check_in', [$request->check_in, $request->check_out])
                  ->orWhereBetween('check_out', [$request->check_in, $request->check_out]);
            })->exists();
        if ($existing) {
            return back()->with('error', 'Khung giờ đã có người đặt.');
        }

        $nights = Carbon::parse($request->check_in)->diffInDays($request->check_out);
        $pricePerNight = $homestay->discounted_price;
        $total = $pricePerNight * $nights;

        DB::beginTransaction();
        try {
            $booking = Booking::create([
                'user_id'     => Auth::id(),
                'homestay_id' => $homestay->id,
                'check_in'    => $request->check_in,
                'check_out'   => $request->check_out,
                'num_guests'  => $request->num_guests,
                'total_amount'=> $total,
                'status'      => Booking::STATUS_PENDING,
            ]);
            Payment::create([
                'booking_id'     => $booking->id,
                'amount'         => $total,
                'payment_status' => Payment::STATUS_PENDING,
            ]);
            DB::commit();
            return redirect()->route('payment.show', $booking)->with('success', 'Tạo booking thành công, vui lòng thanh toán.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

}