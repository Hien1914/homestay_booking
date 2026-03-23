@extends('clients.layout.app')

@section('title', 'Tìm phòng')

@section('content')
<style>
  @import url('{{ asset('css/clients/rooms-search.css') }}');
</style>

<section class="rooms-search-page section-py">
  <div class="container-setting">
    <header class="rooms-search-header">
      <h1 class="rooms-search-title">Tìm phòng phù hợp</h1>
      <p class="rooms-search-subtitle">Lọc theo giá, tiện nghi, đánh giá và danh mục điểm đến — danh sách cập nhật theo bộ lọc bạn chọn.</p>
    </header>

    <div class="rooms-filters-backdrop" id="rooms-filters-backdrop" hidden aria-hidden="true"></div>

    <div class="rooms-search-layout">
      <aside class="rooms-filters-sidebar" id="rooms-filters-panel" aria-label="Bộ lọc tìm phòng">
        <button type="button" class="rooms-filters-close" id="rooms-filters-close" aria-label="Đóng bộ lọc">
          <i class="fa-solid fa-xmark" aria-hidden="true"></i>
        </button>
        <h2 class="rooms-filters-title">Bộ lọc</h2>

        <form id="room-filter-form" class="rooms-filter-form" novalidate>
          <div class="rooms-filter-block">
            <span class="rooms-filter-legend">Giá mỗi đêm (VNĐ)</span>
            <div class="rooms-filter-row">
              <input type="number" class="rooms-filter-input" name="price_min" id="price_min" min="0" step="50000" placeholder="Từ" value="{{ request('price_min') }}" inputmode="numeric" aria-label="Giá tối thiểu">
              <input type="number" class="rooms-filter-input" name="price_max" id="price_max" min="0" step="50000" placeholder="Đến" value="{{ request('price_max') }}" inputmode="numeric" aria-label="Giá tối đa">
            </div>
          </div>

          <div class="rooms-filter-block">
            <span class="rooms-filter-legend">Tiện nghi</span>
            <div class="rooms-filter-row rooms-filter-row--stack">
              @foreach($amenityLabels as $key => $label)
                <label class="rooms-filter-check">
                  <input type="checkbox" name="amenities[]" value="{{ $key }}">
                  <span>{{ $label }}</span>
                </label>
              @endforeach
            </div>
          </div>

          <div class="rooms-filter-block">
            <span class="rooms-filter-legend">Đánh giá tối thiểu</span>
            <div class="rooms-filter-row rooms-filter-row--stack">
              <label class="rooms-filter-radio">
                <input type="radio" name="rating_min" value="0" checked>
                <span>Bất kỳ</span>
              </label>
              <label class="rooms-filter-radio">
                <input type="radio" name="rating_min" value="3">
                <span>Từ 3 <i class="fa-solid fa-star" style="color:#f5a623;font-size:0.75rem"></i> trở lên</span>
              </label>
              <label class="rooms-filter-radio">
                <input type="radio" name="rating_min" value="4">
                <span>Từ 4 <i class="fa-solid fa-star" style="color:#f5a623;font-size:0.75rem"></i> trở lên</span>
              </label>
              <label class="rooms-filter-radio">
                <input type="radio" name="rating_min" value="4.5">
                <span>Từ 4,5 <i class="fa-solid fa-star" style="color:#f5a623;font-size:0.75rem"></i> trở lên</span>
              </label>
            </div>
          </div>

          <div class="rooms-filter-block">
            <span class="rooms-filter-legend">Danh mục</span>
            <div class="rooms-filter-row rooms-filter-row--stack">
              @foreach($categoryLabels as $key => $label)
                <label class="rooms-filter-check">
                  <input type="checkbox" name="category[]" value="{{ $key }}">
                  <span>{{ $label }}</span>
                </label>
              @endforeach
            </div>
            <p class="small text-muted mt-2 mb-0" style="font-size:0.78rem;">Không chọn danh mục nào = hiển thị tất cả.</p>
          </div>

          <div class="rooms-filter-actions">
            <button type="submit" class="rooms-filter-btn rooms-filter-btn--primary">Áp dụng bộ lọc</button>
            <button type="button" class="rooms-filter-btn rooms-filter-btn--ghost" id="room-filter-reset">Xóa bộ lọc</button>
          </div>
        </form>
      </aside>

      <div class="rooms-search-main">
        <div class="rooms-search-toolbar">
          <p class="rooms-result-count mb-0" id="rooms-result-summary">
            Đang hiển thị <strong id="rooms-result-count">{{ count($rooms) }}</strong> chỗ nghỉ
          </p>
          <button type="button" class="rooms-filter-toggle" id="rooms-filter-open" aria-expanded="false" aria-controls="rooms-filters-panel">
            <i class="fa-solid fa-sliders" aria-hidden="true"></i>
            Bộ lọc
          </button>
        </div>

        <div class="rooms-results-grid" id="rooms-results">
          @foreach($rooms as $room)
            @php
              $amenityList = $room['amenities'] ?? [];
              $catKey = $room['category'] ?? '';
              $catLabel = $categoryLabels[$catKey] ?? $catKey;
            @endphp
            <a
              href="{{ $room['href'] ?? '#' }}"
              class="search-room-card"
              data-room-card
              data-price="{{ (int) ($room['price_per_night'] ?? 0) }}"
              data-rating="{{ (float) ($room['rating'] ?? 0) }}"
              data-category="{{ e($catKey) }}"
              data-amenities="{{ e(implode(',', $amenityList)) }}"
            >
              <div class="search-room-card__img-wrap">
                <img class="search-room-card__img" src="{{ $room['img'] }}" alt="" loading="lazy">
                <span class="search-room-card__tag">{{ $catLabel }}</span>
              </div>
              <div class="search-room-card__body">
                <p class="search-room-card__loc">
                  <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                  {{ $room['location'] }}
                </p>
                <h3 class="search-room-card__name">{{ $room['name'] }}</h3>
                <div class="search-room-card__amenities" aria-hidden="true">
                  @foreach(array_slice($amenityList, 0, 4) as $am)
                    @if(isset($amenityLabels[$am]))
                      <span class="search-room-card__amenity">{{ $amenityLabels[$am] }}</span>
                    @endif
                  @endforeach
                </div>
                <div class="search-room-card__meta">
                  <span class="search-room-card__price">
                    {{ $room['price_label'] ?? '' }}
                    <small>/ đêm</small>
                  </span>
                  <span class="search-room-card__rating" title="Đánh giá trung bình">
                    <i class="fa-solid fa-star" aria-hidden="true"></i>
                    {{ number_format((float) ($room['rating'] ?? 0), 1, ',', '.') }}
                    <span class="fw-normal text-muted" style="font-size:0.78rem;">({{ (int) ($room['reviews_count'] ?? 0) }})</span>
                  </span>
                </div>
              </div>
            </a>
          @endforeach
        </div>

        <p class="rooms-empty-state" id="rooms-empty" role="status">Không có chỗ nghỉ nào khớp bộ lọc. Hãy thử nới lỏng giá hoặc bỏ vài tiện nghi.</p>
        <nav class="rooms-pagination" id="rooms-pagination" aria-label="Phân trang kết quả">
          <button type="button" class="rooms-pagination__btn" id="rooms-page-prev">Trước</button>
          <span class="rooms-pagination__info" id="rooms-page-info">Trang 1/1</span>
          <button type="button" class="rooms-pagination__btn" id="rooms-page-next">Sau</button>
        </nav>
      </div>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
