@extends('clients.layout.app')

@section('title', 'Đặt phòng — ' . ($homestay['name'] ?? ''))

@section('content')
@php
    $isDemo = !empty($homestay['id']) && !is_numeric($homestay['id']);
    $pricePerNight = (int) ($homestay['price_per_night'] ?? 0);
    $maxGuests = (int) ($homestay['max_guests'] ?? 4);
    $extraGuestFeePerNight = 100_000;
@endphp
<style>
  @import url('{{ asset('css/clients/booking.css') }}');
</style>

<section class="booking-page section-py">
  <div class="container-setting">
    <nav class="booking-breadcrumb" aria-label="Breadcrumb">
      <ol class="booking-breadcrumb__list">
        @foreach($breadcrumbs ?? [] as $i => $crumb)
          <li class="booking-breadcrumb__item">
            @if(isset($crumb['url']) && $i < count($breadcrumbs ?? []) - 1)
              <a href="{{ $crumb['url'] }}" class="booking-breadcrumb__link">{{ $crumb['label'] }}</a>
            @else
              <span class="booking-breadcrumb__current" aria-current="page">{{ $crumb['label'] }}</span>
            @endif
            @if($i < count($breadcrumbs ?? []) - 1)
              <i class="fa-solid fa-chevron-right booking-breadcrumb__sep"></i>
            @endif
          </li>
        @endforeach
      </ol>
    </nav>

    <h1 class="booking-page-title">Đặt phòng</h1>

    <div class="booking-layout">
      <div class="booking-main">
        <form id="booking-form" class="booking-form-card" novalidate>
          <h2 class="booking-form-title">Thông tin đặt phòng</h2>

          <div class="booking-form-grid">
            <div class="booking-field">
              <label class="booking-label" for="check_in">Ngày nhận phòng</label>
              <input type="date" id="check_in" name="check_in_date" class="booking-input" required min="{{ date('Y-m-d') }}" data-booking-field>
            </div>
            <div class="booking-field">
              <label class="booking-label" for="check_out">Ngày trả phòng</label>
              <input type="date" id="check_out" name="check_out_date" class="booking-input" required data-booking-field>
            </div>
          </div>

          <div class="booking-field">
            <label class="booking-label" for="num_guests">Số khách</label>
            <div class="booking-guests">
              <button type="button" class="booking-stepper-btn" data-booking-stepper="guests" data-delta="-1" aria-label="Giảm">−</button>
              <input type="number" id="num_guests" name="num_guests" class="booking-input booking-input--center" value="1" min="1" max="{{ $maxGuests + 10 }}" readonly data-booking-field>
              <button type="button" class="booking-stepper-btn" data-booking-stepper="guests" data-delta="1" aria-label="Tăng">+</button>
            </div>
            <span class="booking-hint">Tiêu chuẩn {{ $maxGuests }} khách. Vượt quá: +{{ number_format($extraGuestFeePerNight, 0, ',', '.') }}đ/người/đêm</span>
          </div>

          <div class="booking-field">
            <label class="booking-label" for="special_requests">Ghi chú đặc biệt <span class="text-muted">(tùy chọn)</span></label>
            <textarea id="special_requests" name="special_requests" class="booking-input booking-textarea" rows="3" placeholder="Yêu cầu sớm check-in, phương tiện đi lại..." maxlength="500"></textarea>
          </div>

          @if(!$isDemo)
          <div class="booking-field">
            <label class="booking-label">Phương thức thanh toán</label>
            <div class="booking-payment-options">
              <label class="booking-radio">
                <input type="radio" name="payment_method" value="vnpay" checked>
                <span class="booking-radio-box">
                  <i class="fa-solid fa-credit-card"></i>
                  <span>VNPay</span>
                </span>
              </label>
              <label class="booking-radio">
                <input type="radio" name="payment_method" value="momo">
                <span class="booking-radio-box">
                  <i class="fa-solid fa-wallet"></i>
                  <span>MoMo</span>
                </span>
              </label>
            </div>
          </div>
          @endif
        </form>
      </div>

      <aside class="booking-sidebar">
        <div class="booking-summary-card">
          <a href="{{ route('homestay.show', ['id' => $homestay['id'] ?? '']) }}" class="booking-summary-link">
            @php $imgs = $homestay['images'] ?? []; $firstImg = $imgs[0] ?? 'https://placehold.co/600x400'; @endphp
            <img src="{{ $firstImg }}" alt="" class="booking-summary-img">
            <div class="booking-summary-info">
              <h3 class="booking-summary-name">{{ $homestay['name'] }}</h3>
              <p class="booking-summary-location"><i class="fa-solid fa-location-dot"></i> {{ $homestay['province'] ?? '' }}</p>
            </div>
          </a>

          <div class="booking-price-breakdown">
            <div class="booking-price-row">
              <span><span data-booking-nights>0</span> đêm × {{ number_format($pricePerNight, 0, ',', '.') }}đ</span>
              <span data-booking-subtotal>0đ</span>
            </div>
            <div class="booking-price-row" data-booking-extra-row style="display: none;">
              <span>Phí khách vượt quy định (<span data-booking-extra-count>0</span> × {{ number_format($extraGuestFeePerNight, 0, ',', '.') }}đ/đêm)</span>
              <span data-booking-extra>0đ</span>
            </div>
            <div class="booking-price-row">
              <span>Phí dịch vụ (10%)</span>
              <span data-booking-fee>0đ</span>
            </div>
            <div class="booking-price-row booking-price-row--total">
              <span>Tổng cộng</span>
              <span data-booking-total>0đ</span>
            </div>
          </div>

          <div class="booking-message booking-message--error" id="booking-error" role="alert" hidden></div>

          @if($isDemo)
            <p class="booking-demo-notice">
              <i class="fa-solid fa-info-circle"></i>
              Homestay mẫu không thể đặt. <a href="{{ route('login.page') }}">Đăng nhập</a> và chọn homestay thật từ danh sách để đặt phòng.
            </p>
            <button type="button" class="btn btn-secondary booking-submit-btn" disabled>Chỉ xem trước</button>
          @else
            <p class="booking-login-notice" id="booking-login-notice" style="display: none;">
              <i class="fa-solid fa-circle-exclamation"></i>
              Vui lòng <a href="{{ route('login.page') }}?redirect={{ urlencode('/' . request()->path()) }}">Đăng nhập</a> để tiếp tục đặt phòng.
            </p>
            <button type="submit" form="booking-form" class="btn btn-primary booking-submit-btn" id="booking-submit" style="display: none;">
              Xác nhận đặt phòng
            </button>
          @endif
        </div>
      </aside>
    </div>
  </div>
