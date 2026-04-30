<nav class="nav">
  <div class="container-setting nav-container">

    @php($isHomePage = request()->routeIs('home'))
    @php($isCategoryPage = request()->routeIs('destinations.show'))

    <a href="/" class="nav-logo">
      @if($isHomePage)
        <x-logo-icon-home width="24" height="24" aria-hidden="true" />
      @else
        <x-logo-icon width="24" height="24" aria-hidden="true" />
      @endif
      <span>NestAway</span>
    </a>

    <button type="button" class="nav-toggle" id="nav-toggle" aria-label="Mở menu"
      aria-expanded="false" aria-controls="nav-menu">
      <i class="fa-solid fa-bars" aria-hidden="true"></i>
      <i class="fa-solid fa-xmark" aria-hidden="true"></i>
    </button>

    <div class="nav-menu" id="nav-menu">
      <div class="nav-menu-header">
        <a href="/" class="nav-logo">
          @if($isHomePage)
            <x-logo-icon-home width="24" height="24" aria-hidden="true" />
          @else
            <x-logo-icon width="24" height="24" aria-hidden="true" />
          @endif
          <span>NestAway</span>
        </a>
        <button type="button" class="nav-close" id="nav-close" aria-label="Đóng menu">
          <i class="fa-solid fa-xmark" aria-hidden="true"></i>
        </button>
      </div>

      <ul class="nav-list">
        <li class="nav-item">
          <a href="{{ route('home') }}" class="nav-link {{ $isHomePage ? 'active' : '' }}">Trang chủ</a>
        </li>
        <li class="nav-item">
          <a href="{{ route('pages.search') }}" class="nav-link {{ request()->routeIs('pages.search') ? 'active' : '' }}">Tìm phòng</a>
        </li>
        <li class="nav-item">
          <a href="{{ route('blog.index') }}" class="nav-link {{ request()->routeIs('blog.*') ? 'active' : '' }}">Blog</a>
        </li>
        @auth
          @if(Auth::user()->isHost())
            <li class="nav-item">
              <a href="{{ route('host.dashboard') }}" class="nav-link nav-link-host {{ request()->routeIs('host.*') ? 'active' : '' }}">Chuyển sang trang Host
              </a>
            </li>
          @elseif(Auth::user()->isUser())
            <li class="nav-item">
              <a href="{{ route('apply-host.create') }}" class="nav-link nav-link-partner {{ request()->routeIs('apply-host.*') ? 'active' : '' }}">Trở thành đối tác
              </a>
            </li>
          @endif
        @else
          <li class="nav-item">
            <a href="{{ route('apply-host.create') }}" class="nav-link nav-link-partner">Trở thành đối tác</a>
          </li>
        @endauth
      </ul>

      <div class="nav-actions">
        @if(Auth::check())
          <div class="nav-user">
            <div class="nav-user-menu" id="nav-user-menu">
              <button type="button" class="nav-user-btn" id="nav-user-btn">
                <span class="nav-user-avatar">{{ mb_strtoupper(mb_substr(Auth::user()->full_name, 0, 1)) }}</span>
                <span class="nav-user-name">{{ Auth::user()->full_name }}</span>
                <i class="fa-solid fa-chevron-down"></i>
              </button>
              <div class="nav-user-dropdown" id="nav-user-dropdown">
                <a href="{{ route('profile.page') }}" class="nav-user-item">
                  <i class="fa-solid fa-user"></i>
                  <span>Thông tin cá nhân</span>
                </a>
                <a href="{{ route('favorite.index') }}" class="nav-user-item">
                  <i class="fa-solid fa-heart"></i>
                  <span>Phòng yêu thích</span>
                </a>
                <a href="{{ route('bookings.history') }}" class="nav-user-item">
                  <i class="fa-solid fa-clock-rotate-left"></i>
                  <span>Lịch sử đặt phòng</span>
                </a>

                <div class="nav-divider"></div>
                <form action="{{ route('auth.logout') }}" method="POST" style="margin: 0;">
                  @csrf
                  <button type="submit" class="nav-user-item nav-logout">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Đăng xuất</span>
                  </button>
                </form>
              </div>
            </div>
          </div>
        @else
          <a href="{{ route('login.page') }}" class="nav-btn-login">Đăng nhập</a>
        @endif
      </div>
    </div>
  </div>
  <div class="nav-backdrop" id="nav-backdrop" hidden aria-hidden="true"></div>
</nav>

<script>
  // Nav menu toggle (mobile drawer)
  (function () {
    var toggle = document.getElementById('nav-toggle');
    var menu = document.getElementById('nav-menu');
    var backdrop = document.getElementById('nav-backdrop');
    var closeBtn = document.getElementById('nav-close');
    
    if (!toggle || !menu) return;

    function setOpen(open) {
      menu.classList.toggle('is-open', open);
      toggle.classList.toggle('is-open', open);
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      if (backdrop) {
        backdrop.hidden = !open;
        backdrop.setAttribute('aria-hidden', !open);
      }
    }

    toggle.addEventListener('click', function () {
      setOpen(!menu.classList.contains('is-open'));
    });

    if (closeBtn) {
      closeBtn.addEventListener('click', function () {
        setOpen(false);
      });
    }

    if (backdrop) {
      backdrop.addEventListener('click', function () {
        setOpen(false);
      });
    }

    // Handle submenu toggle on mobile
    var navItems = menu.querySelectorAll('.nav-item');
    navItems.forEach(function (item) {
      var link = item.querySelector('a');
      var submenu = item.querySelector('.nav-submenu');

      if (submenu && link) {
        link.addEventListener('click', function (e) {
          // On mobile, prevent default and toggle submenu
          if (window.matchMedia('(max-width: 992px)').matches) {
            e.preventDefault();
            item.classList.toggle('is-open');
          }
        });
      }
    });

    // Close menu on link click only if it doesn't have submenu
    menu.querySelectorAll('a').forEach(function (el) {
      var item = el.closest('.nav-item');
      var submenu = item ? item.querySelector('.nav-submenu') : null;

      if (!submenu) {
        el.addEventListener('click', function () {
          if (window.matchMedia('(max-width: 992px)').matches) {
            setOpen(false);
          }
        });
      }
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') setOpen(false);
    });
  })();

  // User dropdown toggle
  (function () {
    var userMenu = document.getElementById('nav-user-menu');
    var userBtn = document.getElementById('nav-user-btn');

    if (!userMenu || !userBtn) return;

    userBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      userMenu.classList.toggle('is-open');
    });

    document.addEventListener('click', function (e) {
      if (!userMenu.contains(e.target)) {
        userMenu.classList.remove('is-open');
      }
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') {
        userMenu.classList.remove('is-open');
      }
    });
  })();
</script>
