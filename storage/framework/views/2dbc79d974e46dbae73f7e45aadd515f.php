<?php $__env->startSection('title', 'Danh sách yêu thích'); ?>

<?php $__env->startSection('content'); ?>
<style>
    @import url('<?php echo e(asset('css/clients/rooms-search.css')); ?>');
    @import url('<?php echo e(asset('css/clients/favourite.css')); ?>');
</style>

<section class="favourite-page">
    <div class="container-setting">
        <div class="favourite-header">
            <h1 class="favourite-title">Danh sách phòng yêu thích</h1>
            <p class="favourite-subtitle">Bạn có <span id="favourite-total"><?php echo e($favorites->total()); ?></span> homestay trong danh sách yêu thích.</p>
        </div>

        <?php if($favorites->count() === 0): ?>
            <div class="favourite-empty">
                Bạn chưa thêm homestay nào vào danh sách yêu thích.
            </div>
        <?php else: ?>
            <div class="favourite-grid">
                <?php $__currentLoopData = $favorites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $favorite): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $room = $favorite->homestay;
                        if (!$room) {
                            continue;
                        }
                        $image = $room->images->first()?->image_url;
                        $imgUrl = $image
                            ? (\Illuminate\Support\Str::startsWith($image, ['http://', 'https://']) ? $image : asset('storage/' . ltrim($image, '/')))
                            : 'https://placehold.co/600x400';
                        $rating = $room->reviews_avg_rating !== null ? number_format((float) $room->reviews_avg_rating, 1, ',', '.') : '0,0';
                        $reviewsCount = (int) ($room->reviews_count ?? 0);
                        $discountedPrice = (float) $room->discounted_price;
                        $originalPrice = (float) $room->price_per_night;
                        $hasDiscount = $room->applied_promotion !== null && $discountedPrice < $originalPrice;
                        $roomData = $room->rooms_array;
                        $roomTypes = \App\Models\HomestayRoom::ROOM_TYPES;
                        $roomItems = [];
                        $count = 0;
                        foreach ($roomData as $type => $qty) {
                            if ($qty > 0 && isset($roomTypes[$type])) {
                                $icon = match ($type) {
                                    'single_bedroom', 'double_bedroom' => 'bed.svg',
                                    'bathroom' => 'bath.svg',
                                    'pool' => 'pool.svg',
                                    'living_room', 'kitchen', 'dining_room', 'laundry', 'entertainment_room', 'karaoke_room' => 'done.svg',
                                    'garden', 'balcony', 'terrace' => 'leaf.svg',
                                    'parking' => 'location.svg',
                                    default => 'done.svg',
                                };
                                $roomItems[] = [
                                    'icon' => $icon,
                                    'text' => $qty . ' ' . $roomTypes[$type],
                                ];
                                $count++;
                                if ($count >= 4) break;
                            }
                        }
                    ?>

                    <a
                        href="<?php echo e(route('homestay.show', ['slug' => $room->slug])); ?>"
                        class="search-room-card"
                        data-favourite-card
                    >
                        <div class="search-room-card__img-wrap">
                            <img class="search-room-card__img" src="<?php echo e($imgUrl); ?>" alt="<?php echo e($room->title); ?>" loading="lazy">
                            <div class="search-room-card__rating-badge">
                                <img src="<?php echo e(asset('img/icon/star.svg')); ?>" alt="" class="search-room-card__star">
                                <span><?php echo e($rating); ?></span>
                                <span class="search-room-card__rating-count">(<?php echo e($reviewsCount); ?>)</span>
                            </div>
                            <button
                                type="button"
                                class="search-room-card__fav is-active"
                                aria-label="Bỏ yêu thích"
                                data-favourite-toggle
                                data-endpoint="<?php echo e(route('favorite.toggle', ['homestay' => $room->id])); ?>"
                            >
                                <img src="<?php echo e(asset('img/icon/heart-outline.svg')); ?>" alt="" class="search-room-card__fav-icon search-room-card__fav-icon--default">
                                <img src="<?php echo e(asset('img/icon/heart-filled.svg')); ?>" alt="" class="search-room-card__fav-icon search-room-card__fav-icon--active">
                            </button>
                        </div>
                        <div class="search-room-card__body">
                            <p class="search-room-card__loc">
                                <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                                <?php echo e($room->province); ?>

                            </p>
                            <h3 class="search-room-card__name"><?php echo e($room->title); ?></h3>
                            <p class="text-muted small mb-3 search-room-card__summary"><?php echo e(\Illuminate\Support\Str::limit((string) $room->description, 110)); ?></p>
                            <div class="d-flex gap-3 mb-3 small text-muted search-room-card__amenity-list" aria-hidden="true">
                                <?php $__currentLoopData = $roomItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="search-room-card__amenity-item">
                                        <span><?php echo e($rm['text']); ?></span>
                                    </span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <div class="search-room-card__meta">
                                <span class="search-room-card__price">
                                    <?php echo e(number_format($discountedPrice, 0, ',', '.')); ?>đ
                                    <?php if($hasDiscount): ?>
                                        <small class="text-muted" style="font-size:.78rem;text-decoration:line-through;margin-left:6px;"><?php echo e(number_format($originalPrice, 0, ',', '.')); ?>đ</small>
                                    <?php endif; ?>
                                    <small>/ đêm</small>
                                </span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="d-flex justify-content-center mt-5">
                <?php echo e($favorites->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</section>

<?php $__env->startPush('scripts'); ?>
<script>
(function () {
    var csrfToken = document.querySelector('meta[name="csrf-token"]');
    var csrfValue = csrfToken ? csrfToken.getAttribute('content') : '';
    var totalEl = document.getElementById('favourite-total');

    document.querySelectorAll('[data-favourite-toggle]').forEach(function (btn) {
        btn.addEventListener('click', async function (e) {
            e.preventDefault();
            e.stopPropagation();

            var endpoint = btn.getAttribute('data-endpoint');
            if (!endpoint || btn.dataset.loading === '1') return;
            btn.dataset.loading = '1';

            try {
                var res = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfValue || '',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!res.ok) return;
                var data = await res.json();
                if (data.active === false) {
                    var card = btn.closest('[data-favourite-card]');
                    if (card) card.remove();
                    if (totalEl) {
                        var next = Math.max(0, (parseInt(totalEl.textContent || '0', 10) || 0) - 1);
                        totalEl.textContent = String(next);
                    }
                    if (!document.querySelector('[data-favourite-card]')) {
                        window.location.reload();
                    }
                }
            } finally {
                delete btn.dataset.loading;
            }
        });
    });
})();
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('clients.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/clients/favorites.blade.php ENDPATH**/ ?>