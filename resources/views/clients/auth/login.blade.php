@extends('clients.layout.app')

@section('title', 'Đăng nhập')

@section('content')
<style>
  @import url('{{ asset('css/clients/login.css') }}');
</style>

<section class="auth-page section-py">
  <div class="container-setting">
    <div class="auth-card">
      <div class="auth-message auth-message--error" id="auth-error" role="alert" hidden></div>
      <div class="auth-message auth-message--success" id="auth-success" role="status" hidden></div>

      <form id="login-form" class="auth-form is-active" novalidate>
        <h2 class="auth-title">Đăng nhập</h2>

        <label class="auth-label" for="login-email">Email</label>
        <input id="login-email" name="email" type="email" class="auth-input" placeholder="you@example.com" autocomplete="email" required>

        <label class="auth-label" for="login-password">Mật khẩu</label>
        <input id="login-password" name="password" type="password" class="auth-input" placeholder="••••••••" autocomplete="current-password" required>

        <div class="auth-row">
          <label class="auth-check">
            <input id="login-remember" name="remember" type="checkbox" value="1">
            <span>Ghi nhớ mật khẩu</span>
          </label>
          <a href="#" class="auth-link">Quên mật khẩu?</a>
        </div>

        <button type="button" class="auth-btn auth-btn--google" id="btn-google-login" aria-label="Đăng nhập bằng Google">
          <svg viewBox="0 0 24 24" class="google-icon" aria-hidden="true">
            <path fill="#4285F4" d="M23.49 12.27c0-.79-.07-1.55-.2-2.27H12v4.3h6.44a5.5 5.5 0 0 1-2.39 3.61v2.99h3.86c2.26-2.08 3.58-5.15 3.58-8.63z"/>
            <path fill="#34A853" d="M12 24c3.24 0 5.95-1.07 7.93-2.89l-3.86-2.99c-1.07.72-2.44 1.15-4.07 1.15-3.13 0-5.78-2.11-6.73-4.95H1.28v3.11A12 12 0 0 0 12 24z"/>
            <path fill="#FBBC05" d="M5.27 14.32A7.2 7.2 0 0 1 4.9 12c0-.8.14-1.58.37-2.32V6.57H1.28A12 12 0 0 0 0 12c0 1.94.47 3.77 1.28 5.43l3.99-3.11z"/>
            <path fill="#EA4335" d="M12 4.77c1.77 0 3.36.61 4.61 1.82l3.45-3.45C17.94 1.08 15.23 0 12 0A12 12 0 0 0 1.28 6.57l3.99 3.11C6.22 6.88 8.87 4.77 12 4.77z"/>
          </svg>
          Đăng nhập bằng Google
        </button>

        <button type="submit" class="auth-btn auth-btn--primary">Đăng nhập</button>

        <p class="auth-switch">
          Chưa có tài khoản?
          <button type="button" class="auth-switch-btn" data-auth-switch="register">Đăng ký</button>
        </p>
      </form>

      <form id="register-form" class="auth-form" novalidate>
        <h2 class="auth-title">Đăng ký</h2>

        <label class="auth-label" for="register-name">Họ và tên</label>
        <input id="register-name" name="name" type="text" class="auth-input" placeholder="Nguyễn Văn A" autocomplete="name" required>

        <label class="auth-label" for="register-email">Email</label>
        <input id="register-email" name="email" type="email" class="auth-input" placeholder="you@example.com" autocomplete="email" required>

        <label class="auth-label" for="register-password">Mật khẩu</label>
        <input id="register-password" name="password" type="password" class="auth-input" placeholder="Tối thiểu 8 ký tự" autocomplete="new-password" required>

        <label class="auth-label" for="register-password-confirm">Xác nhận mật khẩu</label>
        <input id="register-password-confirm" name="password_confirmation" type="password" class="auth-input" placeholder="Nhập lại mật khẩu" autocomplete="new-password" required>

        <button type="button" class="auth-btn auth-btn--google" id="btn-google-register" aria-label="Đăng ký bằng Google">
          <svg viewBox="0 0 24 24" class="google-icon" aria-hidden="true">
            <path fill="#4285F4" d="M23.49 12.27c0-.79-.07-1.55-.2-2.27H12v4.3h6.44a5.5 5.5 0 0 1-2.39 3.61v2.99h3.86c2.26-2.08 3.58-5.15 3.58-8.63z"/>
            <path fill="#34A853" d="M12 24c3.24 0 5.95-1.07 7.93-2.89l-3.86-2.99c-1.07.72-2.44 1.15-4.07 1.15-3.13 0-5.78-2.11-6.73-4.95H1.28v3.11A12 12 0 0 0 12 24z"/>
            <path fill="#FBBC05" d="M5.27 14.32A7.2 7.2 0 0 1 4.9 12c0-.8.14-1.58.37-2.32V6.57H1.28A12 12 0 0 0 0 12c0 1.94.47 3.77 1.28 5.43l3.99-3.11z"/>
            <path fill="#EA4335" d="M12 4.77c1.77 0 3.36.61 4.61 1.82l3.45-3.45C17.94 1.08 15.23 0 12 0A12 12 0 0 0 1.28 6.57l3.99 3.11C6.22 6.88 8.87 4.77 12 4.77z"/>
          </svg>
          Đăng ký bằng Google
        </button>

        <button type="submit" class="auth-btn auth-btn--primary">Đăng ký</button>

        <p class="auth-switch">
          Đã có tài khoản?
          <button type="button" class="auth-switch-btn" data-auth-switch="login">Đăng nhập</button>
        </p>
      </form>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
