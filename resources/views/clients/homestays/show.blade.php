@extends('clients.layout.app')

@section('title', $homestay['name'] ?? 'Chi tiết homestay')

@section('content')
@php
    $cancelLabels = [
        'flexible' => 'Linh hoạt — Hoàn tiền miễn phí trước 1 ngày nhận phòng',
        'moderate' => 'Trung bình — Hoàn tiền 50% khi hủy trước 5 ngày',
        'strict' => 'Nghiêm ngặt — Chỉ hoàn tiền khi hủy trước 14 ngày',
    ];
    $policy = $homestay['cancellation_policy'] ?? 'moderate';
    $policyText = $cancelLabels[$policy] ?? $cancelLabels['moderate'];
@endphp
<style>
  @import url('{{ asset('css/clients/homestay-detail.css') }}');
</style>

<section class="homestay-detail-page section-py">
  <div class="container-setting">
    {{-- Breadcrumb --}}
    <nav class="homestay-detail-breadcrumb" aria-label="Breadcrumb">
      <ol class="homestay-detail-breadcrumb__list">
        @foreach($breadcrumbs ?? [] as $i => $crumb)
          <li class="homestay-detail-breadcrumb__item">
            @if(isset($crumb['url']) && $i < count($breadcrumbs ?? []) - 1)
              <a href="{{ $crumb['url'] }}" class="homestay-detail-breadcrumb__link">{{ $crumb['label'] }}</a>
            @else
              <span class="homestay-detail-breadcrumb__current" aria-current="page">{{ $crumb['label'] }}</span>
            @endif
            @if($i < count($breadcrumbs ?? []) - 1)
              <i class="fa-solid fa-chevron-right homestay-detail-breadcrumb__sep" aria-hidden="true"></i>
            @endif
          </li>
        @endforeach
      </ol>
    </nav>

    <div class="homestay-detail-success" id="booking-success-msg" role="status" hidden>
      <i class="fa-solid fa-circle-check"></i>
      <span>Đặt phòng thành công! Mã đặt phòng: <strong id="booking-code-display"></strong>. Vui lòng tiến hành thanh toán.</span>
    </div>

    {{-- Hero: Tên, địa chỉ, rating --}}
    <header class="homestay-detail-hero">
      <h1 class="homestay-detail-hero__title">{{ $homestay['name'] }}</h1>
      @if(!empty($homestay['avg_rating']))
        <div class="homestay-detail-hero__stars">
          @for($i = 1; $i <= 5; $i++)
            <i class="fa-solid fa-star homestay-detail-hero__star {{ $i <= round($homestay['avg_rating']) ? 'is-filled' : '' }}"></i>
          @endfor
        </div>
      @endif
      <p class="homestay-detail-hero__location">
        <i class="fa-solid fa-location-dot"></i>
        {{ $homestay['address'] ?? '' }}
        @if(!empty($homestay['province']))
          <span class="homestay-detail-hero__province">— {{ $homestay['province'] }}</span>
        @endif
        <a href="#" class="homestay-detail-hero__map-link">TRÊN BẢN ĐỒ</a>
      </p>
      <div class="homestay-detail-hero__meta">
        <span><i class="fa-solid fa-users"></i> {{ (int) ($homestay['max_guests'] ?? 0) }} khách</span>
        <span><i class="fa-solid fa-door-open"></i> {{ (int) ($homestay['num_bedrooms'] ?? 0) }} phòng ngủ</span>
        <span><i class="fa-solid fa-bed"></i> {{ (int) ($homestay['num_beds'] ?? 0) }} giường</span>
        <span><i class="fa-solid fa-bath"></i> {{ (int) ($homestay['num_bathrooms'] ?? 0) }} phòng tắm</span>
      </div>
    </header>

    {{-- Gallery --}}
    @php $imgs = $homestay['images'] ?? []; @endphp
    <div class="homestay-detail-gallery {{ count($imgs) <= 1 ? 'homestay-detail-gallery--single' : '' }}">
      @if(!empty($imgs))
        <div class="homestay-detail-gallery__main">
          <img src="{{ $imgs[0] }}" alt="{{ $homestay['name'] }}" loading="eager">
        </div>
        @if(isset($imgs[1]))
          <div class="homestay-detail-gallery__thumb">
            <img src="{{ $imgs[1] }}" alt="" loading="lazy">
          </div>
        @endif
        @if(isset($imgs[2]))
          <div class="homestay-detail-gallery__thumb">
            <img src="{{ $imgs[2] }}" alt="" loading="lazy">
          </div>
        @endif
      @endif
    </div>

    {{-- Tab navigation (sticky) --}}
    <nav class="homestay-detail-tabs" id="homestay-tabs">
      <a href="#tong-quan" class="homestay-detail-tab is-active">Tổng quan</a>
      <a href="#mo-ta" class="homestay-detail-tab">Mô tả</a>
      <a href="#chinh-sach" class="homestay-detail-tab">Chính sách</a>
      <a href="#dat-phong" class="homestay-detail-tab">Đặt phòng</a>
      <a href="#danh-gia" class="homestay-detail-tab">Đánh giá</a>
    </nav>

    <div class="homestay-detail-layout">
      <div class="homestay-detail-main">
        <div class="homestay-detail-section homestay-detail-card" id="tong-quan">
          <h2 class="homestay-detail-card__title">Tổng quan — Tiện nghi</h2>
          <div class="homestay-detail-amenities">
            @foreach($homestay['amenities'] ?? [] as $am)
              @if(isset($amenityLabels[$am]))
                <span class="homestay-detail-amenity">{{ $amenityLabels[$am] }}</span>
              @endif
            @endforeach
          </div>
        </div>

        <div class="homestay-detail-section homestay-detail-card" id="mo-ta">
          <h2 class="homestay-detail-card__title">Mô tả</h2>
          <p>{{ $homestay['description'] ?? 'Không có mô tả.' }}</p>
        </div>

        <div class="homestay-detail-section homestay-detail-card" id="chinh-sach">
          <h2 class="homestay-detail-card__title">Thông tin nhận phòng & chính sách</h2>
          <ul class="homestay-detail-info-list">
            <li>
              <i class="fa-solid fa-arrow-right-to-bracket"></i>
              <span><strong>Check-in:</strong> {{ $homestay['check_in_time'] ?? '14:00' }}</span>
            </li>
            <li>
              <i class="fa-solid fa-arrow-right-from-bracket"></i>
              <span><strong>Check-out:</strong> {{ $homestay['check_out_time'] ?? '12:00' }}</span>
            </li>
            <li>
              <i class="fa-solid fa-ban"></i>
              <span><strong>Chính sách hủy:</strong> {{ $policyText }}</span>
            </li>
          </ul>
        </div>

        <div class="homestay-detail-section homestay-detail-card" id="danh-gia">
          <h2 class="homestay-detail-card__title">Đánh giá & bình luận</h2>
          <div class="homestay-detail-reviews-header">
            @if(!empty($homestay['avg_rating']))
              <div class="homestay-detail-reviews-score">
                <i class="fa-solid fa-star"></i>
                <span>{{ number_format((float) $homestay['avg_rating'], 1, ',', '.') }}</span>
              </div>
            @endif
            <span class="homestay-detail-reviews-count">{{ (int) ($homestay['reviews_count'] ?? 0) }} đánh giá</span>
          </div>

          @php $reviews = $homestay['reviews'] ?? []; @endphp
          @if(count($reviews) > 0)
            @foreach($reviews as $r)
              <div class="homestay-detail-review">
                <div class="homestay-detail-review-header">
                  <div class="homestay-detail-review-avatar">{{ mb_substr($r['user_name'] ?? 'K', 0, 1) }}</div>
                  <div class="homestay-detail-review-meta">
                    <div class="homestay-detail-review-name">{{ $r['user_name'] ?? 'Khách' }}</div>
                    <div class="homestay-detail-review-date">{{ $r['date'] ?? '' }}</div>
                  </div>
                  <div class="homestay-detail-review-stars">
                    @for($i = 1; $i <= 5; $i++)
                      <i class="fa-{{ $i <= (int)($r['rating'] ?? 0) ? 'solid' : 'regular' }} fa-star"></i>
                    @endfor
                  </div>
                </div>
                <p class="homestay-detail-review-comment">{{ $r['comment'] ?? '' }}</p>
                @if(!empty($r['admin_reply']))
                  <p class="homestay-detail-review-reply">Quản trị viên: {{ $r['admin_reply'] }}</p>
                @endif
              </div>
            @endforeach
          @else
            <p class="homestay-detail-empty-reviews">Chưa có đánh giá nào. Hãy là người đầu tiên trải nghiệm và đánh giá homestay này.</p>
          @endif
        </div>
      </div>

      <aside class="homestay-detail-sidebar" id="dat-phong">
        {{-- Rating summary card --}}
        @if(!empty($homestay['avg_rating']))
        <div class="homestay-detail-sidebar-card">
          <div class="homestay-detail-sidebar-rating">
            <span class="homestay-detail-sidebar-score">{{ number_format((float) $homestay['avg_rating'], 1, ',', '.') }} Tuyệt vời</span>
            <span class="homestay-detail-sidebar-reviews">{{ (int) ($homestay['reviews_count'] ?? 0) }} bài đánh giá</span>
          </div>
          <a href="#danh-gia" class="homestay-detail-sidebar-link">Đọc mọi bài đánh giá</a>
        </div>
        @endif

        {{-- Check-in/out card --}}
        <div class="homestay-detail-sidebar-card">
          <p class="homestay-detail-sidebar-text"><strong>Nhận phòng:</strong> {{ $homestay['check_in_time'] ?? '14:00' }}</p>
          <p class="homestay-detail-sidebar-text"><strong>Trả phòng:</strong> đến {{ $homestay['check_out_time'] ?? '12:00' }}</p>
        </div>

        {{-- Booking form card --}}
        @php
          $isDemo = !empty($homestay['id']) && !is_numeric($homestay['id']);
          $pricePerNight = (int) ($homestay['price_per_night'] ?? 0);
          $maxGuests = (int) ($homestay['max_guests'] ?? 4);
          $extraGuestFee = 100_000;
          $demoBookingUrl = route('homestay.booking.demo', ['id' => $homestay['id'] ?? '']);
        @endphp
        <div class="homestay-detail-card homestay-detail-card--booking">
          <form id="homestay-booking-form" class="homestay-booking-form">
            <div class="homestay-booking-price">
              <span class="homestay-detail-booking-label">từ</span>
              <span class="homestay-detail-booking-amount">{{ $homestay['price_label'] ?? '0đ' }}</span>
            </div>
            <p class="homestay-detail-booking-hint">Giá mỗi đêm, chưa gồm phí dịch vụ</p>

            <div class="homestay-booking-fields">
              <div class="homestay-booking-row">
                <div class="homestay-booking-field">
                  <label for="check_in">Nhận phòng</label>
                  <input type="date" id="check_in" name="check_in_date" required min="{{ date('Y-m-d') }}">
                </div>
                <div class="homestay-booking-field">
                  <label for="check_out">Trả phòng</label>
                  <input type="date" id="check_out" name="check_out_date" required>
                </div>
              </div>

              <div class="homestay-booking-field">
                <label for="num_guests">Số khách</label>
                <div class="homestay-booking-stepper">
                  <button type="button" class="homestay-booking-stepper-btn" data-delta="-1" aria-label="Giảm">−</button>
                  <input type="number" id="num_guests" name="num_guests" value="1" min="1" max="{{ $maxGuests + 10 }}" readonly>
                  <button type="button" class="homestay-booking-stepper-btn" data-delta="1" aria-label="Tăng">+</button>
                </div>
                <span class="homestay-booking-stepper-hint">Tiêu chuẩn {{ $maxGuests }} khách. Vượt quá: +{{ number_format($extraGuestFee, 0, ',', '.') }}đ/người/đêm</span>
              </div>

              <div class="homestay-booking-field">
                <label for="special_requests">Ghi chú <span class="text-muted">(tùy chọn)</span></label>
                <textarea id="special_requests" name="special_requests" rows="2" placeholder="Check-in sớm, phương tiện đi lại..." maxlength="500"></textarea>
              </div>

              <div class="homestay-booking-field">
                <label>Phương thức thanh toán</label>
                <div class="homestay-booking-payment">
                  <label class="homestay-booking-radio">
                    <input type="radio" name="payment_method" value="vnpay" checked>
                    <span><i class="fa-solid fa-credit-card"></i> VNPay</span>
                  </label>
                  <label class="homestay-booking-radio">
                    <input type="radio" name="payment_method" value="momo">
                    <span><i class="fa-solid fa-wallet"></i> MoMo</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="homestay-booking-breakdown">
              <div class="homestay-booking-breakdown-row">
                <span><span data-bk-nights>0</span> đêm × {{ number_format($pricePerNight, 0, ',', '.') }}đ</span>
                <span data-bk-subtotal>0đ</span>
              </div>
              <div class="homestay-booking-breakdown-row" data-bk-extra-row style="display: none;">
                <span>Phí khách vượt quy định</span>
                <span data-bk-extra>0đ</span>
              </div>
              <div class="homestay-booking-breakdown-row">
                <span>Phí dịch vụ (10%)</span>
                <span data-bk-fee>0đ</span>
              </div>
              <div class="homestay-booking-breakdown-row homestay-booking-breakdown-row--total">
                <span>Tổng cộng</span>
                <span data-bk-total>0đ</span>
              </div>
              <div class="homestay-booking-breakdown-row homestay-booking-breakdown-row--deposit" data-bk-deposit-row style="display: none;">
                <span>Đặt cọc thanh toán ngay</span>
                <span data-bk-deposit>0đ</span>
              </div>
            </div>

            <div class="homestay-detail-login-notice" id="homestay-login-notice" style="display: none;">
              <i class="fa-solid fa-circle-exclamation"></i>
              <span>Vui lòng <a href="{{ route('login.page') }}?redirect={{ urlencode(request()->fullUrl()) }}">Đăng nhập</a> để đặt phòng.</span>
            </div>
            <div class="homestay-booking-error" id="homestay-booking-error" role="alert" hidden></div>

            <button type="submit" class="btn btn-primary homestay-detail-book-btn" id="homestay-booking-submit" @if(!$isDemo) style="display: none;" @endif>
              Thanh toán
            </button>
          </form>
        </div>
      </aside>
    </div>
  </div>
