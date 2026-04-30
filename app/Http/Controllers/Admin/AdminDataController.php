<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Homestay;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Promotion;
use App\Models\Amenity;
use App\Models\Review;
use App\Models\Post;
use App\Models\Destination;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AdminDataController extends Controller
{
    public function dashboard(Request $request)
    {
        $request->validate([
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],
        ]);

        $hasPaymentsTable = Schema::hasTable('payments');
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');

        $usersQuery = User::query()->where('role', 'user');
        $homestaysQuery = Homestay::query();
        $bookingsQuery = Booking::query();
        $paymentsQuery = Payment::query();

        $this->applyDateRange($usersQuery, $fromDate, $toDate);
        $this->applyDateRange($homestaysQuery, $fromDate, $toDate);
        $this->applyDateRange($bookingsQuery, $fromDate, $toDate);
        if ($hasPaymentsTable) {
            $this->applyDateRange($paymentsQuery, $fromDate, $toDate);
        }

        $totalUsers = (clone $usersQuery)->count();
        $totalHomestays = (clone $homestaysQuery)->count();
        $totalBookings = (clone $bookingsQuery)->count();
        $totalRevenue = $hasPaymentsTable
            ? (clone $paymentsQuery)->where('payment_status', 'success')->sum('amount')
            : 0;

        $recentHomestays = (clone $homestaysQuery)
            ->with('destination')
            ->latest()
            ->limit(10)
            ->get();

        $recentUsers = (clone $usersQuery)
            ->latest()
            ->limit(10)
            ->get();

        $popularHomestaysQuery = Homestay::query()
            ->with('destination')
            ->withCount([
                'bookings' => function ($query) use ($fromDate, $toDate) {
                    $this->applyDateRange($query, $fromDate, $toDate);
                },
                'favorites',
            ])
            ->withAvg('reviews', 'rating');
        $popularHomestays = $popularHomestaysQuery
            ->orderByDesc('bookings_count')
            ->orderByDesc('favorites_count')
            ->orderByDesc('reviews_avg_rating')
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

        $revenueByDate = $hasPaymentsTable
            ? Payment::query()
                ->selectRaw('DATE(created_at) as report_date, SUM(amount) as total_amount')
                ->where('payment_status', 'success')
                ->whereBetween('created_at', [$chartStart, $chartEnd])
                ->groupBy('report_date')
                ->pluck('total_amount', 'report_date')
            : collect();

        $bookingsByDate = Booking::query()
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

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalHomestays' => $totalHomestays,
            'totalBookings' => $totalBookings,
            'totalRevenue' => $totalRevenue,
            'recentHomestays' => $recentHomestays,
            'recentUsers' => $recentUsers,
            'popularHomestays' => $popularHomestays,
            'chartLabels' => $chartLabels,
            'scatterRevenue' => $scatterRevenue,
            'scatterBookings' => $scatterBookings,
            'chartTickLimit' => $this->resolveDashboardTickLimit($chartDays),
            'bookingStatusLabels' => $bookingStatusLabels,
            'bookingStatusData' => $bookingStatusData,
            'bookingStatusMeta' => $bookingStatusMeta,
            'bookingStatusColors' => $bookingStatusColors,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
        ]);
    }

    public function users(Request $request)
    {
        $request->validate([
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],
        ]);

        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');

        $usersQuery = User::query()
            ->whereIn('role', ['user', 'host'])
            ->withCount('bookings');

        if ($fromDate) {
            $usersQuery->whereDate('created_at', '>=', $fromDate);
        }
        if ($toDate) {
            $usersQuery->whereDate('created_at', '<=', $toDate);
        }

        $totalUsers = (clone $usersQuery)->count();
        $todayCount = (clone $usersQuery)->whereDate('created_at', now()->toDateString())->count();
        $yesterdayCount = (clone $usersQuery)->whereDate('created_at', now()->subDay()->toDateString())->count();
        $newUsersRate = $yesterdayCount > 0
            ? round((($todayCount - $yesterdayCount) / $yesterdayCount) * 100, 1)
            : ($todayCount > 0 ? 100.0 : 0.0);

        $chartUsers = (clone $usersQuery)->get(['gender', 'birthday']);
        $genderLabels = ['Nam', 'Nữ', 'Khác / Chưa cập nhật'];
        $genderData = [
            $chartUsers->where('gender', 'male')->count(),
            $chartUsers->where('gender', 'female')->count(),
            $chartUsers->whereNotIn('gender', ['male', 'female'])->count(),
        ];

        $ageLabels = ['18-20', '21-25', '26-30', '31-35', 'Khác / Chưa rõ'];
        $ageData = [0, 0, 0, 0, 0];
        foreach ($chartUsers as $chartUser) {
            $age = $chartUser->age;
            if (!$age) {
                $ageData[4]++;
            } elseif ($age <= 20) {
                $ageData[0]++;
            } elseif ($age <= 25) {
                $ageData[1]++;
            } elseif ($age <= 30) {
                $ageData[2]++;
            } elseif ($age <= 35) {
                $ageData[3]++;
            } else {
                $ageData[4]++;
            }
        }

        $users = $usersQuery->latest()->paginate(15)->withQueryString();

        return view('admin.users', [
            'users' => $users,
            'totalUsers' => $totalUsers,
            'newUsersToday' => $todayCount,
            'newUsersRate' => $newUsersRate,
            'genderLabels' => $genderLabels,
            'genderData' => $genderData,
            'ageLabels' => $ageLabels,
            'ageData' => $ageData,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
        ]);
    }

    public function bookings(Request $request)
    {
        $request->validate([
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],
        ]);

        $hasPaymentsTable = Schema::hasTable('payments');
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');

        $baseQuery = Booking::query()
            ->with($hasPaymentsTable ? ['user', 'homestay', 'payment'] : ['user', 'homestay'])
            ->latest('id');

        $this->applyDateRange($baseQuery, $fromDate, $toDate);

        $pendingApprovalsQuery = (clone $baseQuery)->whereHas('payment', function ($query) {
            $query->where('payment_status', Payment::STATUS_PENDING)
                ->whereNotNull('paid_at');
        });

        $bookingsQuery = (clone $baseQuery)->where(function ($query) {
            $query->whereDoesntHave('payment')
                ->orWhereHas('payment', function ($paymentQuery) {
                    $paymentQuery->where('payment_status', '!=', Payment::STATUS_PENDING)
                        ->orWhereNull('paid_at');
                });
        });

        $pendingApprovals = $pendingApprovalsQuery
            ->paginate(10, ['*'], 'pending_page')
            ->withQueryString();
        $bookings = $bookingsQuery
            ->paginate(15, ['*'], 'bookings_page')
            ->withQueryString();

        return view('admin.bookings', [
            'pendingApprovals' => $pendingApprovals,
            'bookings' => $bookings,
            'totalBookings' => (clone $baseQuery)->count(),
            'confirmedBookings' => (clone $baseQuery)->where('status', Booking::STATUS_CONFIRMED)->count(),
            'pendingBookings' => (clone $baseQuery)->where('status', Booking::STATUS_PENDING)->count(),
            'totalRevenue' => $hasPaymentsTable
                ? (clone $baseQuery)->whereHas('payment', fn ($query) => $query->where('payment_status', Payment::STATUS_SUCCESS))->sum('total_amount')
                : 0,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
        ]);
    }

    public function bookingDetail(Request $request, Booking $booking)
    {
        $booking->load(['user', 'homestay', 'payment']);

        if ($request->ajax() || $request->wantsJson()) {
            return view('admin.bookings.show_partial', compact('booking'));
        }

        return redirect()->route('admin.bookings');
    }

    public function payments(Request $request)
    {
        if (!Schema::hasTable('payments')) {
            return redirect()->route('admin.dashboard')->with('error', 'Bảng payments chưa được tạo. Hãy chạy migrate trước.');
        }

        $search = trim((string) $request->query('search'));
        $status = $request->query('status');
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');

        $paymentsQuery = Payment::query()
            ->with(['booking.user', 'booking.homestay'])
            ->latest('created_at');

        $this->applyDateRange($paymentsQuery, $fromDate, $toDate);

        if ($status && in_array($status, array_keys(Payment::statuses()), true)) {
            $paymentsQuery->where('payment_status', $status);
        }

        if ($search !== '') {
            $paymentsQuery->where(function ($query) use ($search) {
                $query->whereHas('booking', function ($bookingQuery) use ($search) {
                    $bookingQuery->where('id', 'like', '%' . $search . '%')
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('full_name', 'like', '%' . $search . '%')
                                ->orWhere('email', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('homestay', function ($homestayQuery) use ($search) {
                            $homestayQuery->where('title', 'like', '%' . $search . '%');
                        });
                });
            });
        }

        $payments = $paymentsQuery->paginate(15)->withQueryString();

        $allPayments = Payment::query();
        $this->applyDateRange($allPayments, $fromDate, $toDate);
        $allPayments = $allPayments->get(['amount', 'payment_status']);

        [$chartLabels, $revenueData] = $this->buildRevenueChartData($fromDate, $toDate, 10);

        return view('admin.payments.index', [
            'payments' => $payments,
            'totalRevenue' => $allPayments->where('payment_status', Payment::STATUS_SUCCESS)->sum('amount'),
            'successPayments' => $allPayments->where('payment_status', Payment::STATUS_SUCCESS)->count(),
            'pendingPayments' => $allPayments->where('payment_status', Payment::STATUS_PENDING)->count(),
            'search' => $search,
            'status' => $status,
            'statuses' => Payment::statuses(),
            'chartLabels' => $chartLabels,
            'revenueData' => $revenueData,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
        ]);
    }

    public function paymentShow(Payment $payment)
    {
        if (!Schema::hasTable('payments')) {
            return redirect()->route('admin.dashboard')->with('error', 'Bảng payments chưa được tạo. Hãy chạy migrate trước.');
        }
        $payment->load(['booking.user', 'booking.homestay']);

        if (request()->ajax() || request()->wantsJson()) {
            return view('admin.payments.show_partial', compact('payment'));
        }

        return view('admin.payments.show', compact('payment'));
    }

    public function promotions(Request $request)
    {
        $request->validate([
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],
        ]);
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');

        $promotionsQuery = Promotion::query()
            ->with(['homestays.owner'])
            ->latest('created_at');
        $this->applyDateRange($promotionsQuery, $fromDate, $toDate);
        $promotions = $promotionsQuery->paginate(15)->withQueryString();

        $statsPromotions = Promotion::query();
        $this->applyDateRange($statsPromotions, $fromDate, $toDate);
        $statsPromotions = $statsPromotions->get();

        $totalPromotions = $statsPromotions->count();
        $activePromotions = $statsPromotions->where('is_active', true)->where('end_date', '>=', now())->count();
        $expiredPromotions = $statsPromotions->where('end_date', '<', now())->count();
        $pendingPromotions = $statsPromotions->where('start_date', '>', now())->count();

        return view('admin.promotions.index', compact('promotions', 'totalPromotions', 'activePromotions', 'expiredPromotions', 'pendingPromotions', 'fromDate', 'toDate'));
    }

    public function amenities(Request $request)
    {
        $request->validate([
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],
        ]);
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');

        $amenitiesQuery = Amenity::query()
            ->withCount('homestays')
            ->latest('created_at');
        $this->applyDateRange($amenitiesQuery, $fromDate, $toDate);
        $amenities = $amenitiesQuery->paginate(15)->withQueryString();

        return view('admin.amenities.index', [
            'amenities' => $amenities,
            'totalAmenities' => $amenities->total(),
            'activeAmenities' => $amenities->where('homestays_count', '>', 0)->count(),
            'fromDate' => $fromDate,
            'toDate' => $toDate,
        ]);
    }

    public function reviews(Request $request)
    {
        $request->validate([
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],
        ]);
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');

        $reviewsQuery = Review::query()
            ->with(['user', 'homestay'])
            ->latest('created_at');
        $this->applyDateRange($reviewsQuery, $fromDate, $toDate);
        $reviews = $reviewsQuery->paginate(15)->withQueryString();

        return view('admin.reviews', compact('reviews', 'fromDate', 'toDate'));
    }

    public function destroyReview(Review $review)
    {
        $review->delete();

        return back()->with('success', 'Đã xóa đánh giá #' . $review->id . '.');
    }

    public function blogs(Request $request)
    {
        $request->validate([
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],
        ]);
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');

        $postsQuery = Post::query()->latest();
        $this->applyDateRange($postsQuery, $fromDate, $toDate);
        $posts = $postsQuery->paginate(15)->withQueryString();

        return view('admin.posts.index', [
            'posts' => $posts,
            'totalPost' => $posts->total(),
            'activePromotions' => $posts->where('status', 'published')->count(),
            'expiredPromotions' => $posts->where('status', '!=', 'published')->count(),
            'fromDate' => $fromDate,
            'toDate' => $toDate,
        ]);
    }

    public function confirmPayment(Booking $booking)
    {
        if (!$booking->payment) {
            return back()->with('error', 'Booking chưa có thông tin thanh toán.');
        }

        if (!$booking->payment->paid_at) {
            return back()->with('error', 'Thanh toán này chưa được khách xác nhận chuyển khoản.');
        }

        \DB::transaction(function () use ($booking) {
            $booking->update(['status' => Booking::STATUS_CONFIRMED]);
            $booking->payment->update([
                'payment_status' => Payment::STATUS_SUCCESS,
            ]);
        });

        return back()->with('success', 'Đã xác nhận thanh toán cho booking #' . $booking->id);
    }

    private function applyDateRange($query, ?string $fromDate, ?string $toDate): void
    {
        if ($fromDate) {
            $query->whereDate('created_at', '>=', $fromDate);
        }
        if ($toDate) {
            $query->whereDate('created_at', '<=', $toDate);
        }
    }

    private function resolveDashboardTickLimit(int $totalDays): int
    {
        if ($totalDays <= 14) return 7;
        if ($totalDays <= 30) return 8;
        if ($totalDays <= 60) return 9;
        return 10;
    }

    private function buildRevenueChartData(?string $fromDate, ?string $toDate, int $defaultDays = 10): array
    {
        $chartEnd = $toDate ? Carbon::parse($toDate)->endOfDay() : now()->endOfDay();
        $chartStart = $fromDate ? Carbon::parse($fromDate)->startOfDay() : $chartEnd->copy()->subDays(max(0, $defaultDays - 1))->startOfDay();
        if ($chartStart->gt($chartEnd)) {
            [$chartStart, $chartEnd] = [$chartEnd->copy()->startOfDay(), $chartStart->copy()->endOfDay()];
        }
        $revenueByDate = Payment::query()
            ->selectRaw('DATE(created_at) as report_date, SUM(amount) as total_amount')
            ->where('payment_status', Payment::STATUS_SUCCESS)
            ->whereBetween('created_at', [$chartStart, $chartEnd])
            ->groupBy('report_date')
            ->pluck('total_amount', 'report_date');
        $labels = [];
        $data = [];
        for ($date = $chartStart->copy()->startOfDay(); $date->lte($chartEnd); $date->addDay()) {
            $dateKey = $date->toDateString();
            $labels[] = $date->format('d/m');
            $data[] = (float) ($revenueByDate[$dateKey] ?? 0);
        }
        return [$labels, $data];
    }
}

