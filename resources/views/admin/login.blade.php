<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    <title>Đăng nhập Admin | NestAway</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/variable.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/login.css') }}">
</head>
<body>
<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-header admin-login-header text-center">
                <h4 class="mb-0"><i class="bi bi-shield-lock me-2"></i> Admin Login</h4>
            </div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <form method="POST" action="{{ route('admin.login.key') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="secret_key" class="form-label">Admin Key</label>
                        <input type="password" class="form-control @error('secret_key') is-invalid @enderror"
                               id="secret_key" name="secret_key" required autofocus
                               placeholder="Nhập admin key...">
                        @error('secret_key')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-box-arrow-in-right me-2"></i> Đăng nhập
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-muted text-center">
                <small>Admin key được thiết lập trong file .env</small>
            </div>
        </div>
    </div>
</div>
</body>
</html>
