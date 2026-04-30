<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminDestinationController extends Controller
{
    public function index(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        $query = Destination::query();

        if ($fromDate) {
            $query->whereDate('created_at', '>=', $fromDate);
        }
        if ($toDate) {
            $query->whereDate('created_at', '<=', $toDate);
        }

        $stats = [
            'total' => (clone $query)->count(),
            'pending' => (clone $query)->where('is_approved', false)->count(),
            'approved' => (clone $query)->where('is_approved', true)->count(),
        ];

        $pendingDestinations = (clone $query)->withCount('homestays')
            ->where('is_approved', false)
            ->latest()
            ->paginate(15, ['*'], 'pending_page');

        $approvedDestinations = (clone $query)->withCount('homestays')
            ->where('is_approved', true)
            ->latest()
            ->paginate(15, ['*'], 'approved_page');

        return view('admin.destinations.index', compact('stats', 'pendingDestinations', 'approvedDestinations', 'fromDate', 'toDate'));
    }

    public function approve(Destination $destination)
    {
        $destination->update(['is_approved' => true]);
        return back()->with('success', 'Đã duyệt điểm đến: ' . $destination->name);
    }

    public function create()
    {
        return view('admin.destinations.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|unique:destinations',
            'description' => 'nullable',
            'image' => 'required|image',
        ]);
        $data['slug'] = Str::slug($data['name']);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('destinations', 'public');
        }
        Destination::create($data);
        return redirect()->route('admin.destinations')->with('success', 'Thêm điểm đến thành công.');
    }

    public function edit(Destination $destination)
    {
        return view('admin.destinations.form', compact('destination'));
    }

    public function update(Request $request, Destination $destination)
    {
        $data = $request->validate([
            'name' => 'required|unique:destinations,name,' . $destination->id,
            'description' => 'nullable',
            'image' => 'nullable|image',
        ]);
        $data['slug'] = Str::slug($data['name']);
        if ($request->hasFile('image')) {
            if ($destination->image) Storage::disk('public')->delete($destination->image);
            $data['image'] = $request->file('image')->store('destinations', 'public');
        }
        $destination->update($data);
        return redirect()->route('admin.destinations')->with('success', 'Cập nhật thành công.');
    }

    public function destroy(Destination $destination)
    {
        if ($destination->image) Storage::disk('public')->delete($destination->image);
        $destination->delete();
        return back()->with('success', 'Đã xóa.');
    }
}
