{{-- Thanh tìm kiếm hero: pill, 4 cột đều nhau --}}
<form class="booking-form booking-form-pill mx-auto" action="{{ $bookingFormAction ?? '#' }}" method="get" role="search">
  <div class="booking-form-pill-inner">
    <div class="booking-form-pill-fields">
      <div class="booking-form-segment">
        <span class="booking-form-segment-ico" aria-hidden="true"><i class="fa-regular fa-map"></i></span>
        <div class="booking-form-segment-main">
          <label class="booking-form-segment-title" for="bf-location">Vị trí</label>
          <input id="bf-location" name="location" type="text" class="booking-form-segment-input" placeholder="Thêm điểm đến" autocomplete="off">
        </div>
      </div>
      <div class="booking-form-segment booking-form-segment--date-range">
        <span class="booking-form-segment-ico" aria-hidden="true">
          <svg viewBox="0 0 24 24" class="booking-form-calendar-icon" fill="none">
            <rect x="3.5" y="5.5" width="17" height="15" rx="3" stroke="currentColor" stroke-width="1.8"/>
            <path d="M7.5 3.75V7.25" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            <path d="M16.5 3.75V7.25" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            <path d="M3.5 9.5H20.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            <path d="M8 13H8.01" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
            <path d="M12 13H12.01" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
            <path d="M16 13H16.01" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
          </svg>
        </span>
        <div class="booking-form-segment-main">
          <label class="booking-form-segment-title" for="bf-checkin">Ngày nhận phòng - trả phòng</label>
          <div class="booking-form-date-range" data-booking-dates>
            <input id="bf-checkin" name="check_in" type="date" class="booking-form-date-native" title="Chọn ngày nhận phòng" data-date-input="checkin">
            <input id="bf-checkout" name="check_out" type="date" class="booking-form-date-native" title="Chọn ngày trả phòng" data-date-input="checkout">
            <button type="button" class="booking-form-date-trigger" data-date-trigger aria-label="Chọn ngày nhận và trả phòng">
              <span class="booking-form-date-value" data-date-summary>dd/mm/yyyy - dd/mm/yyyy</span>
            </button>
          </div>
        </div>
      </div>
      <div class="booking-form-segment booking-form-segment--has-dropdown">
        <span class="booking-form-segment-ico" aria-hidden="true"><i class="fa-solid fa-user-group"></i></span>
        <div class="booking-form-segment-main">
          <label class="booking-form-segment-title" for="bf-guests-trigger">Số khách</label>
          <div class="booking-form-guests booking-form-select" data-booking-guests>
            <input type="hidden" name="adults" value="2" data-guests-input="adults">
            <input type="hidden" name="children" value="0" data-guests-input="children">
            <input type="hidden" name="infants" value="0" data-guests-input="infants">
            <input type="hidden" name="guests" value="2" data-guests-total>
            <button type="button" class="booking-form-select-trigger" id="bf-guests-trigger" aria-haspopup="dialog" aria-expanded="false" aria-controls="bf-guests-panel">
              <span class="booking-form-select-value" data-guests-summary>2 người lớn</span>
              <i class="fa-solid fa-chevron-down booking-form-select-caret" aria-hidden="true"></i>
            </button>
            <div id="bf-guests-panel" class="booking-form-select-panel booking-form-guests-panel" hidden role="dialog" aria-modal="false" aria-label="Chọn số khách">
              <div class="booking-form-guests-row">
                <div class="booking-form-guests-row-text">
                  <span class="booking-form-guests-label">Người lớn</span>
                  <span class="booking-form-guests-hint">Từ 13 tuổi</span>
                </div>
                <div class="booking-form-guests-stepper" data-guests-stepper="adults" data-min="1" data-max="16">
                  <button type="button" class="booking-form-guests-step" data-delta="-1" aria-label="Giảm người lớn">−</button>
                  <span class="booking-form-guests-num" data-guests-display="adults">2</span>
                  <button type="button" class="booking-form-guests-step" data-delta="1" aria-label="Tăng người lớn">+</button>
                </div>
              </div>
              <div class="booking-form-guests-row">
                <div class="booking-form-guests-row-text">
                  <span class="booking-form-guests-label">Trẻ em</span>
                  <span class="booking-form-guests-hint">2–12 tuổi</span>
                </div>
                <div class="booking-form-guests-stepper" data-guests-stepper="children" data-min="0" data-max="10">
                  <button type="button" class="booking-form-guests-step" data-delta="-1" aria-label="Giảm trẻ em">−</button>
                  <span class="booking-form-guests-num" data-guests-display="children">0</span>
                  <button type="button" class="booking-form-guests-step" data-delta="1" aria-label="Tăng trẻ em">+</button>
                </div>
              </div>
              <div class="booking-form-guests-row">
                <div class="booking-form-guests-row-text">
                  <span class="booking-form-guests-label">Em bé</span>
                  <span class="booking-form-guests-hint">Dưới 2 tuổi</span>
                </div>
                <div class="booking-form-guests-stepper" data-guests-stepper="infants" data-min="0" data-max="5">
                  <button type="button" class="booking-form-guests-step" data-delta="-1" aria-label="Giảm em bé">−</button>
                  <span class="booking-form-guests-num" data-guests-display="infants">0</span>
                  <button type="button" class="booking-form-guests-step" data-delta="1" aria-label="Tăng em bé">+</button>
                </div>
              </div>
              <p class="booking-form-guests-foot">Tổng cộng <strong data-guests-foot-total>2</strong> khách</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <button type="submit" class="booking-form-pill-btn" aria-label="Tìm kiếm homestay">
      <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
      <span class="booking-form-pill-btn-text d-lg-none">Tìm kiếm</span>
    </button>
  </div>
