@extends('clients.layout.app')

@section('title', 'Đăng ký')

@section('content')
<style>
    @import url('{{ asset('css/clients/login.css') }}');
</style>

<section class="auth-page section-py">
    <div class="container-setting">
        <div class="auth-card">
            @if(session('error'))
                <div class="auth-message auth-message--error" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('auth.register') }}" method="POST" class="auth-form d-block" novalidate>
                @csrf
                <h2 class="auth-title text-center text-uppercase fw-bold">Đăng ký tài khoản</h2>

                <label class="auth-label" for="register-name">Họ và tên</label>
                <input id="register-name" name="name" type="text" class="auth-input" 
                       placeholder="Nguyễn Văn A" autocomplete="name" 
                       value="{{ old('name') }}" required>
                @error('name')
                    <small style="color: #b42318; font-size: 0.8rem;">{{ $message }}</small>
                @enderror

                <label class="auth-label" for="register-email">Email</label>
                <input id="register-email" name="email" type="email" class="auth-input" 
                       placeholder="you@example.com" autocomplete="email" 
                       value="{{ old('email') }}" required>
                @error('email')
                    <small style="color: #b42318; font-size: 0.8rem;">{{ $message }}</small>
                @enderror

                <label class="auth-label" for="register-phone">Số điện thoại</label>
                <input id="register-phone" name="phone" type="tel" class="auth-input" 
                       placeholder="0912345678" autocomplete="tel" 
                       value="{{ old('phone') }}" required>
                @error('phone')
                    <small style="color: #b42318; font-size: 0.8rem;">{{ $message }}</small>
                @enderror

                <label class="auth-label" for="register-password">Mật khẩu</label>
                <input id="register-password" name="password" type="password" class="auth-input" 
                       placeholder="Tối thiểu 8 ký tự" autocomplete="new-password" required>
                <small style="color: #6c757d; font-size: 0.75rem; display: block; margin-top: 0.25rem;">
                    Yêu cầu: 8-32 ký tự, có chữ hoa, chữ thường, số và ký tự đặc biệt (@$!%*?&#)
                </small>
                @error('password')
                    <small style="color: #b42318; font-size: 0.8rem;">{{ $message }}</small>
                @enderror

                <label class="auth-label" for="register-password-confirm">Xác nhận mật khẩu</label>
                <input id="register-password-confirm" name="password_confirmation" type="password" 
                       class="auth-input" placeholder="Nhập lại mật khẩu" 
                       autocomplete="new-password" required>

                <button type="submit" class="auth-btn auth-btn--primary" style="margin-top: 1rem;">Đăng ký</button>

                <p class="auth-switch">
                    Đã có tài khoản?
                    <a href="{{ route('login.page') }}" class="auth-switch-btn">Đăng nhập</a>
                </p>
            </form>
        </div>
    </div>
</section>
@endsection
