<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Bạn chưa đăng nhập'], 401)
                : redirect()->route('login.page');
        }

        if (!in_array(Auth::user()->role, $roles)) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Bạn không có quyền truy cập'], 403)
                : abort(403, 'Bạn không có quyền truy cập');
        }

        return $next($request);
    }
}
