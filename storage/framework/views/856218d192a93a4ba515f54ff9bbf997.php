

<?php $__env->startSection('title', 'Quản lý chỗ nghỉ'); ?>

<?php $__env->startSection('content'); ?>
<div class="admin-page-header mb-4">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title h3 fw-bold mb-1"><?php echo $__env->yieldContent('title'); ?></h1>
        <p class="admin-page-subtitle text-muted small mb-0">Quản lý tất cả homestay trong hệ thống</p>
    </div>
</div>

<!-- Statistics -->
<div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4 mb-4">
    <div class="col">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body d-flex align-items-center p-4">
                <div class="admin-stat-icon bg-primary-subtle text-primary me-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                    <i class="bi bi-house-door fs-4"></i>
                </div>
                <div>
                    <div class="h4 fw-bold mb-0 text-dark"><?php echo e($stats['total']); ?></div>
                    <div class="text-muted small fw-semibold">Tổng chỗ nghỉ</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body d-flex align-items-center p-4">
                <div class="admin-stat-icon bg-success-subtle text-success me-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                    <i class="bi bi-check-circle fs-4"></i>
                </div>
                <div>
                    <div class="h4 fw-bold mb-0 text-dark"><?php echo e($stats['available']); ?></div>
                    <div class="text-muted small fw-semibold">Đang hoạt động</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body d-flex align-items-center p-4">
                <div class="admin-stat-icon bg-warning-subtle text-warning me-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                    <i class="bi bi-star fs-4"></i>
                </div>
                <div>
                    <div class="h4 fw-bold mb-0 text-dark"><?php echo e(number_format($stats['avgRating'], 1)); ?></div>
                    <div class="text-muted small fw-semibold">Điểm TB</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body d-flex align-items-center p-4">
                <div class="admin-stat-icon bg-info-subtle text-info me-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                    <i class="bi bi-clock-history fs-4"></i>
                </div>
                <div>
                    <div class="h4 fw-bold mb-0 text-dark"><?php echo e($stats['pending_approval'] ?? 0); ?></div>
                    <div class="text-muted small fw-semibold">Chờ duyệt</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm mb-4 rounded-3">
    <div class="card-body p-4">
        <form method="GET" action="<?php echo e(route('admin.homestays')); ?>" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-bold">Từ ngày</label>
                <input type="date" name="from_date" class="form-control" value="<?php echo e(request('from_date')); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">Đến ngày</label>
                <input type="date" name="to_date" class="form-control" value="<?php echo e(request('to_date')); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-secondary">Trạng thái</label>
                <select name="approval_status" class="form-select">
                    <option value="">Tất cả</option>
                    <option value="approved" <?php echo e(request('approval_status') == 'approved' ? 'selected' : ''); ?>>Đã duyệt</option>
                    <option value="pending" <?php echo e(request('approval_status') == 'pending' ? 'selected' : ''); ?>>Chờ duyệt</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="admin-filter-btn w-100 justify-content-center">Lọc</button>
                <a href="<?php echo e(route('admin.homestays')); ?>" class="admin-filter-clear-btn w-100 justify-content-center">Xóa</a>
            </div>
        </form>
    </div>
</div>

