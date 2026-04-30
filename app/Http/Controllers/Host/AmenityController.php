<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AmenityController extends Controller
{
    public function index()
    {
        $amenities = Amenity::where('created_by', Auth::id())->latest()->paginate(15);
        return view('host.amenities', compact('amenities'));
    }



    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100|unique:amenities']);
        Amenity::create([
            'name' => $request->name,
            'created_by' => Auth::id(),
            'is_approved' => false,
        ]);
        return redirect()->route('host.amenities')->with('success', 'Đã gửi yêu cầu tiện nghi, chờ admin duyệt.');
    }



    public function update(Request $request, Amenity $amenity)
    {
        if ($amenity->created_by !== Auth::id()) abort(403);
        $request->validate(['name' => 'required|string|max:100|unique:amenities,name,' . $amenity->id]);
        $amenity->update(['name' => $request->name, 'is_approved' => false]);
        return redirect()->route('host.amenities')->with('success', 'Đã cập nhật, chờ duyệt lại.');
    }

    public function destroy(Amenity $amenity)
    {
        if ($amenity->created_by !== Auth::id()) abort(403);
        $amenity->delete();
        return back()->with('success', 'Đã xóa.');
    }
}
