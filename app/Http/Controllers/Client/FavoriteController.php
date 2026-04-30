<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Homestay;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = Favorite::where('user_id', Auth::id())
            ->with('homestay')
            ->paginate(15);
        return view('clients.favorites', compact('favorites'));
    }

    public function toggle(Homestay $homestay)
    {
        $fav = Favorite::where('user_id', Auth::id())->where('homestay_id', $homestay->id)->first();
        if ($fav) {
            $fav->delete();
            return response()->json(['active' => false]);
        } else {
            Favorite::create(['user_id' => Auth::id(), 'homestay_id' => $homestay->id]);
            return response()->json(['active' => true]);
        }
    }
}