<!-- Homestays Table -->
<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="card-header bg-white py-3 border-light-subtle d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold h6">
            <i class="bi bi-house-door me-2 text-primary"></i>Danh sách chỗ nghỉ
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th class="ps-4">Chỗ nghỉ</th>
                        <th>Chủ sở hữu</th>
                        <th>Địa điểm</th>
                        <th class="text-center">Giá/đêm</th>
                        <th class="text-center">Đánh giá</th>
                        <th class="text-center">Duyệt</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $homestays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $homestay): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $primaryImage = $homestay->images->where('is_primary', true)->first();
                            $coverImage = $primaryImage ? $primaryImage->image_url : ($homestay->images->first()?->image_url ?? null);
                            $avgRating = $homestay->reviews_avg_rating ?? 0;
                        ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="admin-thumbnail me-3">
                                        <?php if($coverImage): ?>
                                            <img src="<?php echo e(asset('storage/' . $coverImage)); ?>" class="w-100 h-100 object-fit-cover">
                                        <?php else: ?>
                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted"><i class="bi bi-image"></i></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="fw-bold text-dark small text-truncate" style="max-width: 180px;"><?php echo e($homestay->title); ?></div>
                                </div>
                            </td>
                            <td><div class="small fw-medium"><?php echo e($homestay->owner->full_name ?? '-'); ?></div></td>
                            <td>
                                <div class="small"><?php echo e($homestay->province); ?></div>
                                <div class="text-muted" style="font-size: 10px;"><?php echo e(Str::limit($homestay->address, 30)); ?></div>
                            </td>
                            <td class="text-center">
                                <?php if($homestay->discounted_price < $homestay->price_per_night): ?>
                                    <div class="fw-bold text-success"><?php echo e(number_format($homestay->discounted_price)); ?>đ</div>
                                    <small class="text-muted text-decoration-line-through"><?php echo e(number_format($homestay->price_per_night)); ?>đ</small>
                                <?php else: ?>
                                    <div class="fw-bold"><?php echo e(number_format($homestay->price_per_night)); ?>đ</div>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="text-warning small fw-bold d-flex align-items-center justify-content-center gap-1">
                                    <i class="bi bi-star-fill"></i><?php echo e(number_format($avgRating, 1)); ?>

                                </div>
                            </td>
                            <td class="text-center">
                                <?php if($homestay->is_approved): ?>
                                    <span class="admin-badge admin-badge-success">Đã duyệt</span>
                                <?php else: ?>
                                    <span class="admin-badge admin-badge-pending">Chờ duyệt</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="admin-actions d-flex justify-content-center gap-1">
                                    <button class="admin-action-btn" data-bs-toggle="modal" data-bs-target="#homestayModal<?php echo e($homestay->id); ?>" title="Xem chi tiết"><i class="bi bi-eye"></i></button>
                                    <?php if(!$homestay->is_approved): ?>
                                        <form action="<?php echo e(route('admin.homestays.approve', $homestay)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                            <button type="submit" class="admin-action-btn" style="color: var(--admin-success); border-color: var(--admin-success);" title="Duyệt"><i class="bi bi-check-lg"></i></button>
                                        </form>
                                    <?php endif; ?>
                                    <form action="<?php echo e(route('admin.homestays.destroy', $homestay)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Xóa homestay?');">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="admin-action-btn admin-action-btn-danger" title="Xóa"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="7" class="text-center py-5 text-muted">Chưa có dữ liệu.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top d-flex justify-content-center">
            <?php echo e($homestays->links()); ?>

        </div>
    </div>
</div>

