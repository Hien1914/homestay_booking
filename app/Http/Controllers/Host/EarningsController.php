<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EarningsController extends Controller
{
    public function index(Request $request)
    {
        $hostId = Auth::id();

        $bookingsQuery = Booking::whereHas('homestay', function($q) use ($hostId) {
            $q->where('owner_id', $hostId);
        })->where('status', Booking::STATUS_COMPLETED);

        if ($request->from_date) {
            $bookingsQuery->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->to_date) {
            $bookingsQuery->whereDate('created_at', '<=', $request->to_date);
        }

        // Tổng doanh thu thực nhận (host_earn)
        $totalEarnings = (clone $bookingsQuery)->sum('host_earn');
        // Đối với host, tổng doanh thu cũng là phần họ thực nhận (đã trừ hoa hồng)
        $totalRevenue = $totalEarnings;
        
        $bookings = $bookingsQuery->latest()->paginate(15, ['*'], 'bookings_page');

        $totalWithdrawn = Payout::where('host_id', $hostId)->where('status', 'completed')->sum('amount');
        $pendingWithdrawn = Payout::where('host_id', $hostId)->where('status', 'pending')->sum('amount');
        $availableBalance = $totalEarnings - $totalWithdrawn - $pendingWithdrawn;

        $payouts = Payout::where('host_id', $hostId)->latest()->paginate(15, ['*'], 'payouts_page');

        return view('host.earnings', compact('totalEarnings', 'totalRevenue', 'totalWithdrawn', 'availableBalance', 'payouts', 'bookings'));
    }

}
