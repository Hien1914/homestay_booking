<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingHistoryController extends Controller
{
    public function history()
    {
        $bookings = Booking::with('homestay', 'payment')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(15);
        return view('clients.booking-history', compact('bookings'));
    }

    public function cancel(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        if ($booking->user_id !== Auth::id()) abort(403);
        if (!$booking->canBeCancelled()) {
            return back()->with('error', 'Không thể hủy booking này.');
        }
        $booking->update([
            'cancel_status'      => 'pending',
            'cancel_requested_at'=> now(),
        ]);
        return back()->with('success', 'Yêu cầu hủy đã gửi, chờ host duyệt.');
    }

    public function checkin(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) abort(403);
        if ($booking->status !== Booking::STATUS_CONFIRMED) {
            return back()->with('error', 'Chỉ có thể nhận phòng khi đơn đặt đã thành công.');
        }
        $booking->update(['status' => Booking::STATUS_CHECKED_IN]);
        return back()->with('success', 'Bạn đã nhận phòng thành công! Chúc bạn có một kỳ nghỉ tuyệt vời.');
    }

    public function checkout(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) abort(403);
        if ($booking->status !== Booking::STATUS_CHECKED_IN) {
            return back()->with('error', 'Chỉ có thể trả phòng khi đang ở.');
        }
        $booking->update(['status' => Booking::STATUS_COMPLETED]);
        return back()->with('success', 'Bạn đã trả phòng thành công. Hẹn gặp lại!');
    }
}

