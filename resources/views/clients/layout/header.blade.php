<nav class="navbar">
  <div class="container-fluid navbar-container">
    <a href="/" class="logo">
      <svg width="24" height="24" viewBox="0 0 26 26" fill="none" aria-hidden="true">
        <path d="M13 2L3 9V22H10V16H16V22H23V9L13 2Z" stroke="currentColor" stroke-width="1.5" fill="none"/>
      </svg>
      <span>NestAway</span>
    </a>

    <button type="button" class="mobile-menu-toggle" id="navbar-toggle" aria-label="Mở menu điều hướng" aria-expanded="false" aria-controls="navbar-menu">
      <i class="fa-solid fa-bars icon-bars" aria-hidden="true"></i>
      <i class="fa-solid fa-xmark icon-close" aria-hidden="true"></i>
    </button>

    <div class="navbar-menu" id="navbar-menu">
      <div class="navbar-menu-header">
        <a href="/" class="navbar-menu-brand">
          <svg width="24" height="24" viewBox="0 0 26 26" fill="none" aria-hidden="true">
            <path d="M13 2L3 9V22H10V16H16V22H23V9L13 2Z" stroke="currentColor" stroke-width="1.5" fill="none"/>
          </svg>
          <span>NestAway</span>
        </a>
        <button type="button" class="navbar-menu-close" id="navbar-menu-close" aria-label="Đóng menu">
          <i class="fa-solid fa-xmark" aria-hidden="true"></i>
        </button>
      </div>
      <div class="navbar-menu-body">
        <ul class="nav-links">
          <li><a href="{{ route('home') }}" class="nav-link-item">Trang chủ</a></li>
          <li class="nav-submenu-item" aria-label="Menu điểm đến">
            <a href="{{ route('destinations.show', ['category' => 'ven-bien']) }}" class="nav-link-item nav-submenu-trigger" aria-label="Mở menu điểm đến">Điểm đến</a>
            <ul class="submenu-list" aria-label="Các lựa chọn điểm đến">
              <li><a href="{{ route('destinations.show', ['category' => 'ven-bien']) }}" class="submenu-link">Ven biển</a></li>
              <li><a href="{{ route('destinations.show', ['category' => 'mien-nui']) }}" class="submenu-link">Miền núi</a></li>
              <li><a href="{{ route('destinations.show', ['category' => 'thanh-thi']) }}" class="submenu-link">Thành thị</a></li>
              <li><a href="{{ route('destinations.show', ['category' => 'ho-song']) }}" class="submenu-link">Hồ & sông</a></li>
              <li><a href="{{ route('destinations.show', ['category' => 'sang-trong']) }}" class="submenu-link">Sang trọng</a></li>
            </ul>
          </li>
          <li><a href="{{ route('rooms.search') }}" class="nav-link-item {{ request()->routeIs('rooms.search') ? 'active' : '' }}">Tìm phòng</a></li>
          <li><a href="#" class="nav-link-item">Blog</a></li>
        </ul>
      </div>
      <div class="navbar-actions">
        <!-- Guest: Show login button -->
        <a href="{{ route('login.page') }}" class="btn-login" id="btn-login-header">Đăng nhập</a>
        
        <!-- Logged in: Show user dropdown -->
        <div class="user-dropdown" id="user-dropdown" hidden>
          <button type="button" class="user-dropdown-trigger" id="user-dropdown-trigger">
            <span class="user-avatar" id="user-avatar"></span>
            <span class="user-name" id="user-name"></span>
            <i class="fa-solid fa-chevron-down user-dropdown-icon"></i>
          </button>
          <div class="user-dropdown-menu" id="user-dropdown-menu">
            <a href="{{ route('profile.page') }}" class="user-dropdown-item">
              <i class="fa-solid fa-user"></i>
              <span>Thông tin cá nhân</span>
            </a>
            <button type="button" class="user-dropdown-item user-dropdown-logout" id="btn-logout">
              <i class="fa-solid fa-right-from-bracket"></i>
              <span>Đăng xuất</span>
            </button>
          </div>
        </div>
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

// User authentication state handling
(function () {
  var btnLogin = document.getElementById('btn-login-header');
  var userDropdown = document.getElementById('user-dropdown');
  var userDropdownTrigger = document.getElementById('user-dropdown-trigger');
  var userDropdownMenu = document.getElementById('user-dropdown-menu');
  var userAvatar = document.getElementById('user-avatar');
  var userName = document.getElementById('user-name');
  var btnLogout = document.getElementById('btn-logout');

  function getInitials(name) {
    if (!name) return '?';
    var parts = name.trim().split(' ');
    if (parts.length === 1) return parts[0].charAt(0).toUpperCase();
    return (parts[0].charAt(0) + parts[parts.length - 1].charAt(0)).toUpperCase();
  }

  function checkAuthState() {
    var token = localStorage.getItem('auth_token');
    var userStr = localStorage.getItem('auth_user');
    var user = null;
    
    try {
      user = userStr ? JSON.parse(userStr) : null;
    } catch (e) {
      user = null;
    }

    if (token && user) {
      // User is logged in
      if (btnLogin) btnLogin.hidden = true;
      if (userDropdown) {
        userDropdown.hidden = false;
        if (userName) userName.textContent = user.name || 'Người dùng';
        if (userAvatar) {
          if (user.avatar) {
            userAvatar.style.backgroundImage = 'url(' + user.avatar + ')';
            userAvatar.textContent = '';
            userAvatar.classList.add('has-image');
          } else {
            userAvatar.textContent = getInitials(user.name);
            userAvatar.style.backgroundImage = '';
            userAvatar.classList.remove('has-image');
          }
        }
      }
    } else {
      // User is not logged in
      if (btnLogin) btnLogin.hidden = false;
      if (userDropdown) userDropdown.hidden = true;
    }
  }

  // Dropdown toggle
  if (userDropdownTrigger && userDropdownMenu) {
    userDropdownTrigger.addEventListener('click', function (e) {
      e.stopPropagation();
      userDropdown.classList.toggle('is-open');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function (e) {
      if (!userDropdown.contains(e.target)) {
        userDropdown.classList.remove('is-open');
      }
    });

    // Close on Escape
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') {
        userDropdown.classList.remove('is-open');
      }
    });
  }

  // Logout handler
  if (btnLogout) {
    btnLogout.addEventListener('click', function () {
      var token = localStorage.getItem('auth_token');
      
      // Call logout API
      if (token) {
        fetch('/api/auth/logout', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
          }
        }).finally(function () {
          // Clear local storage and reload
          localStorage.removeItem('auth_token');
          localStorage.removeItem('auth_user');
          window.location.href = '/';
        });
      } else {
        localStorage.removeItem('auth_token');
        localStorage.removeItem('auth_user');
        window.location.href = '/';
      }
    });
  }

  // Check auth state on page load
  checkAuthState();

  // Listen for storage changes (login/logout from other tabs)
  window.addEventListener('storage', function (e) {
    if (e.key === 'auth_token' || e.key === 'auth_user') {
      checkAuthState();
    }
  });
})();
</script>
