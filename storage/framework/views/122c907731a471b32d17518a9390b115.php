<footer class="site-footer">
  <div class="container-footer">
    <div class="footer-grid">
      <div class="footer-brand">
        <div class="footer-logo">
          <?php if (isset($component)) { $__componentOriginal5d812effded182e3b6dbacddab5bc7dd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5d812effded182e3b6dbacddab5bc7dd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.logo-icon','data' => ['width' => '28','height' => '28','ariaHidden' => 'true']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('logo-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['width' => '28','height' => '28','aria-hidden' => 'true']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5d812effded182e3b6dbacddab5bc7dd)): ?>
<?php $attributes = $__attributesOriginal5d812effded182e3b6dbacddab5bc7dd; ?>
<?php unset($__attributesOriginal5d812effded182e3b6dbacddab5bc7dd); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5d812effded182e3b6dbacddab5bc7dd)): ?>
<?php $component = $__componentOriginal5d812effded182e3b6dbacddab5bc7dd; ?>
<?php unset($__componentOriginal5d812effded182e3b6dbacddab5bc7dd); ?>
<?php endif; ?>
          <span>NestAway</span>
        </div>
        <p class="footer-desc">
          Nền tảng đặt phòng homestay uy tín tại Việt Nam. 
          Kết nối du khách với những không gian lưu trú đẹp, độc đáo và đáng nhớ.
        </p>
        <div class="footer-contact">
          <p><i class="fas fa-phone"></i> 0967 798 825</p>
          <p><i class="fas fa-envelope"></i> support@nestaway.vn</p>
          <p><i class="fas fa-map-marker-alt"></i> Hà Nội, Việt Nam</p>
        </div>
      </div>

      <div class="footer-cols">
        <div class="footer-col">
          <h4 class="footer-col-title">Khám phá</h4>
          <a href="<?php echo e(route('pages.about')); ?>" class="footer-link">Giới thiệu</a>
          <a href="<?php echo e(route('blog.index')); ?>" class="footer-link">Bài viết về du lịch</a>
        </div>

        <div class="footer-col">
          <h4 class="footer-col-title">Hỗ trợ</h4>
          <a href="<?php echo e(route('pages.faq')); ?>" class="footer-link">Câu hỏi thường gặp (FAQ)</a>
          <a href="<?php echo e(route('pages.policy_booking')); ?>" class="footer-link">Chính sách đặt phòng & Hủy phòng</a>
          <a href="<?php echo e(route('pages.terms')); ?>" class="footer-link">Điều khoản dịch vụ</a>
          <a href="<?php echo e(route('pages.privacy_policy')); ?>" class="footer-link">Chính sách bảo mật</a>
        </div>
      </div>
    </div>
  </div>
  
  <div class="footer-bottom">
    <div class="container-footer">
      <div class="footer-bottom-content">
        <p class="text-center">© <?php echo e(date('Y')); ?> NestAway. Bảo lưu mọi quyền.</p>
      </div>
    </div>
  </div>
</footer>
<?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/clients/layout/footer.blade.php ENDPATH**/ ?>