</section>

@push('scripts')
<script>
(function () {
  var form = document.getElementById('booking-form');
  var submitBtn = document.getElementById('booking-submit');
  var errorEl = document.getElementById('booking-error');

  var homestayData = {
    id: '{{ $homestay['id'] ?? '' }}',
    pricePerNight: {{ $pricePerNight }},
    maxGuests: {{ $maxGuests }},
    extraGuestFeePerNight: {{ $extraGuestFeePerNight }},
    isDemo: {{ $isDemo ? 'true' : 'false' }}
  };

  function showError(msg) {
    if (errorEl) {
      errorEl.textContent = msg || '';
      errorEl.hidden = !msg;
    }
  }

  function getNights() {
    var checkIn = document.getElementById('check_in').value;
    var checkOut = document.getElementById('check_out').value;
    if (!checkIn || !checkOut) return 0;
    var a = new Date(checkIn);
    var b = new Date(checkOut);
    var diff = Math.ceil((b - a) / (1000 * 60 * 60 * 24));
    return Math.max(0, diff);
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
    var extraGuests = Math.max(0, guests - homestayData.maxGuests);
    var extraFee = extraGuests * homestayData.extraGuestFeePerNight * nights;
    var beforeServiceFee = subtotal + extraFee;
    var fee = Math.round(beforeServiceFee * 0.1);
    var total = beforeServiceFee + fee;

    var nightsEl = document.querySelector('[data-booking-nights]');
    var subtotalEl = document.querySelector('[data-booking-subtotal]');
    var extraRow = document.querySelector('[data-booking-extra-row]');
    var extraCountEl = document.querySelector('[data-booking-extra-count]');
    var extraEl = document.querySelector('[data-booking-extra]');
    var feeEl = document.querySelector('[data-booking-fee]');
    var totalEl = document.querySelector('[data-booking-total]');

    function fmt(n) {
      return new Intl.NumberFormat('vi-VN').format(n) + 'đ';
    }

    if (nightsEl) nightsEl.textContent = nights;
    if (subtotalEl) subtotalEl.textContent = fmt(subtotal);
    if (extraRow) {
      extraRow.style.display = extraGuests > 0 ? '' : 'none';
      if (extraCountEl) extraCountEl.textContent = extraGuests;
      if (extraEl) extraEl.textContent = fmt(extraFee);
    }
    if (feeEl) feeEl.textContent = fmt(fee);
    if (totalEl) totalEl.textContent = fmt(total);
  }

  function setupStepper() {
    var inp = document.getElementById('num_guests');
    if (!inp) return;
    var maxAllowed = homestayData.maxGuests + 10;
    document.querySelectorAll('[data-booking-stepper="guests"]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var d = parseInt(btn.getAttribute('data-delta'), 10);
        var v = getGuests() + d;
        v = Math.min(maxAllowed, Math.max(1, v));
        inp.value = v;
        updatePrice();
      });
    });
  }

  form.querySelectorAll('[data-booking-field]').forEach(function (el) {
    el.addEventListener('change', updatePrice);
    el.addEventListener('input', updatePrice);
  });

  setupStepper();
  updatePrice();

  if (!homestayData.isDemo) {
    var loginNotice = document.getElementById('booking-login-notice');
    var isLoggedIn = !!localStorage.getItem('auth_token');
    if (submitBtn) submitBtn.style.display = isLoggedIn ? '' : 'none';
    if (loginNotice) loginNotice.style.display = isLoggedIn ? 'none' : '';
  }

  if (form && submitBtn && !homestayData.isDemo) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      showError('');

      var checkIn = document.getElementById('check_in').value;
      var checkOut = document.getElementById('check_out').value;
      var guests = getGuests();
      var nights = getNights();

      if (!checkIn || !checkOut) {
        showError('Vui lòng chọn ngày nhận và trả phòng.');
        return;
      }
      if (nights <= 0) {
        showError('Ngày trả phòng phải sau ngày nhận phòng.');
        return;
      }

      var token = localStorage.getItem('auth_token');
      if (!token) {
        window.location.href = '{{ route('login.page') }}?redirect=' + encodeURIComponent(window.location.pathname);
        return;
      }

      submitBtn.disabled = true;

      fetch('/api/bookings', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'Authorization': 'Bearer ' + token
        },
        body: JSON.stringify({
          homestay_id: parseInt(homestayData.id, 10),
          check_in_date: checkIn,
          check_out_date: checkOut,
          num_guests: guests,
          special_requests: document.getElementById('special_requests').value || null,
          payment_method: form.querySelector('input[name="payment_method"]:checked').value
        })
      })
        .then(function (res) { return res.json().then(function (data) { return { ok: res.ok, status: res.status, data: data }; }); })
        .then(function (payload) {
          if (payload.status === 401) {
            window.location.href = '{{ route('login.page') }}?redirect=' + encodeURIComponent(window.location.pathname);
            return;
          }
          if (!payload.ok || !payload.data.success) {
            showError(payload.data && payload.data.message ? payload.data.message : 'Đặt phòng thất bại. Vui lòng thử lại.');
            submitBtn.disabled = false;
            return;
          }
          var booking = payload.data.data;
          if (booking && booking.booking_code) {
            window.location.href = '{{ route('homestay.show', ['id' => $homestay['id'] ?? '']) }}?booking_created=' + encodeURIComponent(booking.booking_code);
          } else {
            window.location.href = '/';
          }
        })
        .catch(function () {
          showError('Không thể kết nối. Vui lòng thử lại.');
          submitBtn.disabled = false;
        });
    });
  }
})();
</script>
@endpush
@endsection
