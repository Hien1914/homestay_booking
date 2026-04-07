<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AmenityAdminController extends Controller
{
    public function index(): View
    {
        $amenities = Amenity::withCount('homestays')
            ->latest()
            ->get();

        $stats = [
            'total' => $amenities->count(),
        ];

        return view('admin.amenities.index', compact('amenities', 'stats'));
    }

    public function create(): View
    {
        return view('admin.amenities.form', ['amenity' => null]);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:amenities',
        ]);

        $amenity = Amenity::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tạo tiện nghi thành công!',
                'amenity' => $amenity,
            ]);
        }

        return redirect()->route('admin.amenities')->with('success', 'Tạo tiện nghi thành công!');
    }

    public function edit(Amenity $amenity): View
    {
        return view('admin.amenities.form', compact('amenity'));
    }

    public function update(Request $request, Amenity $amenity): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:amenities,name,' . $amenity->id,
        ]);

        $amenity->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật tiện nghi thành công!',
                'amenity' => $amenity,
            ]);
        }

        return redirect()->route('admin.amenities')->with('success', 'Cập nhật tiện nghi thành công!');
    }

    public function destroy(Request $request, Amenity $amenity): JsonResponse|RedirectResponse
    {
        $amenity->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Xóa tiện nghi thành công!',
            ]);
        }

        return redirect()->route('admin.amenities')->with('success', 'Xóa tiện nghi thành công!');
    }
}

