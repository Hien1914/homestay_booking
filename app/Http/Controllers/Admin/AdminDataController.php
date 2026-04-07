<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Homestay;
use App\Models\Inquiry;
use App\Models\Post;
use App\Models\Promotion;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminDataController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalHomestays = Homestay::count();
        $totalBookings = Booking::count();
        $publishedHomestays = Homestay::where('status', 'published')->count();
        $draftHomestays = Homestay::where('status', 'draft')->count();

        $recentHomestays = Homestay::with('images')
            ->latest()
            ->limit(5)
            ->get();

        $recentUsers = User::latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalHomestays' => $totalHomestays,
            'totalBookings' => $totalBookings,
            'publishedHomestays' => $publishedHomestays,
            'draftHomestays' => $draftHomestays,
            'recentHomestays' => $recentHomestays,
            'recentUsers' => $recentUsers,
        ]);
    }

    public function categories()
    {
        return view('admin.categories.index', [
            'categories' => Category::query()
                ->latest()
                ->get(),
        ]);
    }

    public function users()
    {
        return view('admin.users.index', [
            'users' => User::query()
                ->withCount('bookings')
                ->latest()
                ->get(),
        ]);
    }

    public function bookings()
    {
        $bookings = Booking::query()
            ->with(['user', 'homestay', 'promotion'])
            ->latest()
            ->get();

        return view('admin.bookings.index', [
            'bookings' => $bookings,
            'totalBookings' => $bookings->count(),
            'confirmedBookings' => $bookings->where('status', 'confirmed')->count(),
            'pendingBookings' => $bookings->where('status', 'pending')->count(),
            'totalRevenue' => $bookings->where('status', 'confirmed')->sum('total_price'),
        ]);
    }

    public function promotions()
    {
        $promotions = Promotion::query()
            ->latest('created_at')
            ->get();

        return view('admin.promotions.index', [
            'promotions' => $promotions,
            'totalPromotions' => $promotions->count(),
            'activePromotions' => $promotions->where('is_active', true)->where('end_date', '>=', now())->count(),
            'expiredPromotions' => $promotions->where('end_date', '<', now())->count(),
            'totalUsed' => $promotions->sum('used_count'),
        ]);
    }

    public function amenities()
    {
        $amenities = Amenity::query()
            ->withCount('homestays')
            ->latest()
            ->get();

        return view('admin.amenities.index', [
            'amenities' => $amenities,
            'totalAmenities' => $amenities->count(),
            'activeAmenities' => $amenities->where('homestays_count', '>', 0)->count(),
        ]);
    }

    public function reviews()
    {
        return view('admin.reviews.index', [
            'reviews' => Review::query()
                ->with(['reviewer', 'homestay'])
                ->latest('created_at')
                ->get(),
        ]);
    }

    public function blogs()
    {
        return view('admin.blogs.index', [
            'blogs' => Post::query()
                ->with('author')
                ->latest()
                ->get(),
        ]);
    }

    public function tickets()
    {
        return view('admin.tickets.index', [
            'tickets' => Inquiry::query()
                ->with(['guest', 'homestay'])
                ->latest('created_at')
                ->get(),
        ]);
    }

    public function reports()
    {
        $totalRevenue = Booking::where('status', 'completed')->sum('total_price');
        $totalBookings = Booking::count();
        $newUsers = User::whereMonth('created_at', now()->month)->count();
        $newHomestays = Homestay::whereMonth('created_at', now()->month)->count();

        $topHomestays = Homestay::query()
            ->withCount('bookings')
            ->withSum('bookings as total_revenue', 'total_price')
            ->withAvg('reviews as avg_rating', 'rating')
            ->orderByDesc('bookings_count')
            ->limit(10)
            ->get();

        $revenueLabels = [];
        $revenueData = [];
        $bookingLabels = [];
        $bookingData = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $revenueLabels[] = 'T' . $month->month;
            $bookingLabels[] = 'T' . $month->month;
            
            $revenueData[] = Booking::where('status', 'completed')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('total_price');
            
            $bookingData[] = Booking::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        return view('admin.reports.index', [
            'totalRevenue' => $totalRevenue,
            'totalBookings' => $totalBookings,
            'newUsers' => $newUsers,
            'newHomestays' => $newHomestays,
            'topHomestays' => $topHomestays,
            'revenueLabels' => $revenueLabels,
            'revenueData' => $revenueData,
            'bookingLabels' => $bookingLabels,
            'bookingData' => $bookingData,
            'revenueByLocation' => collect([]),
        ]);
    }
}
