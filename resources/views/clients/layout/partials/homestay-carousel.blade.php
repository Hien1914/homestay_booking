<section class="container-setting bg-white">
    <div class="d-flex justify-content-between align-items-center mb-5 gap-3 flex-wrap">
      <div>
        <h2 class="display-6" style="font-family: var(--font-google-sans); font-weight: 500">
          Danh sách Homestay nổi bật
        </h2>
      </div>
      <a href="{{ route('pages.search') }}" class="fw-semibold text-decoration-none" style="color: var(--green-700)">Xem tất cả →</a>
    </div>

    <div class="featured-slider" id="featured-slider">
      <button type="button" class="carousel-nav prev" aria-label="Phòng trước" data-carousel-prev>
        <i class="fa-solid fa-chevron-left"></i>
      </button>
      <button type="button" class="carousel-nav next" aria-label="Phòng tiếp theo" data-carousel-next>
        <i class="fa-solid fa-chevron-right"></i>
      </button>

      <div class="carousel-viewport" data-carousel-viewport>
        <div class="carousel-track" data-carousel-track>
          @foreach(($featuredHomestays ?? []) as $room)
            @php($isFavourite = Auth::check() ? $room->favorites()->where('user_id', Auth::id())->exists() : false)
            @php($coverImage = $room->images->where('is_primary', true)->first()?->image_url ?? $room->images->first()?->image_url)
            <article class="carousel-slide">
              <a href="{{ route('homestay.show', ['slug' => $room->slug]) }}" class="text-decoration-none text-body d-block h-100">
                <div class="room-card card border-0 shadow-sm h-100">
                  <div class="position-relative room-img-wrap">
                    @if($coverImage)
                      <img src="{{ asset('storage/' . $coverImage) }}" alt="{{ $room->title }}" class="room-img card-img-top">
                    @else
                      <div class="room-img-placeholder d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                        <i class="bi bi-house text-muted" style="font-size: 2rem;"></i>
                      </div>
                    @endif
                    <div class="room-rating-badge">
                      <img src="{{ asset('img/icon/star.svg') }}" alt="" class="room-meta-icon room-meta-icon--star">
                      <span>{{ number_format($room->reviews_avg_rating ?? 0, 1, ',', '.') }}</span>
                      <span class="room-rating-badge__count">({{ $room->reviews_count ?? 0 }})</span>
                    </div>
                    <button
                      class="room-fav {{ $isFavourite ? 'is-active' : '' }}"
                      type="button"
                      aria-label="{{ $isFavourite ? 'Bỏ yêu thích' : 'Thêm vào yêu thích' }}"
                      data-favourite-toggle
                      data-endpoint="@auth{{ route('favorite.toggle', ['homestay' => $room->id]) }}@endauth"
                      data-login-url="{{ route('login.page') }}"
                    >
                      <img src="{{ asset('img/icon/heart-outline.svg') }}" alt="" class="room-fav-icon room-fav-icon--default">
                      <img src="{{ asset('img/icon/heart-filled.svg') }}" alt="" class="room-fav-icon room-fav-icon--active">
                    </button>
                  </div>

                  <div class="card-body d-flex flex-column">
                    <p class="small text-muted mb-2 room-meta-item">
                      <img src="{{ asset('img/icon/location.svg') }}" alt="" class="room-meta-icon">
                      <span>{{ $room->province }}</span>
                    </p>

                    <h5 class="card-title fw-semibold mb-2" style="font-family: var(--font-google-sans)">{{ $room->title }}</h5>
                    <p class="text-muted small mb-3 room-summary">{{ Str::limit($room->description, 80) }}</p>

                    <div class="d-flex gap-2 mb-3 small text-muted room-amenity-list flex-wrap">
                      @foreach($room->rooms_array as $type => $qty)
                        @if($qty > 0 && isset(\App\Models\HomestayRoom::ROOM_TYPES[$type]))
                          <span class="badge bg-light text-dark border-0 fw-normal" style="font-size: 0.7rem;">
                            {{ $qty }} {{ \App\Models\HomestayRoom::ROOM_TYPES[$type] }}
                          </span>
                        @endif
                      @endforeach
                    </div>

                    <div class="pt-3 border-top mt-auto">
                      <div>
                        <span class="h5 fw-bold mb-0" style="color: var(--green-800)">{{ number_format($room->discounted_price) }}đ</span>
                        @if($room->discounted_price < $room->price_per_night)
                          <span class="ms-2 text-muted" style="font-size:.82rem;text-decoration:line-through;">{{ number_format($room->price_per_night) }}đ</span>
                        @endif
                        <small class="text-muted"> / đêm</small>
                      </div>
                    </div>
                  </div>
                </div>
              </a>
            </article>
          @endforeach
        </div>
      </div>

      <div class="carousel-dots" data-carousel-dots></div>
    </div>
</section>

@once
  @push('scripts')
    <script src="{{ asset('js/clients/homestay-carousel.js') }}"></script>
  @endpush
@endonce

