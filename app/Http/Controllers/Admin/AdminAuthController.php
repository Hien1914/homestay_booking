<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        if (session('admin_verified')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function loginKey(Request $request)
    {
        $request->validate([
            'secret_key' => 'required|string',
        ]);

        if (!hash_equals((string) env('ADMIN_SECRET_KEY'), (string) $request->secret_key)) {
            return back()
                ->withErrors(['secret_key' => 'Admin key không đúng. Vui lòng nhập lại.'])
                ->withInput();
        }

        $this->saveSession($request, [
            'id' => null,
            'name' => 'Admin',
            'email' => 'key-access',
            'role' => 'admin',
            'login_method' => 'key',
        ]);

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['admin_verified', 'admin_user']);
        $request->session()->regenerate();

        return redirect()->route('admin.login')
            ->with('success', 'Đăng xuất thành công.');
    }

    private function saveSession(Request $request, array $user): void
    {
        $request->session()->regenerate();
        $request->session()->put('admin_verified', true);
        $request->session()->put('admin_user', $user);
    }
}
