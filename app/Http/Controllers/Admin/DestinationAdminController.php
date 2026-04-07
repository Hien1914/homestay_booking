<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DestinationAdminController extends Controller
{
    public function index(): View
    {
        $destinations = Destination::withCount('homestays')
            ->latest()
            ->get();

        $stats = [
            'total' => $destinations->count(),
        ];

        return view('admin.destinations.index', compact('destinations', 'stats'));
    }

    public function create(): View
    {
        return view('admin.destinations.form', ['destination' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:destinations',
            'description' => 'nullable|string',
            'image' => 'required|image|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('destinations', 'public');
        }

        Destination::create($validated);

        return redirect()->route('admin.destinations')->with('success', 'Tạo điểm đến thành công!');
    }

    public function edit(Destination $destination): View
    {
        return view('admin.destinations.form', compact('destination'));
    }

    public function update(Request $request, Destination $destination): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:destinations,slug,' . $destination->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('image')) {
            if ($destination->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($destination->image);
            }
            $validated['image'] = $request->file('image')->store('destinations', 'public');
        }

        $destination->update($validated);

        return redirect()->route('admin.destinations')->with('success', 'Cập nhật điểm đến thành công!');
    }

    public function destroy(Destination $destination): RedirectResponse
    {
        if ($destination->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($destination->image);
        }

        $destination->delete();

        return redirect()->route('admin.destinations')->with('success', 'Xóa điểm đến thành công!');
    }
}
