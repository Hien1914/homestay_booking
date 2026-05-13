<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use Illuminate\Http\Request;

class AdminAmenityController extends Controller
{
    public function index(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        $query = Amenity::query();

        if ($fromDate) {
            $query->whereDate('created_at', '>=', $fromDate);
        }
        if ($toDate) {
            $query->whereDate('created_at', '<=', $toDate);
        }

        $stats = [
            'total' => (clone $query)->count(),
            'pending' => (clone $query)->where('is_approved', false)->count(),
        ];

        $amenities = $query->withCount('homestays')->latest()->paginate(15);
        return view('admin.amenities.index', compact('amenities', 'stats', 'fromDate', 'toDate'));
    }

    public function approve(Amenity $amenity)
    {
        $amenity->update(['is_approved' => true]);
        return back()->with('success', 'Đã duyệt tiện nghi: ' . $amenity->name);
    }

    public function store(Request $request)
    {
        $name = trim($request->name);
        
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($name) {
                    if (Amenity::whereRaw('LOWER(name) = ?', [strtolower($name)])->exists()) {
                        $fail('Tiện nghi này đã tồn tại.');
                    }
                },
            ],
        ]);

        $amenity = Amenity::create(['name' => $name, 'is_approved' => true]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Thêm tiện nghi thành công.',
                'amenity' => $amenity
            ]);
        }

        return redirect()->route('admin.amenities')->with('success', 'Thêm thành công.');
    }

    public function update(Request $request, Amenity $amenity)
    {
        $name = trim($request->name);
        
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($name, $amenity) {
                    if (Amenity::whereRaw('LOWER(name) = ?', [strtolower($name)])
                        ->where('id', '!=', $amenity->id)
                        ->exists()) {
                        $fail('Tiện nghi này đã tồn tại.');
                    }
                },
            ],
        ]);

        $amenity->update(['name' => $name]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật tiện nghi thành công.',
                'amenity' => $amenity
            ]);
        }

        return redirect()->route('admin.amenities')->with('success', 'Cập nhật thành công.');
    }

    public function destroy(Amenity $amenity)
    {
        $amenity->delete();
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa tiện nghi thành công.',
            ]);
        }
        return back()->with('success', 'Đã xóa.');
    }
}