</form>

@once
@push('scripts')
<script>
(function () {
  function clamp(n, min, max) {
    return Math.min(max, Math.max(min, n));
  }

  function formatDisplayDate(value) {
    if (!value) return 'dd/mm/yyyy';
    var parts = value.split('-');
    if (parts.length !== 3) return 'dd/mm/yyyy';
    return parts[2] + '/' + parts[1] + '/' + parts[0];
  }

  function addDays(value, days) {
    if (!value) return '';
    var parts = value.split('-');
    if (parts.length !== 3) return '';
    var date = new Date(parseInt(parts[0], 10), parseInt(parts[1], 10) - 1, parseInt(parts[2], 10));
    date.setDate(date.getDate() + days);
    var y = date.getFullYear();
    var m = String(date.getMonth() + 1).padStart(2, '0');
    var d = String(date.getDate()).padStart(2, '0');
    return y + '-' + m + '-' + d;
  }

  document.querySelectorAll('[data-booking-dates]').forEach(function (wrap) {
    var checkIn = wrap.querySelector('[data-date-input="checkin"]');
    var checkOut = wrap.querySelector('[data-date-input="checkout"]');
    var trigger = wrap.querySelector('[data-date-trigger]');
    var summary = wrap.querySelector('[data-date-summary]');
    if (!checkIn || !checkOut || !trigger || !summary) return;

    var today = new Date();
    var todayValue = new Date(today.getTime() - today.getTimezoneOffset() * 60000).toISOString().split('T')[0];
    checkIn.min = todayValue;

    function syncSummary() {
      summary.textContent = formatDisplayDate(checkIn.value) + ' - ' + formatDisplayDate(checkOut.value);
    }

    function openPicker(input) {
      if (!input) return;
      if (typeof input.showPicker === 'function') {
        input.showPicker();
      } else {
        input.focus();
        input.click();
      }
    }

    function syncCheckoutLimit() {
      if (checkIn.value) {
        checkOut.min = addDays(checkIn.value, 1);
      } else {
        checkOut.min = todayValue;
      }

      if (checkIn.value && checkOut.value && checkOut.value <= checkIn.value) {
        checkOut.value = '';
      }
    }

    trigger.addEventListener('click', function () {
      openPicker(checkIn);
    });

    checkIn.addEventListener('change', function () {
      syncCheckoutLimit();
      syncSummary();
      openPicker(checkOut);
    });

    checkOut.addEventListener('change', function () {
      if (checkIn.value && checkOut.value && checkOut.value <= checkIn.value) {
        checkOut.value = '';
      }
      syncSummary();
    });

    syncCheckoutLimit();
    syncSummary();
  });

  document.querySelectorAll('[data-booking-guests]').forEach(function (wrap) {
    var trigger = wrap.querySelector('.booking-form-select-trigger');
    var panel = wrap.querySelector('.booking-form-guests-panel');
    var summary = wrap.querySelector('[data-guests-summary]');
    var totalInput = wrap.querySelector('[data-guests-total]');
    var footTotal = wrap.querySelector('[data-guests-foot-total]');

    var keys = ['adults', 'children', 'infants'];
    var state = {};
    keys.forEach(function (k) {
      var inp = wrap.querySelector('[data-guests-input="' + k + '"]');
      state[k] = parseInt(inp.value, 10) || 0;
    });

    function syncHidden() {
      keys.forEach(function (k) {
        wrap.querySelector('[data-guests-input="' + k + '"]').value = String(state[k]);
      });
      var t = state.adults + state.children + state.infants;
      totalInput.value = String(t);
      if (footTotal) footTotal.textContent = String(t);
    }

    function syncStepperUI() {
      keys.forEach(function (k) {
        var stepper = wrap.querySelector('[data-guests-stepper="' + k + '"]');
        if (!stepper) return;
        var min = parseInt(stepper.getAttribute('data-min'), 10);
        var max = parseInt(stepper.getAttribute('data-max'), 10);
        var v = state[k];
        var disp = wrap.querySelector('[data-guests-display="' + k + '"]');
        if (disp) disp.textContent = String(v);
        var btns = stepper.querySelectorAll('.booking-form-guests-step');
        btns.forEach(function (btn) {
          var d = parseInt(btn.getAttribute('data-delta'), 10);
          var next = v + d;
          btn.disabled = next < min || next > max;
        });
      });
    }

    function buildSummary() {
      var parts = [state.adults + ' người lớn'];
      if (state.children > 0) parts.push(state.children + ' trẻ em');
      if (state.infants > 0) parts.push(state.infants + ' em bé');
      summary.textContent = parts.join(' · ');
    }

    function apply() {
      syncHidden();
      syncStepperUI();
      buildSummary();
    }

    function close() {
      wrap.classList.remove('is-open');
      if (panel) {
        panel.hidden = true;
      }
      trigger.setAttribute('aria-expanded', 'false');
    }

    function open() {
      wrap.classList.add('is-open');
      if (panel) panel.hidden = false;
      trigger.setAttribute('aria-expanded', 'true');
    }

    function toggle(ev) {
      if (ev) ev.stopPropagation();
      wrap.classList.contains('is-open') ? close() : open();
    }

    trigger.addEventListener('click', toggle);

    wrap.querySelectorAll('[data-guests-stepper]').forEach(function (stepper) {
      var key = stepper.getAttribute('data-guests-stepper');
      var min = parseInt(stepper.getAttribute('data-min'), 10);
      var max = parseInt(stepper.getAttribute('data-max'), 10);
      stepper.querySelectorAll('.booking-form-guests-step').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
          e.stopPropagation();
          var d = parseInt(btn.getAttribute('data-delta'), 10);
          state[key] = clamp(state[key] + d, min, max);
          apply();
        });
      });
    });

    document.addEventListener('click', function (e) {
      if (wrap.classList.contains('is-open') && !wrap.contains(e.target)) close();
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && wrap.classList.contains('is-open')) close();
    });

    apply();
  });
})();
</script>
@endpush
@endonce
