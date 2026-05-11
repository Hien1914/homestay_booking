<section class="testimonials-showcase" style="width: 100%; margin: 0; padding: var(--section-spacing-lg) 0;">
    <div class="tsm-shell" id="testimonial-slider" style="padding: 26px 40px 18px;">
      <div class="container-setting">
        <div class="tsm-top">
          <h3 class="tsm-title">Những đánh giá của khách hàng</h3>
          <div class="tsm-nav">
            <button type="button" class="tsm-nav-btn" data-tsm-prev aria-label="Đánh giá trước">
              <i class="fa-solid fa-chevron-left"></i>
            </button>
            <button type="button" class="tsm-nav-btn" data-tsm-next aria-label="Đánh giá tiếp theo">
              <i class="fa-solid fa-chevron-right"></i>
            </button>
          </div>
        </div>
      </div>

      <div class="tsm-viewport" data-tsm-viewport>
        <div class="tsm-track" data-tsm-track>
          @foreach(($testimonials ?? []) as $testimonial)
            <article class="tsm-slide">
              <div class="tsm-card">
                <div class="tsm-avatar">
                  <span>{{ strtoupper(substr($testimonial['name'], 0, 1)) }}</span>
                </div>
                <div class="tsm-head">
                  <div>
                    <div class="tsm-name">{{ $testimonial['name'] }}</div>
                    <div class="tsm-role">{{ $testimonial['role'] }}</div>
                  </div>
                  <div class="tsm-stars" aria-label="{{ $testimonial['rating'] }} sao">
                    @for($i = 0; $i < $testimonial['rating']; $i++)
                      <img src="{{ asset('img/icon/star.svg') }}" alt="" class="tsm-star-icon">
                    @endfor
                  </div>
                </div>
                <p class="tsm-comment">{{ $testimonial['comment'] }}</p>
              </div>
            </article>
          @endforeach
        </div>
      </div>

      <div class="container-setting">
        <div class="tsm-dots" data-tsm-dots></div>
      </div>
    </div>
</section>

@once
  @push('scripts')
    <script src="{{ asset('js/clients/testimonial-carousel.js') }}"></script>
  @endpush
@endonce

