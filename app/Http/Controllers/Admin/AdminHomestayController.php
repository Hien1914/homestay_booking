<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Homestay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminHomestayController extends Controller
{
    /**
     * Danh sách homestay (có lọc theo trạng thái duyệt)
     */
    public function index(Request $request)
    {
        $query = Homestay::with(['owner', 'destination'])
            ->withCount(['bookings', 'reviews'])
            ->withAvg('reviews', 'rating');

        if ($request->has('approved')) {
            $query->where('is_approved', $request->approved);
        }

        $homestays = $query->latest()->paginate(15);
        $totalHomestays = Homestay::count();
        $sumAverageRatings = Homestay::query()
            ->withAvg('reviews', 'rating')
            ->get()
            ->sum(function ($homestay) {
                return (float) ($homestay->reviews_avg_rating ?? 0);
            });
        $systemAverageRating = $totalHomestays > 0
            ? $sumAverageRatings / $totalHomestays
            : 0;

        $stats = [
            'total' => $totalHomestays,
            'pending_approval' => Homestay::where('is_approved', false)->count(),
            'available' => Homestay::where('status', 'available')->count(),
            'avgRating' => $systemAverageRating,
        ];

        return view('admin.homestays.index', compact('homestays', 'stats'));
    }

    /**
     * Duyệt homestay
     */
    public function approve(Homestay $homestay)
    {
        $homestay->update(['is_approved' => true]);
        // Có thể gửi thông báo cho chủ nhà (nếu tích hợp notification)
        return back()->with('success', 'Đã duyệt homestay: ' . $homestay->title);
    }

    /**
     * Xóa homestay (kèm xóa ảnh vật lý)
     */
    public function destroy(Homestay $homestay)
    {
        // Xóa ảnh trong storage
        foreach ($homestay->images as $image) {
            Storage::disk('public')->delete($image->image_url);
        }
        $homestay->delete();
        return back()->with('success', 'Đã xóa homestay: ' . $homestay->title);
    }
}
