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
          <?php $__currentLoopData = ($testimonials ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testimonial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <article class="tsm-slide">
              <div class="tsm-card">
                <div class="tsm-avatar">
                  <?php if(!empty($testimonial['avatar_url'])): ?>
                    <img src="<?php echo e($testimonial['avatar_url']); ?>" alt="<?php echo e($testimonial['name']); ?>" class="tsm-avatar-img">
                  <?php else: ?>
                    <span><?php echo e($testimonial['initial']); ?></span>
                  <?php endif; ?>
                </div>
                <div class="tsm-head">
                  <div>
                    <div class="tsm-name"><?php echo e($testimonial['name']); ?></div>
                    <div class="tsm-role"><?php echo e($testimonial['role']); ?></div>
                  </div>
                  <div class="tsm-stars" aria-label="<?php echo e($testimonial['rating']); ?> sao">
                    <?php for($i = 0; $i < $testimonial['rating']; $i++): ?>
                      <img src="<?php echo e(asset('img/icon/star.svg')); ?>" alt="" class="tsm-star-icon">
                    <?php endfor; ?>
                  </div>
                </div>
                <p class="tsm-comment"><?php echo e($testimonial['comment']); ?></p>
              </div>
            </article>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      </div>

      <div class="container-setting">
        <div class="tsm-dots" data-tsm-dots></div>
      </div>
    </div>
</section>

<?php if (! $__env->hasRenderedOnce('7c954998-6966-4081-ae90-b7679cc23742')): $__env->markAsRenderedOnce('7c954998-6966-4081-ae90-b7679cc23742'); ?>
  <?php $__env->startPush('scripts'); ?>
    <script>
      (function () {
        const slider = document.getElementById('testimonial-slider');
        if (!slider) return;

        const viewport = slider.querySelector('[data-tsm-viewport]');
        const track = slider.querySelector('[data-tsm-track]');
        const slides = Array.from(slider.querySelectorAll('.tsm-slide'));
        const prevBtn = slider.querySelector('[data-tsm-prev]');
        const nextBtn = slider.querySelector('[data-tsm-next]');
        const dotsWrap = slider.querySelector('[data-tsm-dots]');
        if (!viewport || !track || slides.length === 0) return;

        const perView = () => (window.innerWidth < 768 ? 1 : 3);
        let index = 0;
        let autoTimer = null;
        let startX = 0;
        let dragDelta = 0;
        let dragging = false;

        const buildDots = () => {
          if (!dotsWrap) return;
          const pages = Math.max(1, slides.length - perView() + 1);
          dotsWrap.innerHTML = '';
          for (let i = 0; i < pages; i++) {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'tsm-dot' + (i === index ? ' is-active' : '');
            btn.setAttribute('aria-label', 'Tới đánh giá ' + (i + 1));
            btn.addEventListener('click', () => {
              index = i;
              update(true);
              startAuto();
            });
            dotsWrap.appendChild(btn);
          }
        };

        const updateDots = () => {
          if (!dotsWrap) return;
          dotsWrap.querySelectorAll('.tsm-dot').forEach((dot, i) => {
            dot.classList.toggle('is-active', i === index);
          });
        };

        const update = (animate = true) => {
          const max = Math.max(0, slides.length - perView());
          index = Math.max(0, Math.min(index, max));
          const step = slides[0].offsetWidth + 20;
          track.style.transition = animate ? 'transform .45s ease' : 'none';
          track.style.transform = `translateX(-${index * step}px)`;
          updateDots();
        };

        const next = () => {
          const max = Math.max(0, slides.length - perView());
          index = index >= max ? 0 : index + 1;
          update(true);
        };

        const prev = () => {
          const max = Math.max(0, slides.length - perView());
          index = index <= 0 ? max : index - 1;
          update(true);
        };

        const startAuto = () => {
          stopAuto();
          autoTimer = setInterval(next, 3600);
        };

        const stopAuto = () => {
          if (!autoTimer) return;
          clearInterval(autoTimer);
          autoTimer = null;
        };

        prevBtn?.addEventListener('click', () => { prev(); startAuto(); });
        nextBtn?.addEventListener('click', () => { next(); startAuto(); });

        viewport.addEventListener('mouseenter', stopAuto);
        viewport.addEventListener('mouseleave', startAuto);
        viewport.addEventListener('touchstart', (e) => {
          dragging = true;
          startX = e.touches[0].clientX;
          dragDelta = 0;
          stopAuto();
          track.style.transition = 'none';
        }, { passive: true });
        viewport.addEventListener('touchmove', (e) => {
          if (!dragging) return;
          dragDelta = e.touches[0].clientX - startX;
          const step = slides[0].offsetWidth + 20;
          track.style.transform = `translateX(-${index * step - dragDelta}px)`;
        }, { passive: true });
        viewport.addEventListener('touchend', () => {
          if (!dragging) return;
          dragging = false;
          if (dragDelta < -50) next();
          else if (dragDelta > 50) prev();
          else update(true);
          startAuto();
        });
        window.addEventListener('resize', () => {
          buildDots();
          update(false);
        });

        buildDots();
        update(false);
        startAuto();
      })();
    </script>
  <?php $__env->stopPush(); ?>
<?php endif; ?>
<?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/clients/partials/testimonial-carousel.blade.php ENDPATH**/ ?>