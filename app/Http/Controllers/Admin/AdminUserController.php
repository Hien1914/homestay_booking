<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HostApplication;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function hostApplications(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        $query = HostApplication::query()->with('user');

        if ($fromDate) {
            $query->whereDate('created_at', '>=', $fromDate);
        }
        if ($toDate) {
            $query->whereDate('created_at', '<=', $toDate);
        }

        $applications = $query->latest()->paginate(15);
        return view('admin.host-applications', compact('applications', 'fromDate', 'toDate'));
    }

    public function approveHost(HostApplication $application)
    {
        $application->user->update(['role' => 'host']);
        $application->update(['status' => 'approved']);
        return back()->with('success', 'Đã duyệt và nâng cấp thành host.');
    }

    public function rejectHost(HostApplication $application)
    {
        $application->update(['status' => 'rejected']);
        return back()->with('success', 'Đã từ chối yêu cầu.');
    }
}