(function () {
  var loginForm = document.getElementById('login-form');
  var registerForm = document.getElementById('register-form');
  var switchers = document.querySelectorAll('[data-auth-switch]');
  var errorBox = document.getElementById('auth-error');
  var successBox = document.getElementById('auth-success');
  var btnGoogleLogin = document.getElementById('btn-google-login');
  var btnGoogleRegister = document.getElementById('btn-google-register');
  
  if (!loginForm || !registerForm) return;

  function clearMessages() {
    if (errorBox) { errorBox.hidden = true; errorBox.textContent = ''; }
    if (successBox) { successBox.hidden = true; successBox.textContent = ''; }
  }

  function setError(msg) {
    if (successBox) { successBox.hidden = true; successBox.textContent = ''; }
    if (!errorBox) return;
    errorBox.textContent = msg || 'Đã có lỗi xảy ra.';
    errorBox.hidden = false;
  }

  function setSuccess(msg) {
    if (errorBox) { errorBox.hidden = true; errorBox.textContent = ''; }
    if (!successBox) return;
    successBox.textContent = msg || 'Thao tác thành công.';
    successBox.hidden = false;
  }

  function activate(mode) {
    loginForm.classList.toggle('is-active', mode === 'login');
    registerForm.classList.toggle('is-active', mode === 'register');
    clearMessages();
  }

  function saveAuth(payload) {
    var data = payload && payload.data ? payload.data : payload;
    var token = data && data.token ? data.token : null;
    if (!token) return false;
    var user = data && data.user ? data.user : null;
    localStorage.setItem('auth_token', token);
    localStorage.setItem('auth_user', JSON.stringify(user));
    return true;
  }

  // Check for Google OAuth callback params in URL
  function handleGoogleCallback() {
    var params = new URLSearchParams(window.location.search);
    var token = params.get('token');
    var userStr = params.get('user');
    var error = params.get('error');

    if (error) {
      setError(decodeURIComponent(error));
      // Clean URL
      window.history.replaceState({}, document.title, window.location.pathname);
      return;
    }

    if (token && userStr) {
      try {
        var user = JSON.parse(decodeURIComponent(userStr));
        localStorage.setItem('auth_token', token);
        localStorage.setItem('auth_user', JSON.stringify(user));
        // Clean URL and redirect
        window.history.replaceState({}, document.title, window.location.pathname);
        window.location.href = '/';
      } catch (e) {
        setError('Không thể xử lý dữ liệu đăng nhập từ Google.');
      }
    }
  }

  // Google login handler
  function handleGoogleLogin() {
    fetch('/api/auth/google/redirect')
      .then(function (res) { return res.json(); })
      .then(function (data) {
        if (data.success && data.data && data.data.url) {
          window.location.href = data.data.url;
        } else {
          setError('Không thể kết nối với Google. Vui lòng thử lại.');
        }
      })
      .catch(function () {
        setError('Không thể kết nối tới máy chủ.');
      });
  }

  // Check Google callback on page load
  handleGoogleCallback();

  // Google buttons
  if (btnGoogleLogin) {
    btnGoogleLogin.addEventListener('click', handleGoogleLogin);
  }
  if (btnGoogleRegister) {
    btnGoogleRegister.addEventListener('click', handleGoogleLogin);
  }

  switchers.forEach(function (btn) {
    btn.addEventListener('click', function () {
      activate(btn.getAttribute('data-auth-switch'));
    });
  });

  loginForm.addEventListener('submit', function (e) {
    e.preventDefault();
    clearMessages();
    var email = (loginForm.querySelector('input[name="email"]') || {}).value || '';
    var password = (loginForm.querySelector('input[name="password"]') || {}).value || '';
    if (!email || !password) {
      setError('Vui lòng nhập đầy đủ email và mật khẩu.');
      return;
    }

    fetch('/api/auth/login', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email: email, password: password })
    })
      .then(function (res) { return res.json().then(function (data) { return { ok: res.ok, data: data }; }); })
      .then(function (payload) {
        if (!payload.ok || !payload.data || payload.data.success === false) {
          var msg = payload.data && payload.data.message ? payload.data.message : 'Email hoặc mật khẩu không đúng.';
          setError(msg);
          return;
        }
        if (!saveAuth(payload.data)) {
          setError('Đăng nhập thất bại: thiếu token.');
          return;
        }
        var redirect = new URLSearchParams(window.location.search).get('redirect');
        window.location.href = (redirect && redirect.startsWith('/')) ? redirect : '/';
      })
      .catch(function () {
        setError('Không thể kết nối tới máy chủ. Vui lòng thử lại.');
      });
  });

  registerForm.addEventListener('submit', function (e) {
    e.preventDefault();
    clearMessages();
    var name = (registerForm.querySelector('input[name="name"]') || {}).value || '';
    var email = (registerForm.querySelector('input[name="email"]') || {}).value || '';
    var password = (registerForm.querySelector('input[name="password"]') || {}).value || '';
    var confirm = (registerForm.querySelector('input[name="password_confirmation"]') || {}).value || '';

    if (!name || !email || !password || !confirm) {
      setError('Vui lòng nhập đầy đủ thông tin đăng ký.');
      return;
    }
    if (password.length < 8) {
      setError('Mật khẩu phải có ít nhất 8 ký tự.');
      return;
    }
    if (password !== confirm) {
      setError('Xác nhận mật khẩu không khớp.');
      return;
    }

    fetch('/api/auth/register', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        name: name,
        email: email,
        password: password,
        password_confirmation: confirm
      })
    })
      .then(function (res) { return res.json().then(function (data) { return { ok: res.ok, data: data }; }); })
      .then(function (payload) {
        if (!payload.ok || !payload.data || payload.data.success === false) {
          var msg = payload.data && payload.data.message ? payload.data.message : 'Đăng ký chưa thành công.';
          setError(msg);
          return;
        }

        if (saveAuth(payload.data)) {
          var redirect = new URLSearchParams(window.location.search).get('redirect');
          window.location.href = (redirect && redirect.startsWith('/')) ? redirect : '/';
          return;
        }

        setSuccess('Đăng ký thành công. Mời bạn đăng nhập.');
        registerForm.reset();
        activate('login');
      })
      .catch(function () {
        setError('Không thể kết nối tới máy chủ. Vui lòng thử lại.');
      });
  });
})();
</script>
@endpush
