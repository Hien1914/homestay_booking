<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private function redirectByRole(string $role)
    {
        return match ($role) {
            'admin' => redirect()->route('admin.login'),
            'host'  => redirect()->route('host.dashboard'),
            default => redirect()->route('home'),
        };
    }

    // Hiển thị form đăng nhập
    public function showLogin()
    {
        if (Auth::check()) {
            $user = Auth::user();
            return $this->redirectByRole((string) $user->role);
        }
        return view('clients.login');
    }

    // Hiển thị form đăng ký
    public function showRegister()
    {
        if (Auth::check()) {
            $user = Auth::user();
            return $this->redirectByRole((string) $user->role);
        }
        return view('clients.register');
    }

    // Xử lý đăng ký
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'gender' => $validated['gender'],
            'birthday' => $validated['birthday'],
            'role' => 'user',
            'auth_provider' => 'local',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')->with('success', 'Đăng ký thành công!');
    }

    // Xử lý đăng nhập
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->role === 'admin') {
                Auth::logout();
                $request->session()->regenerate();

                return redirect()
                    ->route('admin.login')
                    ->with('error', 'Tài khoản Admin chỉ đăng nhập tại trang quản trị bằng Admin key.');
            }

            return $this->redirectByRole((string) $user->role)->with('success', 'Đăng nhập thành công!');
        }

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không đúng.',
        ])->onlyInput('email');
    }

    // Đăng xuất
    public function logout(Request $request)
    {
        Auth::logout();
        // Không dùng invalidate() để tránh làm văng phiên đăng nhập Admin nếu đang mở song song
        $request->session()->regenerate(); 

        return redirect()->route('home')->with('success', 'Đăng xuất thành công.');
    }

    // Cập nhật thông tin cá nhân
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|min:2|max:100',
            'phone' => 'nullable|string|regex:/^[0-9]{10,11}$/|unique:users,phone,' . Auth::id(),
            'bank_name' => 'nullable|string|max:120',
            'bank_account_number' => 'nullable|string|regex:/^[0-9]{6,40}$/',
            'gender' => 'nullable|in:male,female,other',
            'birthday' => 'nullable|date|before:today',
            'avatar_url' => 'nullable|url|max:255',
        ], [
            'full_name.required' => 'Vui lòng nhập họ tên',
            'phone.regex' => 'Số điện thoại không hợp lệ (10-11 số)',
            'phone.unique' => 'Số điện thoại này đã được đăng ký',
            'bank_account_number.regex' => 'Số tài khoản chỉ gồm chữ số (6-40 số).',
            'gender.in' => 'Giới tính không hợp lệ',
            'birthday.before' => 'Ngày sinh phải trước ngày hiện tại',
            'avatar_url.url' => 'Link avatar không hợp lệ',
        ]);

        $user = $request->user();
        $user->update([
            'full_name' => $validated['full_name'],
            'phone' => $validated['phone'] ?? null,
            'bank_name' => $validated['bank_name'] ?? null,
            'bank_account_number' => $validated['bank_account_number'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'birthday' => $validated['birthday'] ?? null,
            'avatar_url' => $validated['avatar_url'] ?? null,
        ]);

        return redirect()
            ->route('profile.page')
            ->with('success', 'Cập nhật thông tin thành công.');
    }
}