</section>

@push('scripts')
<script>
(function () {
  var params = new URLSearchParams(window.location.search);
  var code = params.get('booking_created');
  if (code) {
    var msg = document.getElementById('booking-success-msg');
    var display = document.getElementById('booking-code-display');
    if (msg && display) {
      display.textContent = decodeURIComponent(code);
      msg.hidden = false;
      history.replaceState({}, document.title, window.location.pathname);
    }
  }

  var homestayData = {
    id: '{{ $homestay['id'] ?? '' }}',
    pricePerNight: {{ $pricePerNight ?? 0 }},
    maxGuests: {{ $maxGuests ?? 4 }},
    extraGuestFee: {{ $extraGuestFee ?? 100000 }},
    isDemo: {{ $isDemo ? 'true' : 'false' }},
    demoBookingUrl: '{{ $demoBookingUrl ?? '' }}',
    apiUrl: '{{ url('/api/bookings') }}',
    paymentUrl: '{{ route('payment.show') }}',
    loginUrl: '{{ route('login.page') }}',
    csrf: '{{ csrf_token() }}'
  };

  var DEPOSIT_THRESHOLD = 500000;
  var DEPOSIT_RATE_MEDIUM = 0.15;
  var DEPOSIT_RATE_HIGH = 0.20;

  function fmt(n) { return new Intl.NumberFormat('vi-VN').format(n) + 'đ'; }

  function getNights() {
    var ci = document.getElementById('check_in').value;
    var co = document.getElementById('check_out').value;
    if (!ci || !co) return 0;
    var a = new Date(ci), b = new Date(co);
    return Math.max(0, Math.ceil((b - a) / 86400000));
  }

  function getGuests() {
    var inp = document.getElementById('num_guests');
    return Math.max(1, parseInt(inp.value, 10) || 1);
  }

  function updatePrice() {
    var nights = getNights();
    var guests = getGuests();
    var price = homestayData.pricePerNight;
    var subtotal = nights * price;
    var extra = Math.max(0, guests - homestayData.maxGuests) * homestayData.extraGuestFee * nights;
    var beforeFee = subtotal + extra;
    var fee = Math.round(beforeFee * 0.1);
    var total = beforeFee + fee;

    var depositAmount = total;
    var isDeposit = false;
    if (total >= DEPOSIT_THRESHOLD) {
      var rate = total >= 2000000 ? DEPOSIT_RATE_HIGH : DEPOSIT_RATE_MEDIUM;
      depositAmount = Math.round(total * rate);
      isDeposit = true;
    }

    document.querySelector('[data-bk-nights]').textContent = nights;
    document.querySelector('[data-bk-subtotal]').textContent = fmt(subtotal);
    var extraRow = document.querySelector('[data-bk-extra-row]');
    extraRow.style.display = extra > 0 ? '' : 'none';
    document.querySelector('[data-bk-extra]').textContent = fmt(extra);
    document.querySelector('[data-bk-fee]').textContent = fmt(fee);
    document.querySelector('[data-bk-total]').textContent = fmt(total);
    var depRow = document.querySelector('[data-bk-deposit-row]');
    depRow.style.display = isDeposit ? '' : 'none';
    document.querySelector('[data-bk-deposit]').textContent = fmt(depositAmount);
  }

  function setupStepper() {
    var inp = document.getElementById('num_guests');
    var max = homestayData.maxGuests + 10;
    document.querySelectorAll('.homestay-booking-stepper-btn').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var d = parseInt(btn.getAttribute('data-delta'), 10);
        inp.value = Math.min(max, Math.max(1, getGuests() + d));
        updatePrice();
      });
    });
  }

  var form = document.getElementById('homestay-booking-form');
  form.querySelectorAll('#check_in, #check_out, #num_guests').forEach(function (el) {
    el.addEventListener('change', updatePrice);
    el.addEventListener('input', updatePrice);
  });
  setupStepper();
  updatePrice();

  var isLoggedIn = !!localStorage.getItem('auth_token');
  var loginNotice = document.getElementById('homestay-login-notice');
  var submitBtn = document.getElementById('homestay-booking-submit');
  var errorEl = document.getElementById('homestay-booking-error');

  if (homestayData.isDemo) {
    if (loginNotice) loginNotice.style.display = 'none';
    if (submitBtn) submitBtn.style.display = '';
  } else {
    if (loginNotice) loginNotice.style.display = isLoggedIn ? 'none' : '';
    if (submitBtn) submitBtn.style.display = isLoggedIn ? '' : 'none';
  }

  function showError(msg) {
    if (errorEl) {
      errorEl.textContent = msg || '';
      errorEl.hidden = !msg;
    }
  }

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    showError('');

    if (!homestayData.isDemo && !isLoggedIn) {
      window.location.href = homestayData.loginUrl + '?redirect=' + encodeURIComponent(window.location.href);
      return;
    }

    var checkIn = document.getElementById('check_in').value;
    var checkOut = document.getElementById('check_out').value;
    var nights = getNights();
    if (!checkIn || !checkOut || nights <= 0) {
      showError('Vui lòng chọn ngày nhận và trả phòng hợp lệ.');
      return;
    }

    var method = form.querySelector('input[name="payment_method"]:checked').value;
    submitBtn.disabled = true;

    if (homestayData.isDemo) {
      fetch(homestayData.demoBookingUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': homestayData.csrf,
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
          check_in_date: checkIn,
          check_out_date: checkOut,
          num_guests: getGuests(),
          payment_method: method,
          _token: homestayData.csrf
        })
      })
        .then(function (r) { return r.json(); })
        .then(function (data) {
          if (data.success && data.redirect) {
            window.location.href = data.redirect;
          } else {
            showError(data.message || 'Đặt phòng thất bại.');
            submitBtn.disabled = false;
          }
        })
        .catch(function () {
          showError('Không thể kết nối. Vui lòng thử lại.');
          submitBtn.disabled = false;
        });
      return;
    }

    fetch(homestayData.apiUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: JSON.stringify({
        homestay_id: parseInt(homestayData.id, 10),
        check_in_date: checkIn,
        check_out_date: checkOut,
        num_guests: getGuests(),
        special_requests: document.getElementById('special_requests').value || null,
        payment_method: method
      })
    })
      .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d }; }); })
      .then(function (res) {
        if (res.data && res.data.success && res.data.data && res.data.data.booking_code) {
          window.location.href = homestayData.paymentUrl + '?code=' + encodeURIComponent(res.data.data.booking_code) + '&method=' + method;
        } else if (res.data && res.data.message) {
          showError(res.data.message);
          submitBtn.disabled = false;
        } else {
          showError('Đặt phòng thất bại. Vui lòng thử lại.');
          submitBtn.disabled = false;
        }
      })
      .catch(function () {
        showError('Không thể kết nối. Vui lòng thử lại.');
        submitBtn.disabled = false;
      });
  });

  var tabs = document.getElementById('homestay-tabs');
  if (tabs) {
    function setActiveTab(hash) {
      tabs.querySelectorAll('.homestay-detail-tab').forEach(function (t) {
        t.classList.toggle('is-active', t.getAttribute('href') === (hash || '#tong-quan'));
      });
    }
    if (window.location.hash) {
      setActiveTab(window.location.hash);
    }
    tabs.querySelectorAll('.homestay-detail-tab').forEach(function (tab) {
      tab.addEventListener('click', function (e) {
        var href = this.getAttribute('href');
        if (href && href.startsWith('#')) {
          e.preventDefault();
          var el = document.querySelector(href);
          if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
          setActiveTab(href);
        }
      });
    });
  }
})();
</script>
@endpush
@endsection
