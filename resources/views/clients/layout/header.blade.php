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

<script src="{{ asset('js/clients/header.js') }}"></script>

