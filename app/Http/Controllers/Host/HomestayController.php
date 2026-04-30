<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\Homestay;
use App\Models\HomestayImage;
use App\Models\HomestayRoom;
use App\Models\Amenity;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HomestayController extends Controller
{
    public function index()
    {
        $hostId = Auth::id();
        $homestays = Homestay::where('owner_id', $hostId)
            ->with(['images' => fn($q) => $q->orderByDesc('is_primary'), 'destination'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->latest()
            ->paginate(15);

        $stats = [
            'total' => Homestay::where('owner_id', $hostId)->count(),
            'available' => Homestay::where('owner_id', $hostId)->where('is_approved', true)->count(),
            'avgRating' => Homestay::where('owner_id', $hostId)
                ->join('reviews', 'homestays.id', '=', 'reviews.homestay_id')
                ->avg('reviews.rating') ?? 0,
        ];

        return view('host.homestays.index', compact('homestays', 'stats'));
    }

    public function create()
    {
        $amenities = Amenity::orderBy('name')->get();
        $destinations = Destination::orderBy('name')->get();
        $promotions = Auth::user()->promotions()->where('is_active', true)->get();
        return view('host.homestays.form', compact('amenities', 'destinations', 'promotions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|min:5|max:255',
            'slug' => 'nullable|string',
            'description' => 'required|min:100',
            'destination_id' => 'nullable|exists:destinations,id',
            'destination_custom' => 'nullable|string|max:255',
            'province' => 'required|string|max:100',
            'ward' => 'nullable|string|max:100',
            'address' => 'required|string|max:255',
            'price_per_night' => 'required|integer|min:0',
            'max_guests' => 'required|integer|min:1',
            'cover_image' => 'required|image|max:5120',
            'room_images.*' => 'nullable|image|max:5120',
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:amenities,id',
            'rooms' => 'nullable|array',
            'rooms.*' => 'nullable|integer|min:0',
            'promotion_id' => 'nullable|exists:promotions,id',
        ]);

        DB::beginTransaction();
        try {
            // Xử lý điểm đến (chọn hoặc tạo mới)
            $destination_id = $validated['destination_id'];
            if (!$destination_id && $validated['destination_custom']) {
                $dest = Destination::create([
                    'name' => $validated['destination_custom'],
                    'is_approved' => false,
                    'created_by' => Auth::id(),
                ]);
                $destination_id = $dest->id;
            }

            $homestay = Homestay::create([
                'owner_id' => Auth::id(),
                'title' => $validated['title'],
                'slug' => $validated['slug'] ?? Str::slug($validated['title']),
                'description' => $validated['description'],
                'destination_id' => $destination_id,
                'province' => $validated['province'],
                'ward' => $validated['ward'] ?? null,
                'address' => $validated['address'],
                'price_per_night' => $validated['price_per_night'],
                'max_guests' => $validated['max_guests'],
                'promotion_id' => $validated['promotion_id'] ?? null,
                'status' => 'available',
                'is_approved' => false,
            ]);

            // Ảnh đại diện
            if ($request->hasFile('cover_image')) {
                $coverPath = $request->file('cover_image')->store("homestays/{$homestay->id}", 'public');
                HomestayImage::create([
                    'homestay_id' => $homestay->id,
                    'image_url' => $coverPath,
                    'is_primary' => true,
                ]);
            }

            // Ảnh phòng
            if ($request->hasFile('room_images')) {
                foreach ($request->file('room_images') as $image) {
                    $path = $image->store("homestays/{$homestay->id}", 'public');
                    HomestayImage::create([
                        'homestay_id' => $homestay->id,
                        'image_url' => $path,
                        'is_primary' => false,
                    ]);
                }
            }

            // Gắn amenities
            if (!empty($validated['amenities'])) {
                $homestay->amenities()->attach($validated['amenities']);
            }

            // Lưu số phòng (feature_type là string tự do)
            if (!empty($request->input('rooms'))) {
                foreach ($request->input('rooms') as $type => $qty) {
                    if ($qty > 0) {
                        HomestayRoom::create([
                            'homestay_id' => $homestay->id,
                            'feature_type' => $type,
                            'quantity' => $qty,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('host.homestays.index')
                ->with('success', 'Tạo homestay thành công, chờ admin duyệt.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    public function edit(Homestay $homestay)
    {
        if ($homestay->owner_id !== Auth::id()) abort(403);
        $homestay->load(['images', 'amenities', 'rooms']);
        $amenities = Amenity::orderBy('name')->get();
        $destinations = Destination::orderBy('name')->get();
        $promotions = Auth::user()->promotions()->where('is_active', true)->get();
        return view('host.homestays.form', compact('homestay', 'amenities', 'destinations', 'promotions'));
    }

    public function update(Request $request, Homestay $homestay)
    {
        if ($homestay->owner_id !== Auth::id()) abort(403);

        $validated = $request->validate([
            'title' => 'required|min:5|max:255',
            'slug' => 'nullable|string',
            'description' => 'required|min:100',
            'destination_id' => 'nullable|exists:destinations,id',
            'destination_custom' => 'nullable|string|max:255',
            'province' => 'required|string|max:100',
            'ward' => 'nullable|string|max:100',
            'address' => 'required|string|max:255',
            'price_per_night' => 'required|integer|min:0',
            'max_guests' => 'required|integer|min:1',
            'cover_image' => 'nullable|image|max:5120',
            'room_images.*' => 'nullable|image|max:5120',
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:amenities,id',
            'rooms' => 'nullable|array',
            'rooms.*' => 'nullable|integer|min:0',
            'delete_images' => 'nullable|array',
            'promotion_id' => 'nullable|exists:promotions,id',
        ]);

        DB::beginTransaction();
        try {
            // Xử lý điểm đến
            $destination_id = $validated['destination_id'];
            if (!$destination_id && $validated['destination_custom']) {
                $dest = Destination::create([
                    'name' => $validated['destination_custom'],
                    'is_approved' => false,
                    'created_by' => Auth::id(),
                ]);
                $destination_id = $dest->id;
            }

            $homestay->update([
                'title' => $validated['title'],
                'slug' => $validated['slug'] ?? Str::slug($validated['title']),
                'description' => $validated['description'],
                'destination_id' => $destination_id,
                'province' => $validated['province'],
                'ward' => $validated['ward'] ?? null,
                'address' => $validated['address'],
                'price_per_night' => $validated['price_per_night'],
                'max_guests' => $validated['max_guests'],
                'promotion_id' => $validated['promotion_id'] ?? null,
                'is_approved' => false,
            ]);

            // Xóa ảnh được chọn
            if (!empty($validated['delete_images'])) {
                $imagesToDelete = HomestayImage::whereIn('id', $validated['delete_images'])
                    ->where('homestay_id', $homestay->id)
                    ->get();
                foreach ($imagesToDelete as $image) {
                    Storage::disk('public')->delete($image->image_url);
                    $image->delete();
                }
            }

            // Cập nhật ảnh đại diện
            if ($request->hasFile('cover_image')) {
                $oldCover = $homestay->images()->where('is_primary', true)->first();
                if ($oldCover) {
                    Storage::disk('public')->delete($oldCover->image_url);
                    $oldCover->delete();
                }
                $coverPath = $request->file('cover_image')->store("homestays/{$homestay->id}", 'public');
                HomestayImage::create([
                    'homestay_id' => $homestay->id,
                    'image_url' => $coverPath,
                    'is_primary' => true,
                ]);
            }

            // Thêm ảnh phòng mới
            if ($request->hasFile('room_images')) {
                foreach ($request->file('room_images') as $image) {
                    $path = $image->store("homestays/{$homestay->id}", 'public');
                    HomestayImage::create([
                        'homestay_id' => $homestay->id,
                        'image_url' => $path,
                        'is_primary' => false,
                    ]);
                }
            }

            // Đồng bộ amenities
            $homestay->amenities()->detach();
            if (!empty($validated['amenities'])) {
                $homestay->amenities()->attach($validated['amenities']);
            }

            // Cập nhật số phòng
            $homestay->rooms()->delete();
            if (!empty($request->input('rooms'))) {
                foreach ($request->input('rooms') as $type => $qty) {
                    if ($qty > 0) {
                        HomestayRoom::create([
                            'homestay_id' => $homestay->id,
                            'feature_type' => $type,
                            'quantity' => $qty,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('host.homestays.index')
                ->with('success', 'Cập nhật thành công, chờ duyệt lại.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    public function destroy(Homestay $homestay)
    {
        if ($homestay->owner_id !== Auth::id()) abort(403);
        $hasActive = $homestay->bookings()->whereIn('status', ['confirmed', 'checked_in', 'completed'])->exists();
        if ($hasActive) {
            return back()->with('error', 'Không thể xóa homestay đã có đặt phòng.');
        }
        foreach ($homestay->images as $img) {
            Storage::disk('public')->delete($img->image_url);
        }
        $homestay->delete();
        return redirect()->route('host.homestays.index')->with('success', 'Đã xóa homestay.');
    }
}