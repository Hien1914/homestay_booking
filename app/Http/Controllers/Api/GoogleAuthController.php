<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirect(): JsonResponse
    {
        $clientId = config('services.google.client_id');
        $redirectUri = config('services.google.redirect');
        
        $params = http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'access_type' => 'offline',
            'prompt' => 'select_account',
        ]);

        $url = 'https://accounts.google.com/o/oauth2/v2/auth?' . $params;

        return response()->json([
            'success' => true,
            'data' => ['url' => $url],
        ]);
    }

    /**
     * Handle Google OAuth callback
     */
    public function callback(Request $request): mixed
    {
        $code = $request->query('code');
        $error = $request->query('error');

        if ($error) {
            return $this->redirectWithError('Đăng nhập Google bị hủy');
        }

        if (!$code) {
            return $this->redirectWithError('Không nhận được mã xác thực từ Google');
        }

        try {
            // Exchange code for access token
            $tokenResponse = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'client_id' => config('services.google.client_id'),
                'client_secret' => config('services.google.client_secret'),
                'redirect_uri' => config('services.google.redirect'),
                'grant_type' => 'authorization_code',
                'code' => $code,
            ]);

            if (!$tokenResponse->successful()) {
                return $this->redirectWithError('Không thể xác thực với Google');
            }

            $tokenData = $tokenResponse->json();
            $accessToken = $tokenData['access_token'] ?? null;

            if (!$accessToken) {
                return $this->redirectWithError('Không nhận được access token');
            }

            // Get user info from Google
            $userResponse = Http::withToken($accessToken)
                ->get('https://www.googleapis.com/oauth2/v2/userinfo');

            if (!$userResponse->successful()) {
                return $this->redirectWithError('Không thể lấy thông tin người dùng từ Google');
            }

            $googleUser = $userResponse->json();

            // Find or create user
            $user = $this->findOrCreateUser($googleUser);

            if (!$user->is_active) {
                return $this->redirectWithError('Tài khoản đã bị khóa. Vui lòng liên hệ admin.');
            }

            // Create auth token
            $user->tokens()->delete();
            $token = $user->createToken('auth_token')->plainTextToken;

            // Redirect to frontend with token
            return $this->redirectWithSuccess($token, $user);

        } catch (\Exception $e) {
            return $this->redirectWithError('Đã có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Handle Google login from frontend (using Google Sign-In button)
     */
    public function loginWithToken(Request $request): JsonResponse
    {
        $request->validate([
            'access_token' => 'required|string',
        ]);

        try {
            // Verify token with Google
            $userResponse = Http::withToken($request->access_token)
                ->get('https://www.googleapis.com/oauth2/v2/userinfo');

            if (!$userResponse->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token Google không hợp lệ',
                ], 401);
            }

            $googleUser = $userResponse->json();

            // Find or create user
            $user = $this->findOrCreateUser($googleUser);

            if (!$user->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tài khoản đã bị khóa. Vui lòng liên hệ admin.',
                ], 403);
            }

            // Create auth token
            $user->tokens()->delete();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Đăng nhập thành công',
                'data' => [
                    'user' => new UserResource($user),
                    'token' => $token,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã có lỗi xảy ra: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Find existing user or create new one from Google data
     */
    private function findOrCreateUser(array $googleUser): User
    {
        $googleId = $googleUser['id'];
        $email = $googleUser['email'];
        $name = $googleUser['name'] ?? $googleUser['given_name'] ?? 'Người dùng';
        $avatar = $googleUser['picture'] ?? null;

        // First, try to find by google_id
        $user = User::where('google_id', $googleId)->first();

        if ($user) {
            // Update avatar if changed
            if ($avatar && $user->avatar !== $avatar) {
                $user->update(['avatar' => $avatar]);
            }
            return $user;
        }

        // Then, try to find by email (existing account, link Google)
        $user = User::where('email', $email)->first();

        if ($user) {
            // Link Google account to existing user
            $user->update([
                'google_id' => $googleId,
                'avatar' => $avatar ?: $user->avatar,
                'email_verified_at' => $user->email_verified_at ?: now(),
            ]);
            return $user;
        }

        // Create new user
        return User::create([
            'name' => $name,
            'email' => $email,
            'google_id' => $googleId,
            'avatar' => $avatar,
            'password' => null, // No password for Google-only accounts
            'email_verified_at' => now(), // Google emails are verified
        ]);
    }

    /**
     * Redirect to frontend with error
     */
    private function redirectWithError(string $message): \Illuminate\Http\RedirectResponse
    {
        $params = http_build_query([
            'error' => $message,
        ]);
        return redirect('/dang-nhap?' . $params);
    }

    /**
     * Redirect to frontend with success
     */
    private function redirectWithSuccess(string $token, User $user): \Illuminate\Http\RedirectResponse
    {
        $params = http_build_query([
            'token' => $token,
            'user' => json_encode([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar,
                'role' => $user->role,
            ]),
        ]);
        return redirect('/dang-nhap?' . $params);
    }
}
