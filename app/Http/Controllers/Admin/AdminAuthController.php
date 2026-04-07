<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        if (session('admin_verified')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function loginAccount(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Email hoặc mật khẩu không đúng.')
                ->with('tab', 'account');
        }

        if ($user->role !== 'admin') {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Tài khoản này không có quyền Admin.')
                ->with('tab', 'account');
        }

        $user->tokens()->delete();
        $token = $user->createToken('admin_token')->plainTextToken;

        $this->saveSession($request, $token, [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'login_method' => 'account',
        ]);

        return redirect()->route('admin.dashboard');
    }

    public function loginKey(Request $request)
    {
        $request->validate([
            'secret_key' => 'required|string',
        ]);

        if ($request->secret_key !== env('ADMIN_SECRET_KEY')) {
            return back()
                ->with('error', 'Secret key không đúng.')
                ->with('tab', 'key');
        }

        $this->saveSession($request, null, [
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
        $token = session('admin_token');

        if ($token) {
            PersonalAccessToken::findToken($token)?->delete();
        }

        $request->session()->forget(['admin_verified', 'admin_token', 'admin_user']);

        return redirect()->route('admin.login')
            ->with('success', 'Đăng xuất thành công.');
    }

    private function saveSession(Request $request, ?string $token, array $user): void
    {
        $request->session()->regenerate();
        $request->session()->put('admin_verified', true);
        $request->session()->put('admin_token', $token);
        $request->session()->put('admin_user', $user);
    }
}
