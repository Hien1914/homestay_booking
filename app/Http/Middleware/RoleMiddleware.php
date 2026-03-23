<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['success' => false, 'message' => 'Chưa đăng nhập'], 401);
        }

        if (! $user->is_active) {
            return response()->json(['success' => false, 'message' => 'Tài khoản đã bị khóa'], 403);
        }

        if (! in_array($user->role, $roles)) {
            return response()->json(['success' => false, 'message' => 'Không có quyền thực hiện'], 403);
        }

        return $next($request);
    }
}
