

<?php $__env->startSection('title', 'Tìm phòng'); ?>

<?php $__env->startSection('content'); ?>
<style>
  @import url('<?php echo e(asset('css/clients/rooms-search.css')); ?>');
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
                <?php $__currentLoopData = $destinations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $destination): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <label class="rooms-filter-check">
                    <input type="radio" name="destination_id" value="<?php echo e($destination->id); ?>">
                    <span><?php echo e($destination->name); ?></span>
                  </label>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </div>
            </div>

            <div class="rooms-filter-block">
              <span class="rooms-filter-legend">Giá mỗi đêm (VNĐ)</span>
              <div class="rooms-filter-row">
                <input type="number" class="rooms-filter-input" name="price_min" id="price_min" min="0" step="50000" placeholder="Từ" value="<?php echo e(request('price_min')); ?>" inputmode="numeric" aria-label="Giá tối thiểu">
                <input type="number" class="rooms-filter-input" name="price_max" id="price_max" min="0" step="50000" placeholder="Đến" value="<?php echo e(request('price_max')); ?>" inputmode="numeric" aria-label="Giá tối đa">
              </div>
            </div>

            <div class="rooms-filter-block">
              <span class="rooms-filter-legend">Tiện nghi</span>
              <div class="rooms-filter-row rooms-filter-row--stack">
                <?php $__currentLoopData = $amenityLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <label class="rooms-filter-check">
                    <input type="checkbox" name="amenities[]" value="<?php echo e($key); ?>">
                    <span><?php echo e($label); ?></span>
                  </label>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
              Đang hiển thị <strong id="rooms-result-count"><?php echo e(count($rooms)); ?></strong> chỗ nghỉ
            </p>
            <button type="button" class="rooms-filter-toggle" id="rooms-filter-open" aria-expanded="false" aria-controls="rooms-filters-panel">
              <i class="fa-solid fa-sliders" aria-hidden="true"></i>
              Bộ lọc
            </button>
          </div>
  
          <div class="rooms-results-grid" id="rooms-results">
            <?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php
                $amenityList = $room['amenities'] ?? [];
                $roomItems = $room['room_items'] ?? [];
                $isFavourite = in_array((string) ($room['id'] ?? ''), $favoriteHomestayIds ?? [], true);
              ?>
              <a
                href="<?php echo e($room['href'] ?? '#'); ?>"
                class="search-room-card"
                data-room-card
                data-destination-id="<?php echo e($room['destination_id'] ?? ''); ?>"
                data-price="<?php echo e((int) ($room['discounted_price_per_night'] ?? $room['price_per_night'] ?? 0)); ?>"
                data-rating="<?php echo e((float) ($room['rating'] ?? 0)); ?>"
                data-amenities="<?php echo e(e(implode(',', $amenityList))); ?>"
              >
                <div class="search-room-card__img-wrap">
                  <img class="search-room-card__img" src="<?php echo e($room['img']); ?>" alt="" loading="lazy">
                  <div class="search-room-card__rating-badge">
                    <img src="<?php echo e(asset('img/icon/star.svg')); ?>" alt="" class="search-room-card__star">
                    <span><?php echo e(number_format((float) ($room['rating'] ?? 0), 1, ',', '.')); ?></span>
                    <span class="search-room-card__rating-count">(<?php echo e((int) ($room['reviews_count'] ?? 0)); ?>)</span>
                  </div>
                  <button
                    type="button"
                    class="search-room-card__fav <?php echo e($isFavourite ? 'is-active' : ''); ?>"
                    aria-label="<?php echo e($isFavourite ? 'Bỏ yêu thích' : 'Thêm vào yêu thích'); ?>"
                    data-favourite-toggle
                    data-endpoint="<?php if(auth()->guard()->check()): ?><?php echo e(route('favorite.toggle', ['homestay' => $room['id']])); ?><?php endif; ?>"
                    data-login-url="<?php echo e(route('login.page')); ?>"
                  >
                    <img src="<?php echo e(asset('img/icon/heart-outline.svg')); ?>" alt="" class="search-room-card__fav-icon search-room-card__fav-icon--default">
                    <img src="<?php echo e(asset('img/icon/heart-filled.svg')); ?>" alt="" class="search-room-card__fav-icon search-room-card__fav-icon--active">
                  </button>
                </div>
                <div class="search-room-card__body">
                  <p class="search-room-card__loc">
                    <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                    <?php echo e($room['location']); ?>

                  </p>
                  <h3 class="search-room-card__name"><?php echo e($room['name']); ?></h3>
                  <p class="text-muted small mb-3 search-room-card__summary"><?php echo e($room['description'] ?? ''); ?></p>
                  <div class="d-flex gap-3 mb-3 small text-muted search-room-card__amenity-list" aria-hidden="true">
                    <?php $__currentLoopData = $roomItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <span class="search-room-card__amenity-item">
                        <span><?php echo e($rm['text']); ?></span>
                      </span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </div>
                  <div class="search-room-card__meta">
                    <span class="search-room-card__price">
                      <?php echo e($room['price_label'] ?? ''); ?>

                      <?php if(!empty($room['has_discount'])): ?>
                        <small class="text-muted" style="font-size:.78rem;text-decoration:line-through;margin-left:6px;"><?php echo e($room['original_price_label']); ?></small>
                      <?php endif; ?>
                      <small>/ đêm</small>
                    </span>
                  </div>
                </div>
              </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('js/clients/search.js')); ?>"></script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('clients.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/clients/search.blade.php ENDPATH**/ ?>