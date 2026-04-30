<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $hostId = Auth::id();
        $query = User::whereHas('bookings.homestay', fn($q) => $q->where('owner_id', $hostId))
            ->withCount(['bookings' => fn($q) => $q->whereHas('homestay', fn($sq) => $sq->where('owner_id', $hostId))])
            ->withSum(['bookings as total_spent' => fn($q) => $q->whereHas('homestay', fn($sq) => $sq->where('owner_id', $hostId))->where('status', 'completed')], 'host_earn');

        if ($search = $request->search) {
            $query->where(fn($q) => $q->where('full_name', 'like', "%$search%")->orWhere('email', 'like', "%$search%"));
        }

        $customers = $query->latest()->paginate(15);
        return view('host.customers', compact('customers'));
    }
}