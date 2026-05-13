<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $hostId = Auth::id();
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $allowedStatuses = [
            Booking::STATUS_PENDING,
            Booking::STATUS_CONFIRMED,
            Booking::STATUS_CHECKED_IN,
            Booking::STATUS_COMPLETED,
            Booking::STATUS_CANCELLED,
        ];

        $baseQuery = Booking::query()
            ->whereHas('homestay', fn($q) => $q->where('owner_id', $hostId))
            ->whereIn('status', $allowedStatuses);
        
        if ($fromDate) {
            $baseQuery->whereDate('created_at', '>=', $fromDate);
        }
        if ($toDate) {
            $baseQuery->whereDate('created_at', '<=', $toDate);
        }

        // Tính toán số lượng theo trạng thái
        $pendingCount = (clone $baseQuery)->where('status', Booking::STATUS_PENDING)->count();
        $successCount = (clone $baseQuery)
            ->whereIn('status', [Booking::STATUS_CONFIRMED, Booking::STATUS_COMPLETED])
            ->count();
        $checkedInCount = (clone $baseQuery)->where('status', Booking::STATUS_CHECKED_IN)->count();
        $cancelledCount = (clone $baseQuery)->where('status', Booking::STATUS_CANCELLED)->count();

        $cancelRequests = (clone $baseQuery)
            ->with(['homestay', 'user', 'payment'])
            ->where('cancel_status', 'pending')
            ->latest('created_at')
            ->paginate(10, ['*'], 'cancel_page')
            ->withQueryString();

        $query = (clone $baseQuery)->with(['homestay', 'user', 'payment'])
            ->where(function($q) {
                $q->whereNull('cancel_status')
                  ->orWhere('cancel_status', '!=', 'pending');
            });

        if (($status = $request->status) && in_array($status, $allowedStatuses, true)) {
            $query->where('status', $status);
        }

        $bookings = $query->latest('created_at')->paginate(15, ['*'], 'page')->withQueryString();
        
        return view('host.bookings.index', compact(
            'bookings',
            'cancelRequests',
            'pendingCount',
            'successCount',
            'checkedInCount',
            'cancelledCount',
            'fromDate',
            'toDate'
        ));
    }

    public function show(Request $request, Booking $booking)
    {
        if ($booking->homestay->owner_id !== Auth::id()) abort(403);
        $booking->load(['homestay', 'user', 'payment', 'review']);
        
        if ($request->ajax()) {
            return view('host.bookings.show_partial', compact('booking'));
        }
        
        return view('host.bookings.show', compact('booking'));
    }



    public function cancelApprove(Request $request, Booking $booking)
    {
        if ($booking->homestay->owner_id !== Auth::id()) abort(403);
        $action = $request->query('action');
        if (!in_array($action, ['approve', 'reject'])) abort(400);
        if ($booking->cancel_status !== 'pending') {
            return back()->with('error', 'Không có yêu cầu hủy đang chờ.');
        }
        if ($action === 'approve') {
            $booking->update([
                'cancel_status' => 'approved', 
                'host_approved' => true
            ]);
            $msg = 'Đã duyệt hủy phòng, chờ hệ thống hoàn tiền.';
        } else {
            $booking->update(['cancel_status' => 'rejected']);
            $msg = 'Đã từ chối yêu cầu hủy.';
        }
        return back()->with('success', $msg);
    }
}
