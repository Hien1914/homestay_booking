<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->get('admin_verified')) {
            return redirect()->route('admin.login')->with('error', 'Vui lòng đăng nhập bằng secret key để tiếp tục.');
        }

        $adminUser = $request->session()->get('admin_user');
        if (!is_array($adminUser) || ($adminUser['role'] ?? null) !== 'admin') {
            $request->session()->forget(['admin_verified', 'admin_user']);
            return redirect()->route('admin.login')->with('error', 'Phiên đăng nhập admin không hợp lệ.');
        }

        return $next($request);
    }
}
