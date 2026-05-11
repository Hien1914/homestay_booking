<section class="container-setting">
  <div class="mb-4">
    <h2 class="display-6" style="font-weight: 500; font-family: var(--font-google-sans)">Những Điểm Đến Phổ Biến</h2>
  </div>
  
  <div class="carousel-destination" id="destinations-carousel">
    <button type="button" class="carousel-nav prev" aria-label="Điểm đến trước" data-carousel-prev>
      <i class="fa-solid fa-chevron-left"></i>
    </button>
    <button type="button" class="carousel-nav next" aria-label="Điểm đến tiếp theo" data-carousel-next>
      <i class="fa-solid fa-chevron-right"></i>
    </button>

    <div class="carousel-viewport" data-carousel-viewport>
      <div class="carousel-track" data-carousel-track>
        @forelse($destinations as $destination)
          <div class="carousel-slide">
            <a href="{{ route('destinations.show', ['category' => $destination->slug]) }}" class="text-decoration-none">
              <div style="height: 300px; background-image: url('{{ $destination->image ? (Str::startsWith($destination->image, 'http') ? $destination->image : asset('storage/' . ltrim($destination->image, '/'))) : '' }}'); background-size: cover; background-position: center; border-radius: 12px; position: relative;">
                <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(0,0,0,0.5), transparent); padding: 20px; border-radius: 0 0 12px 12px;">
                  <h5 class="text-white fw-bold mb-0">{{ $destination->name }}</h5>
                </div>
              </div>
            </a>
          </div>
        @empty
          <p class="text-muted text-center">Chưa có điểm đến nào</p>
        @endforelse
      </div>
    </div>

    <div class="carousel-dots" data-carousel-dots></div>
  </div>
</section>

@once
  @push('scripts')
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        new Carousel({
          container: document.getElementById('destinations-carousel'),
          autoPlaySpeed: 3000,
          responsive: {
            mobile: { width: 768, itemsPerView: 2 },
            tablet: { width: 1024, itemsPerView: 3 },
            desktop: { width: Infinity, itemsPerView: 4 }
          },
          gap: 20,
          enableDrag: false
        });
      });
    </script>
  @endpush
@endonce
