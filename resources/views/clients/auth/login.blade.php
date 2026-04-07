@extends('clients.layout.app')

@section('title', 'Đăng nhập')

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
            
            @if(session('success'))
                <div class="auth-message auth-message--success" role="status">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('auth.login') }}" method="POST" class="auth-form d-block" novalidate>
                @csrf
                <h2 class="auth-title text-center text-uppercase fw-bold">Đăng nhập</h2>

                <label class="auth-label" for="login-email">Email</label>
                <input id="login-email" name="email" type="email" class="auth-input" 
                       placeholder="you@example.com" autocomplete="email" 
                       value="{{ old('email') }}" required>
                @error('email')
                    <small style="color: #b42318; font-size: 0.8rem;">{{ $message }}</small>
                @enderror

                <label class="auth-label" for="login-password">Mật khẩu</label>
                <input id="login-password" name="password" type="password" class="auth-input" 
                       placeholder="••••••••" autocomplete="current-password" required>
                @error('password')
                    <small style="color: #b42318; font-size: 0.8rem;">{{ $message }}</small>
                @enderror

                <div class="auth-row" style="justify-content: flex-end;">
                    <a href="#" class="auth-link">Quên mật khẩu?</a>
                </div>

                <button type="submit" class="auth-btn auth-btn--primary">Đăng nhập</button>

                <p class="auth-switch">
                    Chưa có tài khoản?
                    <a href="{{ route('register.page') }}" class="auth-switch-btn">Đăng ký</a>
                </p>
            </form>
        </div>
    </div>
</section>
@endsection
