<section class="container-setting bg-white">
    <div class="d-flex justify-content-between align-items-center mb-5 gap-3 flex-wrap">
      <div>
        <h2 class="display-6 fw-bold" style="font-family: 'Google Sans', sans-serif;">
          Danh sách Homestay nổi bật
        </h2>
      </div>
      <a href="<?php echo e(route('pages.search')); ?>" class="fw-semibold text-decoration-none" style="color: var(--green-700)">Xem tất cả →</a>
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
          <?php $__currentLoopData = ($featuredHomestays ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php ($isFavourite = Auth::check() ? $room->favorites()->where('user_id', Auth::id())->exists() : false); ?>
            <?php ($coverImage = $room->images->where('is_primary', true)->first()?->image_url ?? $room->images->first()?->image_url); ?>
            <article class="carousel-slide">
              <a href="<?php echo e(route('homestay.show', ['slug' => $room->slug])); ?>" class="text-decoration-none text-body d-block h-100">
                <div class="room-card card border-0 shadow-sm h-100">
                  <div class="position-relative room-img-wrap">
                    <?php if($coverImage): ?>
                      <img src="<?php echo e(asset('storage/' . $coverImage)); ?>" alt="<?php echo e($room->title); ?>" class="room-img card-img-top">
                    <?php else: ?>
                      <div class="room-img-placeholder d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                        <i class="bi bi-house text-muted" style="font-size: 2rem;"></i>
                      </div>
                    <?php endif; ?>
                    <div class="room-rating-badge">
                      <img src="<?php echo e(asset('img/icon/star.svg')); ?>" alt="" class="room-meta-icon room-meta-icon--star">
                      <span><?php echo e(number_format($room->reviews_avg_rating ?? 0, 1, ',', '.')); ?></span>
                      <span class="room-rating-badge__count">(<?php echo e($room->reviews_count ?? 0); ?>)</span>
                    </div>
                    <button
                      class="room-fav <?php echo e($isFavourite ? 'is-active' : ''); ?>"
                      type="button"
                      aria-label="<?php echo e($isFavourite ? 'Bỏ yêu thích' : 'Thêm vào yêu thích'); ?>"
                      data-favourite-toggle
                      data-endpoint="<?php if(auth()->guard()->check()): ?><?php echo e(route('favorite.toggle', ['homestay' => $room->id])); ?><?php endif; ?>"
                      data-login-url="<?php echo e(route('login.page')); ?>"
                    >
                      <img src="<?php echo e(asset('img/icon/heart-outline.svg')); ?>" alt="" class="room-fav-icon room-fav-icon--default">
                      <img src="<?php echo e(asset('img/icon/heart-filled.svg')); ?>" alt="" class="room-fav-icon room-fav-icon--active">
                    </button>
                  </div>

                  <div class="card-body d-flex flex-column">
                    <p class="small text-muted mb-2 room-meta-item">
                      <img src="<?php echo e(asset('img/icon/location.svg')); ?>" alt="" class="room-meta-icon">
                      <span><?php echo e($room->province); ?></span>
                    </p>

                    <h5 class="card-title fw-semibold mb-2" style="font-family: 'Google Sans', sans-serif;"><?php echo e($room->title); ?></h5>
                    <p class="text-muted small mb-3 room-summary"><?php echo e(Str::limit($room->description, 80)); ?></p>

                    <div class="d-flex gap-2 mb-3 small text-muted room-amenity-list flex-wrap">
                      <?php $__currentLoopData = $room->rooms_array; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $qty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($qty > 0 && isset(\App\Models\HomestayRoom::ROOM_TYPES[$type])): ?>
                          <span class="badge bg-light text-dark border-0 fw-normal" style="font-size: 0.7rem;">
                            <?php echo e($qty); ?> <?php echo e(\App\Models\HomestayRoom::ROOM_TYPES[$type]); ?>

                          </span>
                        <?php endif; ?>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <div class="pt-3 border-top mt-auto">
                      <div>
                        <span class="h5 fw-bold mb-0" style="color: var(--green-800)"><?php echo e(number_format($room->discounted_price)); ?>đ</span>
                        <?php if($room->discounted_price < $room->price_per_night): ?>
                          <span class="ms-2 text-muted" style="font-size:.82rem;text-decoration:line-through;"><?php echo e(number_format($room->price_per_night)); ?>đ</span>
                        <?php endif; ?>
                        <small class="text-muted"> / đêm</small>
                      </div>
                    </div>
                  </div>
                </div>
              </a>
            </article>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      </div>

      <div class="carousel-dots" data-carousel-dots></div>
    </div>
</section>

<?php if (! $__env->hasRenderedOnce('b9b2bee9-858e-4afd-a892-a8529d83d977')): $__env->markAsRenderedOnce('b9b2bee9-858e-4afd-a892-a8529d83d977'); ?>
  <?php $__env->startPush('scripts'); ?>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        new Carousel({
          container: document.getElementById('featured-slider'),
          autoPlaySpeed: 5000,
          responsive: {
            mobile: { width: 768, itemsPerView: 2 },
            tablet: { width: 1024, itemsPerView: 3 },
            desktop: { width: Infinity, itemsPerView: 4 }
          },
          gap: 20,
          trackSelector: '[data-carousel-track]',
          slideSelector: '.carousel-slide',
          dotsSelector: '[data-carousel-dots]',
          prevBtnSelector: '[data-carousel-prev]',
          nextBtnSelector: '[data-carousel-next]',
          viewportSelector: '[data-carousel-viewport]',
          enableDrag: false
        });

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        document.querySelectorAll('[data-favourite-toggle]').forEach((btn) => {
          btn.addEventListener('click', async (e) => {
            e.preventDefault();
            e.stopPropagation();

            const endpoint = btn.getAttribute('data-endpoint');
            if (!endpoint) {
              const loginUrl = btn.getAttribute('data-login-url');
              if (loginUrl) window.location.href = loginUrl;
              return;
            }

            if (btn.dataset.loading === '1') return;
            btn.dataset.loading = '1';

            try {
              const res = await fetch(endpoint, {
                method: 'POST',
                headers: {
                  'X-CSRF-TOKEN': csrfToken || '',
                  'X-Requested-With': 'XMLHttpRequest',
                  'Accept': 'application/json'
                }
              });

              if (!res.ok) return;
              const data = await res.json();
              const active = !!data.active;
              btn.classList.toggle('is-active', active);
              btn.setAttribute('aria-label', active ? 'Bỏ yêu thích' : 'Thêm vào yêu thích');
            } finally {
              delete btn.dataset.loading;
            }
          });
        });
      });
    </script>
  <?php $__env->stopPush(); ?>
<?php endif; ?>
<?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/clients/partials/homestay-carousel.blade.php ENDPATH**/ ?>