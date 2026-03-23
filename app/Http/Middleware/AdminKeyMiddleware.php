<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminKeyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->get('admin_verified')) {
            return redirect()
                ->route('admin.login')
                ->with('error', 'Vui lòng đăng nhập bằng secret key để tiếp tục.');
        }

        return $next($request);
    }
}
