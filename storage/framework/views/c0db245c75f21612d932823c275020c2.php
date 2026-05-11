<nav class="nav">
  <div class="container-setting nav-container">

    <?php ($isHomePage = request()->routeIs('home')); ?>
    <?php ($isCategoryPage = request()->routeIs('destinations.show')); ?>

    <a href="/" class="nav-logo">
      <?php if($isHomePage): ?>
        <?php if (isset($component)) { $__componentOriginal35eb2f30f6252dd5602143eed5e913f5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal35eb2f30f6252dd5602143eed5e913f5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.logo-icon-home','data' => ['width' => '24','height' => '24','ariaHidden' => 'true']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('logo-icon-home'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['width' => '24','height' => '24','aria-hidden' => 'true']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal35eb2f30f6252dd5602143eed5e913f5)): ?>
<?php $attributes = $__attributesOriginal35eb2f30f6252dd5602143eed5e913f5; ?>
<?php unset($__attributesOriginal35eb2f30f6252dd5602143eed5e913f5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal35eb2f30f6252dd5602143eed5e913f5)): ?>
<?php $component = $__componentOriginal35eb2f30f6252dd5602143eed5e913f5; ?>
<?php unset($__componentOriginal35eb2f30f6252dd5602143eed5e913f5); ?>
<?php endif; ?>
      <?php else: ?>
        <?php if (isset($component)) { $__componentOriginal5d812effded182e3b6dbacddab5bc7dd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5d812effded182e3b6dbacddab5bc7dd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.logo-icon','data' => ['width' => '24','height' => '24','ariaHidden' => 'true']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('logo-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['width' => '24','height' => '24','aria-hidden' => 'true']); ?>
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
      <?php endif; ?>
      <span>NestAway</span>
    </a>

    <button type="button" class="nav-toggle" id="nav-toggle" aria-label="Mở menu"
      aria-expanded="false" aria-controls="nav-menu">
      <i class="fa-solid fa-bars" aria-hidden="true"></i>
      <i class="fa-solid fa-xmark" aria-hidden="true"></i>
    </button>

    <div class="nav-menu" id="nav-menu">
      <div class="nav-menu-header">
        <a href="/" class="nav-logo">
          <?php if($isHomePage): ?>
            <?php if (isset($component)) { $__componentOriginal35eb2f30f6252dd5602143eed5e913f5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal35eb2f30f6252dd5602143eed5e913f5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.logo-icon-home','data' => ['width' => '24','height' => '24','ariaHidden' => 'true']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('logo-icon-home'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['width' => '24','height' => '24','aria-hidden' => 'true']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal35eb2f30f6252dd5602143eed5e913f5)): ?>
<?php $attributes = $__attributesOriginal35eb2f30f6252dd5602143eed5e913f5; ?>
<?php unset($__attributesOriginal35eb2f30f6252dd5602143eed5e913f5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal35eb2f30f6252dd5602143eed5e913f5)): ?>
<?php $component = $__componentOriginal35eb2f30f6252dd5602143eed5e913f5; ?>
<?php unset($__componentOriginal35eb2f30f6252dd5602143eed5e913f5); ?>
<?php endif; ?>
          <?php else: ?>
            <?php if (isset($component)) { $__componentOriginal5d812effded182e3b6dbacddab5bc7dd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5d812effded182e3b6dbacddab5bc7dd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.logo-icon','data' => ['width' => '24','height' => '24','ariaHidden' => 'true']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('logo-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['width' => '24','height' => '24','aria-hidden' => 'true']); ?>
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
          <?php endif; ?>
          <span>NestAway</span>
        </a>
        <button type="button" class="nav-close" id="nav-close" aria-label="Đóng menu">
          <i class="fa-solid fa-xmark" aria-hidden="true"></i>
        </button>
      </div>

      <ul class="nav-list">
        <li class="nav-item">
          <a href="<?php echo e(route('home')); ?>" class="nav-link <?php echo e($isHomePage ? 'active' : ''); ?>">Trang chủ</a>
        </li>
        <li class="nav-item">
          <a href="<?php echo e(route('pages.search')); ?>" class="nav-link <?php echo e(request()->routeIs('pages.search') ? 'active' : ''); ?>">Tìm phòng</a>
        </li>
        <li class="nav-item">
          <a href="<?php echo e(route('blog.index')); ?>" class="nav-link <?php echo e(request()->routeIs('blog.*') ? 'active' : ''); ?>">Blog</a>
        </li>
        <?php if(auth()->guard()->check()): ?>
          <?php if(Auth::user()->isHost()): ?>
            <li class="nav-item">
              <a href="<?php echo e(route('host.dashboard')); ?>" class="nav-link nav-link-host <?php echo e(request()->routeIs('host.*') ? 'active' : ''); ?>">Chuyển sang trang Host
              </a>
            </li>
          <?php elseif(Auth::user()->isUser()): ?>
            <li class="nav-item">
              <a href="<?php echo e(route('apply-host.create')); ?>" class="nav-link nav-link-partner <?php echo e(request()->routeIs('apply-host.*') ? 'active' : ''); ?>">Trở thành đối tác
              </a>
            </li>
          <?php endif; ?>
        <?php else: ?>
          <li class="nav-item">
            <a href="<?php echo e(route('apply-host.create')); ?>" class="nav-link nav-link-partner">Trở thành đối tác</a>
          </li>
        <?php endif; ?>
      </ul>

      <div class="nav-actions">
        <?php if(Auth::check()): ?>
          <div class="nav-user">
            <div class="nav-user-menu" id="nav-user-menu">
              <button type="button" class="nav-user-btn" id="nav-user-btn">
                <span class="nav-user-avatar"><?php echo e(mb_strtoupper(mb_substr(Auth::user()->full_name, 0, 1))); ?></span>
                <span class="nav-user-name"><?php echo e(Auth::user()->full_name); ?></span>
                <i class="fa-solid fa-chevron-down"></i>
              </button>
              <div class="nav-user-dropdown" id="nav-user-dropdown">
                <a href="<?php echo e(route('profile.page')); ?>" class="nav-user-item">
                  <i class="fa-solid fa-user"></i>
                  <span>Thông tin cá nhân</span>
                </a>
                <a href="<?php echo e(route('favorite.index')); ?>" class="nav-user-item">
                  <i class="fa-solid fa-heart"></i>
                  <span>Phòng yêu thích</span>
                </a>
                <a href="<?php echo e(route('bookings.history')); ?>" class="nav-user-item">
                  <i class="fa-solid fa-clock-rotate-left"></i>
                  <span>Lịch sử đặt phòng</span>
                </a>

                <div class="nav-divider"></div>
                <form action="<?php echo e(route('auth.logout')); ?>" method="POST" style="margin: 0;">
                  <?php echo csrf_field(); ?>
                  <button type="submit" class="nav-user-item nav-logout">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Đăng xuất</span>
                  </button>
                </form>
              </div>
            </div>
          </div>
        <?php else: ?>
          <a href="<?php echo e(route('login.page')); ?>" class="nav-btn-login">Đăng nhập</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <div class="nav-backdrop" id="nav-backdrop" hidden aria-hidden="true"></div>
</nav>

<script src="<?php echo e(asset('js/clients/header.js')); ?>"></script>

<?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/clients/layout/header.blade.php ENDPATH**/ ?>