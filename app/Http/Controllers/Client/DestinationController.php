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
        $allDestinations = Destination::where('is_approved', true)->orderBy('name')->get();

        $destination = $slug
            ? Destination::where('is_approved', true)->where('slug', $slug)->firstOrFail()
            : null;

        $query = Homestay::query()->where('is_approved', true)
            ->with(['promotion', 'images' => fn($q) => $q->orderByDesc('is_primary')->orderBy('id')])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->latest();

        if ($destination) {
            $query->where('destination_id', $destination->id);
        }

        return view('clients.destinations', [
            'destination' => $destination,
            'allDestinations' => $allDestinations,
            'homestays' => $query->paginate(15),
        ]);
    }
}

