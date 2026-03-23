<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Quản trị') | NestAway Admin</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @stack('styles')
</head>
<body class="admin-shell-body">
@if(!session('admin_verified'))
    <script>window.location.href = '{{ route('admin.login') }}';</script>
@endif

<div class="admin-shell">
    @include('admin.layout.sidebar')

    <main class="admin-main">
        <header class="admin-topbar">
            <div>
                <p class="admin-kicker">@yield('page_kicker', 'NestAway Admin')</p>
                <h1 class="admin-page-title">@yield('page_title', 'Tổng quan hệ thống')</h1>
            </div>

            <div class="admin-topbar-actions">
                @yield('page_actions')
            </div>
        </header>

        <div class="admin-main-content">
            @yield('content')
        </div>
    </main>
</div>

<form id="admin-logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
    @csrf
</form>

<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script>
function adminLogout(event) {
    if (event) event.preventDefault();
    document.getElementById('admin-logout-form').submit();
}

document.addEventListener('click', function (event) {
    var openBtn = event.target.closest('[data-modal-open]');
    if (openBtn) {
        event.preventDefault();
        var modalId = openBtn.getAttribute('data-modal-open');
        var modal = document.getElementById(modalId);
        if (!modal) return;
        var mode = openBtn.getAttribute('data-modal-mode') || 'add';
        var entity = openBtn.getAttribute('data-modal-entity') || '';
        var title = modal.querySelector('[data-modal-dynamic-title]');
        if (title) {
            title.textContent = mode === 'edit' ? 'Chỉnh sửa ' + entity : 'Thêm ' + entity;
        }
        modal.hidden = false;
        document.body.style.overflow = 'hidden';
        return;
    }

    if (event.target.closest('[data-modal-close]')) {
        var currentModal = event.target.closest('.admin-modal');
        if (currentModal) {
            currentModal.hidden = true;
            document.body.style.overflow = '';
        }
    }
});
</script>
@stack('scripts')
</body>
</html>
