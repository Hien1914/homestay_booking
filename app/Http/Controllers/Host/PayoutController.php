<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PayoutController extends Controller
{
    public function index()
    {
        $payouts = Payout::where('host_id', Auth::id())->latest()->paginate(15);
        return view('host.payouts.index', compact('payouts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:100000',
        ]);

        $hostId = Auth::id();

        // Tính toán số dư khả dụng
        $totalEarnings = \App\Models\Booking::whereHas('homestay', function($q) use ($hostId) {
            $q->where('owner_id', $hostId);
        })->where('status', \App\Models\Booking::STATUS_COMPLETED)->sum('host_earn');

        $totalWithdrawn = Payout::where('host_id', $hostId)->where('status', 'completed')->sum('amount');
        $pendingWithdrawn = Payout::where('host_id', $hostId)->where('status', 'pending')->sum('amount');
        $available = $totalEarnings - $totalWithdrawn - $pendingWithdrawn;

        if ($request->amount > $available) {
            return back()->with('error', 'Số tiền vượt quá số dư khả dụng.');
        }

        Payout::create([
            'host_id' => $hostId,
            'amount' => $request->amount,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Yêu cầu rút tiền đã gửi, chờ admin xử lý.');
    }
}