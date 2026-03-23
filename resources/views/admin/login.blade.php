<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Admin | NestAway</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
<div class="admin-login-page">
    <section class="admin-login-panel">
        <div class="admin-login-card">
            <h2>Đăng nhập quản trị</h2>

            @if(session('error'))
                <div class="admin-login-alert is-error">{{ session('error') }}</div>
            @endif

            @if(session('success'))
                <div class="admin-login-alert is-success">{{ session('success') }}</div>
            @endif

            <form class="admin-login-form" method="POST" action="{{ route('admin.login.key') }}">
                @csrf
                <div>
                    <label class="admin-login-label" for="secret_key">Secret Key quản trị</label>
                    <div class="admin-login-field">
                        <i class="bi bi-shield-lock-fill"></i>
                        <input id="secret_key" type="password" name="secret_key" class="admin-login-input" placeholder="Nhập key từ file .env" autocomplete="off" required>
                    </div>
                </div>

                <button type="submit" class="admin-btn admin-btn-primary">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Vào bảng điều khiển
                </button>
            </form>
        </div>
    </section>
</div>
</body>
</html>
