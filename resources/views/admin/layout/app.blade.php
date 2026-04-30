<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    <title>@yield('title', 'Quản trị') | NestAway Admin</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/variable.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/page.css') }}">
    @stack('styles')
</head>
<body>
@if(!session('admin_verified'))
    <script>window.location.href = '{{ route('admin.login') }}';</script>
@endif

<!-- Sidebar Overlay for Mobile -->
<div class="admin-sidebar-overlay" id="sidebarOverlay"></div>

<!-- Hamburger Menu Button -->
<button class="admin-hamburger-btn" id="hamburgerBtn" aria-label="Toggle menu">
    <i class="bi bi-list"></i>
</button>

@include('admin.layout.sidebar')

<main class="admin-main-content">
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof toastr !== 'undefined') {
                    toastr.success('{{ session('success') }}');
                } else {
                    alert('{{ session('success') }}');
                }
            });
        </script>
    @endif
    
    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof toastr !== 'undefined') {
                    toastr.error('{{ session('error') }}');
                } else {
                    alert('{{ session('error') }}');
                }
            });
        </script>
    @endif
    
    @yield('content')
</main>

<form id="admin-logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
    @csrf
</form>

<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script>
// Hamburger Menu Toggle
document.addEventListener('DOMContentLoaded', function() {
    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const sidebar = document.querySelector('.admin-sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    if (hamburgerBtn && sidebar) {
        hamburgerBtn.addEventListener('click', function() {
            sidebar.classList.toggle('is-open');
            hamburgerBtn.classList.toggle('is-open');
            overlay.classList.toggle('is-open');
        });
        
        if (overlay) {
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('is-open');
                hamburgerBtn.classList.remove('is-open');
                overlay.classList.remove('is-open');
            });
        }
        
        const navLinks = sidebar.querySelectorAll('.admin-nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 900) {
                    sidebar.classList.remove('is-open');
                    hamburgerBtn.classList.remove('is-open');
                    overlay.classList.remove('is-open');
                }
            });
        });
    }
});

function adminLogout(event) {
    if (event) event.preventDefault();
    document.getElementById('admin-logout-form').submit();
}
</script>
@stack('scripts')
</body>
</html>

