<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::whereHas('homestay', function($q) {
            $q->where('owner_id', Auth::id());
        })->with(['homestay', 'user'])->latest()->paginate(15);
        
        return view('host.reviews', compact('reviews'));
    }
}
