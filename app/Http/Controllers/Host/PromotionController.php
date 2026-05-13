<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Http\Requests\Host\PromotionRequest;
use App\Models\Promotion;
use Illuminate\Support\Facades\Auth;

class PromotionController extends Controller
{
    public function index()
    {
        $hostId = Auth::id();
        $query = Promotion::where('host_id', $hostId);

        $totalPromotions = (clone $query)->count();
        $today = now()->toDateString();

        $activePromotions = (clone $query)->where('is_active', true)
            ->where('end_date', '>=', $today)
            ->count();

        $inactivePromotions = (clone $query)->where('is_active', false)
            ->where('end_date', '>=', $today)
            ->count();

        $expiredPromotions = (clone $query)->where('end_date', '<', $today)->count();

        $promotions = $query->latest()->paginate(15);

        return view('host.promotions.index', compact('promotions', 'totalPromotions', 'activePromotions', 'inactivePromotions', 'expiredPromotions'));
    }

    public function create()
    {
        return view('host.promotions.form', ['promotion' => null]);
    }

    public function store(PromotionRequest $request)
    {
        $validated = $request->validated();
        $validated['host_id'] = Auth::id();
        Promotion::create($validated);
        return redirect()->route('host.promotions.index')->with('success', 'Thêm khuyến mãi thành công.');
    }

    public function edit(Promotion $promotion)
    {
        if ($promotion->host_id !== Auth::id()) abort(403);
        return view('host.promotions.form', compact('promotion'));
    }

    public function update(PromotionRequest $request, Promotion $promotion)
    {
        if ($promotion->host_id !== Auth::id()) abort(403);
        $validated = $request->validated();
        $promotion->update($validated);
        return redirect()->route('host.promotions.index')->with('success', 'Cập nhật khuyến mãi thành công.');
    }

    public function destroy(Promotion $promotion)
    {
        if ($promotion->host_id !== Auth::id()) abort(403);
        $promotion->delete();
        return back()->with('success', 'Đã xóa khuyến mãi.');
    }
}
