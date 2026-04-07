<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDestinationRequest;
use App\Http\Requests\Admin\UpdateDestinationRequest;
use App\Models\Destination;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DestinationController extends Controller
{
    public function index(): View
    {
        $destinations = Destination::query()
            ->withCount('homestays')
            ->latest()
            ->get();

        return view('admin.destinations.index', [
            'destinations' => $destinations,
            'totalDestinations' => $destinations->count(),
            'totalHomestays' => $destinations->sum('homestays_count'),
            'totalViews' => 0, // Add views tracking later
        ]);
    }

    public function create(): View
    {
        return view('admin.destinations.form');
    }

    public function edit(Destination $destination): View
    {
        return view('admin.destinations.form', [
            'destination' => $destination,
        ]);
    }

    public function store(StoreDestinationRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('destinations', 'public');
        }

        Destination::create($data);

        return redirect()
            ->route('admin.destinations')
            ->with('success', 'Da tao diem den moi.');
    }

    public function update(UpdateDestinationRequest $request, Destination $destination): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('destinations', 'public');
        }

        $destination->update($data);

        return redirect()
            ->route('admin.destinations')
            ->with('success', 'Da cap nhat diem den.');
    }

    public function destroy(Destination $destination): RedirectResponse
    {
        $destination->delete();

        return redirect()
            ->route('admin.destinations')
            ->with('success', 'Da xoa diem den.');
    }
}
