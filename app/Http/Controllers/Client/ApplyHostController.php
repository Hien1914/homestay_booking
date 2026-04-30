<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Host\ApplyHostRequest;
use App\Models\HostApplication;
use Illuminate\Support\Facades\Auth;

class ApplyHostController extends Controller
{
    public function create()
    {
        $user = Auth::user();

        // Nếu đã là host → redirect về host dashboard
        if ($user->isHost()) {
            return redirect()->route('host.dashboard')
                ->with('info', 'Bạn đã là chủ nhà (host).');
        }

        // Kiểm tra đã có đơn pending
        $pendingApp = HostApplication::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        return view('clients.apply-host', compact('pendingApp'));
    }

    public function store(ApplyHostRequest $request)
    {
        $user = Auth::user();

        // Đã là host
        if ($user->isHost()) {
            return redirect()->route('host.dashboard')
                ->with('info', 'Bạn đã là chủ nhà.');
        }

        // Kiểm tra đơn pending hoặc approved
        $existing = HostApplication::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existing) {
            return back()->with('error', 'Bạn đã gửi đơn đăng ký rồi. Vui lòng chờ admin xem xét.');
        }

        HostApplication::create([
            'user_id'     => $user->id,
            'id_card'     => $request->id_card,
            'bank_acc'    => $request->bank_acc,
            'bank_name'   => $request->bank_name,
            'bank_holder' => $request->bank_holder,
            'status'      => 'pending',
        ]);

        return redirect()->route('home')
            ->with('success', 'Yêu cầu đã gửi thành công, admin sẽ xem xét và phản hồi.');
    }
}