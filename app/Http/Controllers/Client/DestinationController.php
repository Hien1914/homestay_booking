<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Homestay;
use Illuminate\Contracts\View\View;

class DestinationController extends Controller
{
    public function show(?string $slug = null): View
    {
        $allDestinations = Destination::orderBy('name')->get();

        $destination = $slug
            ? Destination::where('slug', $slug)->firstOrFail()
            : $allDestinations->first();

        abort_if(!$destination, 404);

        $homestays = Homestay::query()
            ->where('province', $destination->province)
            ->where('status', 'published')
            ->with(['images' => fn ($q) => $q->orderByDesc('is_primary')->orderBy('id')])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->latest()
            ->get();

        return view('clients.destinations.index', [
            'destination'     => $destination,
            'allDestinations' => $allDestinations,
            'homestays'       => $homestays,
        ]);
    }
}
