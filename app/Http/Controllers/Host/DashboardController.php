<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\Homestay;
use App\Models\Booking;
use App\Models\Payout;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $hostId = Auth::id();
        
        // Lấy tham số lọc từ request
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');
        
        $totalHomestays = Homestay::where('owner_id', $hostId)->count();
        $approvedHomestays = Homestay::where('owner_id', $hostId)->where('is_approved', true)->count();
        
        // Query bookings có hỗ trợ lọc ngày
        $bookingsQuery = Booking::whereHas('homestay', function($q) use ($hostId) {
            $q->where('owner_id', $hostId);
        });
        
        if ($fromDate) {
            $bookingsQuery->whereDate('created_at', '>=', $fromDate);
        }
        if ($toDate) {
            $bookingsQuery->whereDate('created_at', '<=', $toDate);
        }
        
        $totalBookings = (clone $bookingsQuery)->count();
        $pendingBookings = (clone $bookingsQuery)->where('status', Booking::STATUS_PENDING)->count();
        $completedBookings = (clone $bookingsQuery)->where('status', Booking::STATUS_COMPLETED)->count();
        
        // Doanh thu thực nhận (host_earn)
        $totalRevenue = (clone $bookingsQuery)->where('status', Booking::STATUS_COMPLETED)->sum('host_earn');
        
        $totalWithdrawn = Payout::where('host_id', $hostId)->where('status', 'completed')->sum('amount');
        $availableBalance = $totalRevenue - $totalWithdrawn;
        
        $recentHomestays = Homestay::where('owner_id', $hostId)
            ->with('destination')
            ->latest()
            ->limit(5)
            ->get();
        
        $recentBookings = (clone $bookingsQuery)
            ->with(['homestay', 'user'])
            ->latest()
            ->limit(5)
            ->get();
        
        $chartStart = $fromDate
            ? Carbon::parse($fromDate)->startOfDay()
            : now()->subDays(13)->startOfDay();
        $chartEnd = $toDate
            ? Carbon::parse($toDate)->endOfDay()
            : now()->endOfDay();

        if ($chartStart->gt($chartEnd)) {
            [$chartStart, $chartEnd] = [$chartEnd->copy()->startOfDay(), $chartStart->copy()->endOfDay()];
        }

        $chartDays = max(1, $chartStart->copy()->startOfDay()->diffInDays($chartEnd->copy()->startOfDay()) + 1);
        $chartLabels = [];
        $scatterRevenue = [];
        $scatterBookings = [];

        $revenueByDate = Booking::query()
            ->whereHas('homestay', function ($query) use ($hostId) {
                $query->where('owner_id', $hostId);
            })
            ->selectRaw('DATE(created_at) as report_date, SUM(host_earn) as total_amount')
            ->where('status', Booking::STATUS_COMPLETED)
            ->whereBetween('created_at', [$chartStart, $chartEnd])
            ->groupBy('report_date')
            ->pluck('total_amount', 'report_date');

        $bookingsByDate = Booking::query()
            ->whereHas('homestay', function ($query) use ($hostId) {
                $query->where('owner_id', $hostId);
            })
            ->selectRaw('DATE(created_at) as report_date, COUNT(*) as total_bookings')
            ->whereBetween('created_at', [$chartStart, $chartEnd])
            ->groupBy('report_date')
            ->pluck('total_bookings', 'report_date');

        for ($date = $chartStart->copy()->startOfDay(); $date->lte($chartEnd); $date->addDay()) {
            $dateKey = $date->toDateString();
            $chartLabels[] = $date->format('d/m');
            $scatterRevenue[] = ['x' => $dateKey, 'y' => (float) ($revenueByDate[$dateKey] ?? 0)];
            $scatterBookings[] = ['x' => $dateKey, 'y' => (int) ($bookingsByDate[$dateKey] ?? 0)];
        }

        $chartTickLimit = $this->resolveDashboardTickLimit($chartDays);

        $bookingStatusCounts = (clone $bookingsQuery)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $bookingStatusMeta = collect($bookingStatusCounts)
            ->map(function ($total, $status) {
                return [
                    'status' => $status,
                    'label' => Booking::labelForStatus($status),
                    'color' => Booking::chartColorForStatus($status),
                    'total' => (int) $total,
                ];
            })
            ->values();

        $bookingStatusLabels = $bookingStatusMeta->pluck('label')->all();
        $bookingStatusData = $bookingStatusMeta->pluck('total')->all();
        $bookingStatusColors = $bookingStatusMeta->pluck('color')->all();
        
        // Đảm bảo các biến được truyền vào view
        return view('host.dashboard', compact(
            'totalHomestays', 'approvedHomestays', 'totalBookings',
            'pendingBookings', 'completedBookings', 'totalRevenue',
            'availableBalance', 'recentHomestays', 'recentBookings',
            'fromDate', 'toDate', // thêm 2 biến này
            'chartLabels', 'scatterRevenue', 'scatterBookings',
            'chartTickLimit', 'bookingStatusLabels', 'bookingStatusData', 'bookingStatusMeta', 'bookingStatusColors'
        ));
    }

    private function resolveDashboardTickLimit(int $totalDays): int
    {
        if ($totalDays <= 14) return 7;
        if ($totalDays <= 30) return 8;
        if ($totalDays <= 60) return 9;
        return 10;
    }
}
