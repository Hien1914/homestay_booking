<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Throwable;

class GoogleAuthController extends Controller
{
    private function redirectByRole(string $role)
    {
        return match ($role) {
            default => redirect()->route('home'),
        };
    }

    /**
     * Chuyển hướng đến Google để xác thực
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Xử lý callback từ Google sau khi xác thực thành công
     */
    public function callback(Request $request)
    {
        try {
            // Lấy thông tin người dùng từ Google
            $driver = Socialite::driver('google');

            try {
                $googleUser = $driver->user();
            } catch (Throwable $stateException) {
                // Fallback khi có lỗi session state
                $googleUser = $driver->stateless()->user();
            }

            // Kiểm tra email có tồn tại không
            if (empty($googleUser->email)) {
                throw new \RuntimeException('Google account does not provide an email address.');
            }

            // Tìm user theo google_id hoặc email
            $user = User::where('google_id', $googleUser->id)->first();
            if (!$user) {
                $user = User::where('email', $googleUser->email)->first();
            }

            if ($user) {
                // Cập nhật thông tin user hiện tại
                $updateData = [
                    'auth_provider' => 'google',
                    'google_id' => $googleUser->id,
                ];

                if (empty($user->avatar_url) && !empty($googleUser->avatar)) {
                    $updateData['avatar_url'] = $googleUser->avatar;
                }

                if (!empty($googleUser->name) && empty($user->full_name)) {
                    $updateData['full_name'] = $googleUser->name;
                }

                $user->update($updateData);
            } else {
                // Tạo user mới
                $user = User::create([
                    'full_name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'auth_provider' => 'google',
                    'google_id' => $googleUser->id,
                    'avatar_url' => $googleUser->avatar,
                    'password' => Hash::make(Str::random(24)),
                    'role' => 'user',
                ]);
            }

            // Đăng nhập user
            Auth::login($user);
            $request->session()->regenerate();

            if ($user->role === 'admin') {
                Auth::logout();
                $request->session()->regenerate();

                return redirect()
                    ->route('admin.login')
                    ->with('error', 'Tài khoản Admin chỉ đăng nhập tại trang quản trị bằng Admin key.');
            }

            return $this->redirectByRole((string) $user->role)->with('success', 'Đăng nhập thành công!');
        } catch (Throwable $e) {
            // Ghi log lỗi và chuyển hướng về trang đăng nhập với thông báo lỗi
            Log::error('Google authentication failed.', [
                'message' => $e->getMessage(),
            ]);

            return redirect()->route('login.page')->with('error', 'Đăng nhập Google thất bại! Vui lòng thử lại.');
        }
    }
}
