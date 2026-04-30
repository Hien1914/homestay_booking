<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DestinationController extends Controller
{
    public function index()
    {
        $destinations = Destination::where('created_by', Auth::id())->latest()->paginate(15);
        return view('host.destinations.index', compact('destinations'));
    }

    public function create()
    {
        return view('host.destinations.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:destinations,name',
            'description' => 'nullable|string|max:500',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('destinations', 'public');
        }

        Destination::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
            'created_by' => Auth::id(),
            'is_approved' => false,
        ]);

        return redirect()->route('host.destinations')->with('success', 'Điểm đến được tạo thành công, chờ admin duyệt.');
    }

    public function edit(Destination $destination)
    {
        if ($destination->created_by !== Auth::id()) abort(403);
        return view('host.destinations.form', compact('destination'));
    }

    public function update(Request $request, Destination $destination)
    {
        if ($destination->created_by !== Auth::id()) abort(403);

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:destinations,name,' . $destination->id,
            'description' => 'nullable|string|max:500',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_approved' => false,
        ];

        if ($request->hasFile('image')) {
            if ($destination->image) {
                Storage::disk('public')->delete($destination->image);
            }
            $data['image'] = $request->file('image')->store('destinations', 'public');
        }

        $destination->update($data);

        return redirect()->route('host.destinations')->with('success', 'Cập nhật thành công, chờ admin duyệt.');
    }

    public function destroy(Destination $destination)
    {
        if ($destination->created_by !== Auth::id()) abort(403);

        if ($destination->image) {
            Storage::disk('public')->delete($destination->image);
        }

        $destination->delete();
        return back()->with('success', 'Xóa điểm đến thành công.');
    }
}

