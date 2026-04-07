<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }
        return view('clients.auth.login');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }
        return view('clients.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|min:2|max:100',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'required|string|regex:/^[0-9]{10,11}$/|unique:users,phone',
            'password' => [
                'required',
                'string',
                'min:8',
                'max:32',
                'regex:/[A-Z]/',      // ít nhất 1 chữ in hoa
                'regex:/[a-z]/',      // ít nhất 1 chữ thường
                'regex:/[0-9]/',      // ít nhất 1 số
                'regex:/[@$!%*?&#]/', // ít nhất 1 ký tự đặc biệt
                'confirmed',
            ],
        ], [
            'name.required'      => 'Vui lòng nhập họ tên',
            'email.required'     => 'Vui lòng nhập email',
            'email.unique'       => 'Email này đã được đăng ký',
            'phone.required'     => 'Vui lòng nhập số điện thoại',
            'phone.regex'        => 'Số điện thoại không hợp lệ (10-11 số)',
            'phone.unique'       => 'Số điện thoại này đã được đăng ký',
            'password.min'       => 'Mật khẩu phải có ít nhất 8 ký tự',
            'password.max'       => 'Mật khẩu tối đa 32 ký tự',
            'password.regex'     => 'Mật khẩu phải có chữ hoa, chữ thường, số và ký tự đặc biệt (@$!%*?&#)',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
        ]);

        $user = User::create([
            'full_name'         => $request->name,
            'email'             => $request->email,
            'phone'             => preg_replace('/\s+/', '', $request->phone),
            'password_hash'     => Hash::make($request->password),
            'role'              => 'guest',
            'is_email_verified' => false,
        ]);

        Auth::login($user);

        return $this->redirectByRole($user)->with('success', 'Đăng ký thành công!');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required'    => 'Vui lòng nhập email',
            'password.required' => 'Vui lòng nhập mật khẩu',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password_hash)) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Email hoặc mật khẩu không đúng');
        }

        Auth::login($user);
        $request->session()->regenerate();

        return $this->redirectByRole($user);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    protected function redirectByRole($user)
    {
        return redirect()->route('home');
    }
}
