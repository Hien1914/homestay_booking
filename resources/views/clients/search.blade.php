@extends('clients.layout.app')

@section('title', 'Tìm phòng')

@section('content')
<style>
  @import url('{{ asset('css/clients/rooms-search.css') }}');
</style>

<section class="rooms-search-page">
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
              <span class="rooms-filter-legend">Điểm đến</span>
              <div class="rooms-filter-row rooms-filter-row--stack">
                <label class="rooms-filter-check">
                  <input type="radio" name="destination_id" value="" checked>
                  <span>Tất cả</span>
                </label>
                @foreach($destinations as $destination)
                  <label class="rooms-filter-check">
                    <input type="radio" name="destination_id" value="{{ $destination->id }}">
                    <span>{{ $destination->name }}</span>
                  </label>
                @endforeach
              </div>
            </div>

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
                $roomItems = $room['room_items'] ?? [];
                $isFavourite = in_array((string) ($room['id'] ?? ''), $favoriteHomestayIds ?? [], true);
              @endphp
              <a
                href="{{ $room['href'] ?? '#' }}"
                class="search-room-card"
                data-room-card
                data-destination-id="{{ $room['destination_id'] ?? '' }}"
                data-price="{{ (int) ($room['discounted_price_per_night'] ?? $room['price_per_night'] ?? 0) }}"
                data-rating="{{ (float) ($room['rating'] ?? 0) }}"
                data-amenities="{{ e(implode(',', $amenityList)) }}"
              >
                <div class="search-room-card__img-wrap">
                  <img class="search-room-card__img" src="{{ $room['img'] }}" alt="" loading="lazy">
                  <div class="search-room-card__rating-badge">
                    <img src="{{ asset('img/icon/star.svg') }}" alt="" class="search-room-card__star">
                    <span>{{ number_format((float) ($room['rating'] ?? 0), 1, ',', '.') }}</span>
                    <span class="search-room-card__rating-count">({{ (int) ($room['reviews_count'] ?? 0) }})</span>
                  </div>
                  <button
                    type="button"
                    class="search-room-card__fav {{ $isFavourite ? 'is-active' : '' }}"
                    aria-label="{{ $isFavourite ? 'Bỏ yêu thích' : 'Thêm vào yêu thích' }}"
                    data-favourite-toggle
                    data-endpoint="@auth{{ route('favorite.toggle', ['homestay' => $room['id']]) }}@endauth"
                    data-login-url="{{ route('login.page') }}"
                  >
                    <img src="{{ asset('img/icon/heart-outline.svg') }}" alt="" class="search-room-card__fav-icon search-room-card__fav-icon--default">
                    <img src="{{ asset('img/icon/heart-filled.svg') }}" alt="" class="search-room-card__fav-icon search-room-card__fav-icon--active">
                  </button>
                </div>
                <div class="search-room-card__body">
                  <p class="search-room-card__loc">
                    <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                    {{ $room['location'] }}
                  </p>
                  <h3 class="search-room-card__name">{{ $room['name'] }}</h3>
                  <p class="text-muted small mb-3 search-room-card__summary">{{ $room['description'] ?? '' }}</p>
                  <div class="d-flex gap-3 mb-3 small text-muted search-room-card__amenity-list" aria-hidden="true">
                    @foreach($roomItems as $rm)
                      <span class="search-room-card__amenity-item">
                        <span>{{ $rm['text'] }}</span>
                      </span>
                    @endforeach
                  </div>
                  <div class="search-room-card__meta">
                    <span class="search-room-card__price">
                      {{ $room['price_label'] ?? '' }}
                      @if(!empty($room['has_discount']))
                        <small class="text-muted" style="font-size:.78rem;text-decoration:line-through;margin-left:6px;">{{ $room['original_price_label'] }}</small>
                      @endif
                      <small>/ đêm</small>
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
<script src="{{ asset('js/clients/search.js') }}"></script>
@endpush

