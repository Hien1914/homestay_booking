<?php $__env->startSection('title', 'Quản lý bài viết'); ?>

<?php $__env->startSection('content'); ?>

<div class="admin-page-header">
    <div class="admin-page-header-content">
        <h1 class="admin-page-title"><?php echo $__env->yieldContent('title'); ?></h1>
        <p class="admin-page-subtitle">Quản lý bài viết và tin tức của hệ thống</p>
    </div>
    <div class="admin-page-actions">
        <a href="<?php echo e(route('admin.posts.create')); ?>" class="admin-create-btn">
            <i class="bi bi-plus-lg"></i>
            Tạo mới
        </a>
    </div>
</div>

<!-- Statistics -->
<div class="admin-stats-grid admin-stats-grid-3">
    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-primary">
            <i class="bi bi-newspaper"></i>
        </div>
        <div class="admin-stat-content">
            <div class="admin-stat-value"><?php echo e($totalPost); ?></div>
            <div class="admin-stat-label">Tổng bài viết</div>
        </div>
    </div>

    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-success">
            <i class="bi bi-check-circle"></i>
        </div>
        <div class="admin-stat-content">
            <div class="admin-stat-value"><?php echo e($activePosts); ?></div>
            <div class="admin-stat-label">Bài viết hiển thị</div>
        </div>
    </div>

    <div class="admin-stat-card">
        <div class="admin-stat-icon admin-stat-icon-danger">
            <i class="bi bi-x-circle"></i>
        </div>
        <div class="admin-stat-content">
            <div class="admin-stat-value"><?php echo e($draftPosts); ?></div>
            <div class="admin-stat-label">Bài viết nháp</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm mb-4 rounded-3">
    <div class="card-body p-4">
        <form method="GET" action="<?php echo e(route('admin.posts.index')); ?>" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label small fw-bold text-secondary">Từ ngày</label>
                <input type="date" name="from_date" class="form-control" value="<?php echo e($fromDate); ?>">
            </div>
            <div class="col-md-5">
                <label class="form-label small fw-bold text-secondary">Đến ngày</label>
                <input type="date" name="to_date" class="form-control" value="<?php echo e($toDate); ?>">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="admin-filter-btn w-100 justify-content-center">Lọc</button>
                <a href="<?php echo e(route('admin.posts.index')); ?>"
                    class="admin-filter-clear-btn w-100 justify-content-center">Xóa</a>
            </div>
        </form>
    </div>
</div>

<!-- Posts Table -->
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white py-3 border-light-subtle">
        <h5 class="card-title mb-0 fw-bold h6">
            <i class="bi bi-newspaper me-2 text-primary"></i>Danh sách bài viết
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ảnh</th>
                        <th>Tiêu đề</th>
                        <th>Ngày tạo</th>
                        <th>Trạng thái</th>
                        <th>Lượt xem</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true;
                    $__currentLoopData = $posts;
                    $__env->addLoop($__currentLoopData);
                    foreach ($__currentLoopData as $item):
                        $__env->incrementLoopIndices();
                        $loop = $__env->getLastLoop();
                        $__empty_1 = false; ?>
                        <tr>
                            <td><span class="admin-id-badge">#<?php echo e($item->id); ?></span></td>
                            <td>
                                <?php if ($item->thumbnail_url): ?>
                                    <img src="<?php echo e(asset('storage/' . $item->thumbnail_url)); ?>"
                                        alt="<?php echo e($item->title); ?>" class="admin-thumbnail">
                                <?php else: ?>
                                    <div class="admin-thumbnail d-flex align-items-center justify-content-center bg-light">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-bold text-dark"><?php echo e(Str::limit($item->title, 60)); ?></div>
                                <?php if ($item->description): ?>
                                    <div class="small text-muted text-truncate" style="max-width: 250px;">
                                        <?php echo e($item->description); ?></div>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($item->created_at->format('d/m/Y')); ?></td>
                            <td>
                                <?php if ($item->status === 'published'): ?>
                                    <span class="admin-badge admin-badge-confirmed">Xuất bản</span>
                                <?php else: ?>
                                    <span class="admin-badge admin-badge-pending">Bản nháp</span>
                                <?php endif; ?>
                            </td>
                            <td><span class="admin-badge admin-badge-ongoing"><?php echo e($item->views); ?></span></td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="<?php echo e(route('admin.posts.edit', $item->id)); ?>"
                                        class="admin-action-btn" title="Chỉnh sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="admin-action-btn admin-action-btn-danger" title="Xóa"
                                        onclick="deletePost(<?php echo e($item->id); ?>, '<?php echo e(addslashes($item->title)); ?>')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach;
                    $__env->popLoop();
                    $loop = $__env->getLastLoop();
                    if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="bi bi-newspaper fs-2 d-block mb-2"></i> Chưa có bài viết nào
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            <?php echo e($posts->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function deletePost(id, title) {
        if (!confirm(`Bạn có chắc chắn muốn xóa bài viết "${title}"?`)) return;

        fetch(`/admin/posts/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: '_method=DELETE'
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message || 'Xóa thành công!');
                    location.reload();
                } else {
                    alert(data.message || 'Có lỗi xảy ra!');
                }
            })
            .catch(error => {
                alert('Có lỗi xảy ra!');
                console.error('Error:', error);
            });
    }
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/admin/posts/index.blade.php ENDPATH**/ ?>