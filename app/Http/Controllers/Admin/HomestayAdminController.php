<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Destination;
use App\Models\Homestay;
use App\Models\HomestayImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HomestayAdminController extends Controller
{
    public function index(): View
    {
        $homestays = Homestay::with(['images'])
            ->withCount('bookings')
            ->withAvg('reviews', 'rating')
            ->latest()
            ->get();

        $stats = [
            'total' => $homestays->count(),
            'available' => $homestays->where('status', 'available')->count(),
            'avgRating' => $homestays->avg('reviews_avg_rating') ?? 0,
        ];

        return view('admin.homestays.index', compact('homestays', 'stats'));
    }

    public function create(): View
    {
        return view('admin.homestays.form', [
            'homestay' => null,
            'amenities' => Amenity::orderBy('name')->get(),
            'destinations' => Destination::orderBy('name')->get(),
            'generatedRoomCode' => Homestay::generateRoomCode(),
            'homestayTypes' => $this->getHomestayTypes(),
        ]);
    }

    public function edit(Homestay $homestay): View
    {
        $homestay->load(['images', 'amenities']);

        return view('admin.homestays.form', [
            'homestay' => $homestay,
            'amenities' => Amenity::orderBy('name')->get(),
            'destinations' => Destination::orderBy('name')->get(),
            'generatedRoomCode' => $homestay->room_code,
            'homestayTypes' => $this->getHomestayTypes(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|min:5|max:255',
            'type' => 'required|string|max:100',
            'description' => 'required|string|min:100',
            'destination_id' => 'nullable|exists:destinations,id',
            'province' => 'required|string|max:100',
            'ward' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'price_per_night' => 'required|numeric|min:0',
            'max_guests' => 'required|integer|min:1|max:50',
            'cover_image' => 'required|image|max:5120',
            'room_images.*' => 'nullable|image|max:5120',
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:amenities,id',
        ]);

        DB::beginTransaction();
        try {
            $homestay = Homestay::create([
                'title' => $validated['title'],
                'type' => $validated['type'],
                'description' => $validated['description'],
                'destination_id' => $validated['destination_id'] ?? null,
                'province' => $validated['province'],
                'ward' => $validated['ward'],
                'address' => $validated['address'],
                'price_per_night' => $validated['price_per_night'],
                'max_guests' => $validated['max_guests'],
                'status' => 'available',
            ]);

            // Upload cover image
            if ($request->hasFile('cover_image')) {
                $path = $request->file('cover_image')->store('homestays/' . $homestay->id, 'public');
                HomestayImage::create([
                    'homestay_id' => $homestay->id,
                    'image_url' => $path,
                    'is_primary' => true,
                ]);
            }

            // Upload room images
            if ($request->hasFile('room_images')) {
                foreach ($request->file('room_images') as $image) {
                    $path = $image->store('homestays/' . $homestay->id, 'public');
                    HomestayImage::create([
                        'homestay_id' => $homestay->id,
                        'image_url' => $path,
                        'is_primary' => false,
                    ]);
                }
            }

            // Sync amenities
            if (!empty($validated['amenities'])) {
                $homestay->amenities()->attach($validated['amenities']);
            }

            DB::commit();
            return redirect()->route('admin.homestays')->with('success', 'Đã tạo homestay mới thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Homestay $homestay): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|min:5|max:255',
            'type' => 'required|string|max:100',
            'description' => 'required|string|min:100',
            'destination_id' => 'nullable|exists:destinations,id',
            'province' => 'required|string|max:100',
            'ward' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'price_per_night' => 'required|numeric|min:0',
            'max_guests' => 'required|integer|min:1|max:50',
            'cover_image' => 'nullable|image|max:5120',
            'room_images.*' => 'nullable|image|max:5120',
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:amenities,id',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'exists:homestay_images,id',
        ]);

        DB::beginTransaction();
        try {
            $homestay->update([
                'title' => $validated['title'],
                'type' => $validated['type'],
                'description' => $validated['description'],
                'destination_id' => $validated['destination_id'] ?? null,
                'province' => $validated['province'],
                'ward' => $validated['ward'],
                'address' => $validated['address'],
                'price_per_night' => $validated['price_per_night'],
                'max_guests' => $validated['max_guests'],
            ]);

            // Delete selected images
            if (!empty($validated['delete_images'])) {
                $imagesToDelete = HomestayImage::whereIn('id', $validated['delete_images'])
                    ->where('homestay_id', $homestay->id)
                    ->get();

                foreach ($imagesToDelete as $image) {
                    Storage::disk('public')->delete($image->image_url);
                    $image->delete();
                }
            }

            // Upload new cover image
            if ($request->hasFile('cover_image')) {
                $oldPrimary = $homestay->images()->where('is_primary', true)->first();
                if ($oldPrimary) {
                    Storage::disk('public')->delete($oldPrimary->image_url);
                    $oldPrimary->delete();
                }

                $path = $request->file('cover_image')->store('homestays/' . $homestay->id, 'public');
                HomestayImage::create([
                    'homestay_id' => $homestay->id,
                    'image_url' => $path,
                    'is_primary' => true,
                ]);
            }

            // Upload new room images
            if ($request->hasFile('room_images')) {
                foreach ($request->file('room_images') as $image) {
                    $path = $image->store('homestays/' . $homestay->id, 'public');
                    HomestayImage::create([
                        'homestay_id' => $homestay->id,
                        'image_url' => $path,
                        'is_primary' => false,
                    ]);
                }
            }

            // Sync amenities
            $homestay->amenities()->sync($validated['amenities'] ?? []);

            DB::commit();
            return redirect()->route('admin.homestays')->with('success', 'Đã cập nhật homestay thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function destroy(Homestay $homestay): RedirectResponse
    {
        DB::beginTransaction();
        try {
            foreach ($homestay->images as $image) {
                Storage::disk('public')->delete($image->image_url);
            }
            $homestay->delete();

            DB::commit();
            return redirect()->route('admin.homestays')->with('success', 'Đã xóa homestay thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function calendar(Homestay $homestay): View
    {
        $homestay->load(['bookings.guest']);

        $bookings = $homestay->bookings()
            ->with('guest')
            ->where('check_out', '>=', now()->subMonth())
            ->orderBy('check_in')
            ->get()
            ->map(fn($booking) => [
                'id' => $booking->id,
                'title' => $booking->guest->full_name ?? 'Khách',
                'start' => $booking->check_in->format('Y-m-d'),
                'end' => $booking->check_out->format('Y-m-d'),
                'status' => $booking->status,
                'color' => $this->getBookingColor($booking->status),
            ]);

        return view('admin.homestays.calendar', compact('homestay', 'bookings'));
    }

    public function uploadEditorImage(Request $request)
    {
        $request->validate(['upload' => 'required|image|max:5120']);
        $path = $request->file('upload')->store('homestay-descriptions', 'public');

        return response()->json(['url' => asset('storage/' . $path)]);
    }

    private function getHomestayTypes(): array
    {
        return [
            'villa' => 'Villa',
            'apartment' => 'Căn hộ',
            'house' => 'Nhà nguyên căn',
            'room' => 'Phòng riêng',
            'bungalow' => 'Bungalow',
            'resort' => 'Resort',
            'farmstay' => 'Farmstay',
            'container' => 'Container',
            'treehouse' => 'Nhà trên cây',
        ];
    }

    private function getBookingColor(string $status): string
    {
        return match ($status) {
            'confirmed' => '#28a745',
            'pending' => '#ffc107',
            'cancelled' => '#dc3545',
            'completed' => '#6c757d',
            default => '#007bff',
        };
    }
}