(function () {
  var form = document.getElementById('room-filter-form');
  var cards = document.querySelectorAll('[data-room-card]');
  var countEl = document.getElementById('rooms-result-count');
  var emptyEl = document.getElementById('rooms-empty');
  var paginationEl = document.getElementById('rooms-pagination');
  var prevBtn = document.getElementById('rooms-page-prev');
  var nextBtn = document.getElementById('rooms-page-next');
  var pageInfo = document.getElementById('rooms-page-info');
  var resultsGrid = document.getElementById('rooms-results');
  var panel = document.getElementById('rooms-filters-panel');
  var backdrop = document.getElementById('rooms-filters-backdrop');
  var openBtn = document.getElementById('rooms-filter-open');
  var closeBtn = document.getElementById('rooms-filters-close');

  var currentPage = 1;
  var filteredCards = [];
  var currentPerPage = getPerPage();

  function getGridColumns() {
    if (!resultsGrid) return 1;
    var cols = window.getComputedStyle(resultsGrid).gridTemplateColumns;
    if (!cols || cols === 'none') return 1;
    return cols.split(' ').filter(Boolean).length || 1;
  }

  function getPerPage() {
    var cols = getGridColumns();
    if (cols >= 3) return 9;
    return 6;
  }

  function parseAmenities(str) {
    if (!str) return [];
    return String(str).split(',').map(function (s) { return s.trim(); }).filter(Boolean);
  }

  function getCheckedValues(name) {
    return Array.prototype.slice.call(form.querySelectorAll('input[name="' + name + '"]:checked')).map(function (el) { return el.value; });
  }

  function applyFilters() {
    var fd = new FormData(form);
    var priceMin = parseInt(fd.get('price_min') || '0', 10);
    if (isNaN(priceMin) || priceMin < 0) priceMin = 0;
    var priceMaxRaw = fd.get('price_max');
    var priceMax = priceMaxRaw === '' || priceMaxRaw === null ? Infinity : parseInt(priceMaxRaw, 10);
    if (isNaN(priceMax)) priceMax = Infinity;

    var ratingMin = parseFloat(fd.get('rating_min') || '0') || 0;
    var categories = getCheckedValues('category[]');
    var requiredAmenities = getCheckedValues('amenities[]');

    filteredCards = [];
    cards.forEach(function (card) {
      var price = parseInt(card.getAttribute('data-price') || '0', 10);
      var rating = parseFloat(card.getAttribute('data-rating') || '0');
      var category = card.getAttribute('data-category') || '';
      var roomAmenities = parseAmenities(card.getAttribute('data-amenities'));

      var ok = true;
      if (price < priceMin || price > priceMax) ok = false;
      if (ok && rating < ratingMin - 1e-6) ok = false;
      if (ok && categories.length && categories.indexOf(category) === -1) ok = false;
      if (ok && requiredAmenities.length) {
        for (var i = 0; i < requiredAmenities.length; i++) {
          if (roomAmenities.indexOf(requiredAmenities[i]) === -1) {
            ok = false;
            break;
          }
        }
      }

      card.classList.toggle('is-hidden', !ok);
      card.classList.remove('is-page-hidden');
      if (ok) filteredCards.push(card);
    });

    currentPage = 1;
    renderPage();
  }

  function renderPage() {
    currentPerPage = getPerPage();
    var total = filteredCards.length;
    var totalPages = Math.max(1, Math.ceil(total / currentPerPage));
    if (currentPage > totalPages) currentPage = totalPages;

    var start = (currentPage - 1) * currentPerPage;
    var end = start + currentPerPage;
    filteredCards.forEach(function (card, idx) {
      card.classList.toggle('is-page-hidden', idx < start || idx >= end);
    });

    if (countEl) countEl.textContent = String(total);
    if (emptyEl) emptyEl.classList.toggle('is-visible', total === 0);
    if (paginationEl) paginationEl.hidden = total <= currentPerPage;
    if (pageInfo) pageInfo.textContent = 'Trang ' + currentPage + '/' + totalPages;
    if (prevBtn) prevBtn.disabled = currentPage <= 1;
    if (nextBtn) nextBtn.disabled = currentPage >= totalPages;
  }

  function closePanel() {
    if (!panel) return;
    panel.classList.remove('is-open');
    if (backdrop) {
      backdrop.classList.remove('is-visible');
      backdrop.setAttribute('hidden', '');
      backdrop.setAttribute('aria-hidden', 'true');
    }
    if (openBtn) {
      openBtn.setAttribute('aria-expanded', 'false');
    }
  }

  function openPanel() {
    if (!panel) return;
    panel.classList.add('is-open');
    if (backdrop) {
      backdrop.removeAttribute('hidden');
      backdrop.classList.add('is-visible');
      backdrop.setAttribute('aria-hidden', 'false');
    }
    if (openBtn) openBtn.setAttribute('aria-expanded', 'true');
  }

  if (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      applyFilters();
      closePanel();
    });
    form.addEventListener('change', function () {
      applyFilters();
    });
  }

  var resetBtn = document.getElementById('room-filter-reset');
  if (resetBtn && form) {
    resetBtn.addEventListener('click', function () {
      form.reset();
      var anyRating = form.querySelector('input[name="rating_min"][value="0"]');
      if (anyRating) anyRating.checked = true;
      applyFilters();
    });
  }

  if (prevBtn) {
    prevBtn.addEventListener('click', function () {
      if (currentPage <= 1) return;
      currentPage -= 1;
      renderPage();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  if (nextBtn) {
    nextBtn.addEventListener('click', function () {
      var totalPages = Math.max(1, Math.ceil(filteredCards.length / currentPerPage));
      if (currentPage >= totalPages) return;
      currentPage += 1;
      renderPage();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  window.addEventListener('resize', function () {
    var nextPerPage = getPerPage();
    if (nextPerPage !== currentPerPage) {
      currentPerPage = nextPerPage;
      currentPage = 1;
      renderPage();
    }
  });

  if (openBtn) openBtn.addEventListener('click', openPanel);
  if (closeBtn) closeBtn.addEventListener('click', closePanel);
  if (backdrop) backdrop.addEventListener('click', closePanel);

  applyFilters();
})();
</script>
@endpush