<?php $__currentLoopData = $homestays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $homestay): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $modalAvgRating = (float) ($homestay->reviews_avg_rating ?? 0);
    ?>
    <div class="modal fade" id="homestayModal<?php echo e($homestay->id); ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 px-4 pt-4">
                    <h5 class="modal-title fw-bold">Chi tiết homestay: <?php echo e($homestay->title); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <div class="col-lg-5">
                            <?php if($homestay->images->count() > 0): ?>
                                <div id="carousel<?php echo e($homestay->id); ?>" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner rounded-4 shadow-sm overflow-hidden">
                                        <?php $__currentLoopData = $homestay->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="carousel-item <?php echo e($idx == 0 ? 'active' : ''); ?>">
                                                <img src="<?php echo e(asset('storage/' . $img->image_url)); ?>" class="d-block w-100 object-fit-cover" style="height: 350px;">
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                    <?php if($homestay->images->count() > 1): ?>
                                        <button class="carousel-control-prev" data-bs-target="#carousel<?php echo e($homestay->id); ?>" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
                                        <button class="carousel-control-next" data-bs-target="#carousel<?php echo e($homestay->id); ?>" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <div class="mt-4 p-4 bg-light rounded-4">
                                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-person-circle me-2"></i>Chủ sở hữu</h6>
                                <div class="d-flex align-items-center">
                                    <div class="admin-user-avatar-sm me-3 bg-white shadow-sm overflow-hidden d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; font-size: 1.2rem; border-radius: 50%;">
                                        <?php if(!empty($homestay->owner?->avatar_url)): ?>
                                            <img
                                                src="<?php echo e($homestay->owner->avatar_url); ?>"
                                                alt="<?php echo e($homestay->owner->full_name); ?>"
                                                class="w-100 h-100 object-fit-cover"
                                                style="border-radius: 50%;"
                                            >
                                        <?php else: ?>
                                            <?php echo e(mb_strtoupper(mb_substr($homestay->owner?->full_name ?? 'H', 0, 1))); ?>

                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark"><?php echo e($homestay->owner->full_name); ?></div>
                                        <div class="small text-muted"><?php echo e($homestay->owner->email); ?></div>
                                        <div class="small text-muted"><?php echo e($homestay->owner->phone ?? 'Chưa cập nhật SĐT'); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="card border-0 bg-light rounded-4 p-4 h-100">
                                <h6 class="fw-bold text-primary text-uppercase small mb-3" style="letter-spacing: 1px;">Thông tin tổng quan</h6>
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <div class="small text-secondary mb-1">Địa chỉ cụ thể</div>
                                        <div class="fw-bold text-dark mb-3"><?php echo e($homestay->address); ?>, <?php echo e($homestay->ward); ?>, <?php echo e($homestay->district); ?>, <?php echo e($homestay->province); ?></div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="small text-secondary mb-1">Giá thuê mỗi đêm</div>
                                        <div class="d-flex align-items-baseline gap-2">
                                            <?php if($homestay->discounted_price < $homestay->price_per_night): ?>
                                                <div class="h4 fw-bold text-success mb-0"><?php echo e(number_format($homestay->discounted_price)); ?>đ</div>
                                                <div class="text-muted text-decoration-line-through small"><?php echo e(number_format($homestay->price_per_night)); ?>đ</div>
                                                <span class="badge bg-danger-subtle text-danger small rounded-pill">-<?php echo e($homestay->promotion->discount_percent); ?>%</span>
                                            <?php else: ?>
                                                <div class="h4 fw-bold text-dark mb-0"><?php echo e(number_format($homestay->price_per_night)); ?>đ</div>
                                            <?php endif; ?>
                                            <span class="small text-muted">/ đêm</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="small text-secondary mb-1">Sức chứa tối đa</div>
                                        <div class="fw-bold text-dark"><i class="bi bi-people me-2"></i><?php echo e($homestay->max_guests); ?> người</div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="small text-secondary mb-1">Đánh giá trung bình</div>
                                        <div class="fw-bold text-dark"><i class="bi bi-star-fill text-warning me-2"></i><?php echo e(number_format($modalAvgRating, 1)); ?> / 5</div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="small text-secondary mb-1">Số lượt đánh giá</div>
                                        <div class="fw-bold text-dark"><i class="bi bi-chat-dots me-2"></i><?php echo e($homestay->reviews_count ?? 0); ?> lượt</div>
                                    </div>
                                </div>

                                <h6 class="fw-bold text-primary text-uppercase small mt-4 mb-3" style="letter-spacing: 1px;">Mô tả chỗ nghỉ</h6>
                                <div class="text-muted small" style="line-height: 1.6; max-height: 150px; overflow-y: auto;">
                                    <?php echo nl2br(e($homestay->description)); ?>

                                </div>

                                <h6 class="fw-bold text-primary text-uppercase small mt-4 mb-3" style="letter-spacing: 1px;">Tiện nghi cung cấp</h6>
                                <div class="d-flex flex-wrap gap-2">
                                    <?php $__empty_1 = true; $__currentLoopData = $homestays->firstWhere('id', $homestay->id)->amenities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $amenity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <span class="admin-badge admin-badge-success py-1 px-3" style="font-size: 0.75rem;">
                                            <i class="bi bi-check2-circle me-1"></i><?php echo e($amenity->name); ?>

                                        </span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <span class="text-muted small">Chưa có tiện nghi nào được cập nhật.</span>
                                    <?php endif; ?>
                                </div>

                                <h6 class="fw-bold text-primary text-uppercase small mt-4 mb-3" style="letter-spacing: 1px;">Cấu trúc phòng</h6>
                                <div class="row g-2">
                                    <?php $roomTypes = \App\Models\HomestayRoom::ROOM_TYPES; ?>
                                    <?php $__currentLoopData = $homestay->rooms_array; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $qty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($qty > 0 && isset($roomTypes[$type])): ?>
                                            <div class="col-md-6 col-xl-4">
                                                <div class="bg-white border rounded-3 p-2 text-center">
                                                    <div class="small text-muted"><?php echo e($roomTypes[$type]); ?></div>
                                                    <div class="fw-bold text-dark"><?php echo e($qty); ?> phòng</div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if(!$homestay->is_approved): ?>
                    <div class="modal-footer border-0 p-4 pt-0 gap-2">
                        <form action="<?php echo e(route('admin.homestays.approve', $homestay)); ?>" method="POST">
                            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                            <button type="submit" class="admin-create-btn px-4">Duyệt chỗ nghỉ này</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/admin/homestays/index.blade.php ENDPATH**/ ?>