<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use Illuminate\Http\Request;

class AdminPayoutController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');

        $query = Payout::with('host')->latest();

        if ($status && in_array($status, ['pending', 'completed', 'failed'])) {
            $query->where('status', $status);
        }

        if ($fromDate) {
            $query->whereDate('created_at', '>=', $fromDate);
        }
        if ($toDate) {
            $query->whereDate('created_at', '<=', $toDate);
        }

        $payouts = $query->paginate(15)->withQueryString();
        
        $stats = [
            'total' => (clone $query)->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'completed' => (clone $query)->where('status', 'completed')->count(),
            'failed' => (clone $query)->where('status', 'failed')->count(),
        ];

        return view('admin.payouts', compact('payouts', 'stats', 'status', 'fromDate', 'toDate'));
    }

    public function approve(Payout $payout)
    {
        if ($payout->status !== 'pending') {
            return back()->with('error', 'Yêu cầu đã được xử lý.');
        }
        $payout->update(['status' => 'completed']);
        return back()->with('success', 'Đã duyệt rút tiền #' . $payout->id);
    }

    public function reject(Payout $payout)
    {
        if ($payout->status !== 'pending') {
            return back()->with('error', 'Yêu cầu đã được xử lý.');
        }
        $payout->update(['status' => 'failed']);
        return back()->with('success', 'Đã từ chối rút tiền #' . $payout->id);
    }
}
