<section class="container-setting">
  <div class="mb-4">
    <h2 class="display-6 fw-bold" style="font-family: var(--font-google-sans)">Những Điểm Đến Phổ Biến</h2>
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
        <?php $__empty_1 = true;
        $__currentLoopData = $destinations;
        $__env->addLoop($__currentLoopData);
        foreach ($__currentLoopData as $destination): $__env->incrementLoopIndices();
          $loop = $__env->getLastLoop();
          $__empty_1 = false; ?>
          <div class="carousel-slide">
            <a href="<?php echo e(route('destinations.show', ['category' => $destination->slug])); ?>" class="text-decoration-none">
              <div style="height: 300px; background-image: url('<?php echo e($destination->image ? (Str::startsWith($destination->image, 'http') ? $destination->image : asset('storage/' . ltrim($destination->image, '/'))) : ''); ?>'); background-size: cover; background-position: center; border-radius: 12px; position: relative;">
                <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(0,0,0,0.5), transparent); padding: 20px; border-radius: 0 0 12px 12px;">
                  <h5 class="text-white fw-bold mb-0"><?php echo e($destination->name); ?></h5>
                </div>
              </div>
            </a>
          </div>
        <?php endforeach;
        $__env->popLoop();
        $loop = $__env->getLastLoop();
        if ($__empty_1): ?>
          <p class="text-muted text-center">Chưa có điểm đến nào</p>
        <?php endif; ?>
      </div>
    </div>

    <div class="carousel-dots" data-carousel-dots></div>
  </div>
</section>

<?php if (! $__env->hasRenderedOnce('613087c5-6dac-42b2-9b80-e89f97e6d125')): $__env->markAsRenderedOnce('613087c5-6dac-42b2-9b80-e89f97e6d125'); ?>
  <?php $__env->startPush('scripts'); ?>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      new Carousel({
        container: document.getElementById('destinations-carousel'),
        autoPlaySpeed: 3000,
        responsive: {
          mobile: {
            width: 768,
            itemsPerView: 2
          },
          tablet: {
            width: 1024,
            itemsPerView: 3
          },
          desktop: {
            width: Infinity,
            itemsPerView: 4
          }
        },
        gap: 20,
        enableDrag: false
      });
    });
  </script>
  <?php $__env->stopPush(); ?>
<?php endif; ?>
<?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/clients/partials/destination-carousel.blade.php ENDPATH**/ ?>