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
                  <span><?php echo e(strtoupper(substr($testimonial['name'], 0, 1))); ?></span>
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

<?php if (! $__env->hasRenderedOnce('65d0af9a-8e80-48fb-8c54-b674fd9dcaef')): $__env->markAsRenderedOnce('65d0af9a-8e80-48fb-8c54-b674fd9dcaef'); ?>
  <?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('js/clients/testimonial-carousel.js')); ?>"></script>
  <?php $__env->stopPush(); ?>
<?php endif; ?>

<?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/clients/layout/partials/testimonial-carousel.blade.php ENDPATH**/ ?>