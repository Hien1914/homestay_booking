<nav class="navbar">
  <div class="container-fluid navbar-container">
    @php($isHomePage = request()->routeIs('home'))
    @php($isDestinationPage = request()->routeIs('destinations.show'))
    <a href="/" class="logo">
      @if($isHomePage)
        <x-logo-icon-home width="24" height="24" aria-hidden="true" />
      @else
        <x-logo-icon width="24" height="24" aria-hidden="true" />
      @endif
      <span>NestAway</span>
    </a>

    <button type="button" class="mobile-menu-toggle" id="navbar-toggle" aria-label="Mở menu điều hướng" aria-expanded="false" aria-controls="navbar-menu">
      <i class="fa-solid fa-bars icon-bars" aria-hidden="true"></i>
      <i class="fa-solid fa-xmark icon-close" aria-hidden="true"></i>
    </button>

    <div class="navbar-menu" id="navbar-menu">
      <div class="navbar-menu-header">
        <a href="/" class="navbar-menu-brand">
          @if($isHomePage)
            <x-logo-icon-home width="24" height="24" aria-hidden="true" />
          @else
            <x-logo-icon width="24" height="24" aria-hidden="true" />
          @endif
          <span>NestAway</span>
        </a>
        <button type="button" class="navbar-menu-close" id="navbar-menu-close" aria-label="Đóng menu">
          <i class="fa-solid fa-xmark" aria-hidden="true"></i>
        </button>
      </div>
      <div class="navbar-menu-body">
        <ul class="nav-links">
          <li><a href="{{ route('home') }}" class="nav-link-item {{ $isHomePage ? 'active' : '' }}">Trang chủ</a></li>
          <li class="nav-submenu-item" aria-label="Menu điểm đến">
            <a href="{{ route('destinations.show', ['category' => 'ven-bien']) }}" class="nav-link-item nav-submenu-trigger {{ $isDestinationPage ? 'active' : '' }}" aria-label="Mở menu điểm đến">Điểm đến</a>
            <ul class="submenu-list" aria-label="Các lựa chọn điểm đến">
              <li><a href="{{ route('destinations.show', ['category' => 'ven-bien']) }}" class="submenu-link">Ven biển</a></li>
              <li><a href="{{ route('destinations.show', ['category' => 'mien-nui']) }}" class="submenu-link">Miền núi</a></li>
              <li><a href="{{ route('destinations.show', ['category' => 'thanh-thi']) }}" class="submenu-link">Thành thị</a></li>
              <li><a href="{{ route('destinations.show', ['category' => 'ho-song']) }}" class="submenu-link">Hồ & sông</a></li>
              <li><a href="{{ route('destinations.show', ['category' => 'sang-trong']) }}" class="submenu-link">Sang trọng</a></li>
            </ul>
          </li>
          <li><a href="{{ route('rooms.search') }}" class="nav-link-item {{ request()->routeIs('rooms.search') ? 'active' : '' }}">Tìm phòng</a></li>
          <li><a href="{{ route('blog.index') }}" class="nav-link-item {{ request()->routeIs('blog.*') ? 'active' : '' }}">Blog</a></li>
        </ul>
      </div>
      <div class="navbar-actions">
        @auth
          {{-- Đã đăng nhập --}}
          <div class="navbar-user-actions">
            <button type="button" class="notification-btn" id="notification-btn" aria-label="Thông báo">
              <i class="fa-solid fa-bell"></i>
              <span class="notification-badge" id="notification-badge" hidden>0</span>
            </button>
            
            <div class="user-dropdown" id="user-dropdown">
              <button type="button" class="user-dropdown-trigger" id="user-dropdown-trigger">
                <span class="user-avatar">{{ strtoupper(substr(Auth::user()->full_name, 0, 1)) }}</span>
                <span class="user-name">{{ Auth::user()->full_name }}</span>
                <i class="fa-solid fa-chevron-down user-dropdown-icon"></i>
              </button>
              <div class="user-dropdown-menu" id="user-dropdown-menu">
                <a href="{{ route('profile.page') }}" class="user-dropdown-item">
                  <i class="fa-solid fa-user"></i>
                  <span>Thông tin cá nhân</span>
                </a>
                <a href="{{ route('wishlist.index') }}" class="user-dropdown-item">
                  <i class="fa-solid fa-heart"></i>
                  <span>Phòng yêu thích</span>
                </a>
                <a href="{{ route('bookings.history') }}" class="user-dropdown-item">
                  <i class="fa-solid fa-clock-rotate-left"></i>
                  <span>Lịch sử đặt phòng</span>
                </a>
                <div class="user-dropdown-divider"></div>
                <form action="{{ route('auth.logout') }}" method="POST" style="margin: 0;">
                  @csrf
                  <button type="submit" class="user-dropdown-item user-dropdown-logout">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Đăng xuất</span>
                  </button>
                </form>
              </div>
            </div>
          </div>
        @else
          {{-- Chưa đăng nhập --}}
          <a href="{{ route('login.page') }}" class="btn-login">Đăng nhập</a>
        @endauth
      </div>
    </div>
  </div>
  <div class="navbar-backdrop" id="navbar-backdrop" hidden aria-hidden="true"></div>
</nav>

<script>
(function () {
  var nav = document.querySelector('.navbar');
  var toggle = document.getElementById('navbar-toggle');
  var menu = document.getElementById('navbar-menu');
  var backdrop = document.getElementById('navbar-backdrop');
  var menuClose = document.getElementById('navbar-menu-close');
  if (!toggle || !menu || !nav) return;

  var mq = window.matchMedia('(max-width: 768px)');

  function setOpen(open) {
    menu.classList.toggle('is-open', open);
    toggle.classList.toggle('is-open', open);
    nav.classList.toggle('navbar--menu-open', open);
    document.body.classList.toggle('navbar-drawer-open', open);
    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    toggle.setAttribute('aria-label', open ? 'Đóng menu điều hướng' : 'Mở menu điều hướng');
    if (backdrop) {
      backdrop.hidden = !open;
      backdrop.setAttribute('aria-hidden', open ? 'false' : 'true');
    }
  }

  toggle.addEventListener('click', function () {
    setOpen(!menu.classList.contains('is-open'));
  });

  if (menuClose) {
    menuClose.addEventListener('click', function () {
      setOpen(false);
    });
  }

  if (backdrop) {
    backdrop.addEventListener('click', function () {
      setOpen(false);
    });
  }

  menu.querySelectorAll('a, .btn-login').forEach(function (el) {
    el.addEventListener('click', function () {
      if (mq.matches) setOpen(false);
    });
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') setOpen(false);
  });
})();

// User dropdown toggle
(function () {
  var userDropdown = document.getElementById('user-dropdown');
  var userDropdownTrigger = document.getElementById('user-dropdown-trigger');
  
  if (!userDropdown || !userDropdownTrigger) return;

  userDropdownTrigger.addEventListener('click', function (e) {
    e.stopPropagation();
    userDropdown.classList.toggle('is-open');
  });

  document.addEventListener('click', function (e) {
    if (!userDropdown.contains(e.target)) {
      userDropdown.classList.remove('is-open');
    }
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      userDropdown.classList.remove('is-open');
    }
  });
})();
</script>
