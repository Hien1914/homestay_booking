

<?php $__env->startSection('title', 'Cẩm Nang & Tin Tức Du Lịch'); ?>

<?php $__env->startSection('content'); ?>
<main class="blog-page">
    <div class="container">
        <div class="blog-header">
            <h1 class="blog-title">Cẩm Nang & Tin Tức Du Lịch</h1>
            <p class="blog-subtitle">Tuyển tập những kinh nghiệm du lịch, mẹo hay và cập nhật homestay mới nhất dành riêng cho bạn</p>
        </div>
        
        <div class="blog-grid">
            <?php $__empty_1 = true; $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $imageUrl = $post->image;
                    if (!$imageUrl) {
                        $imageUrl = 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=600&h=400&fit=crop';
                    } elseif (!Str::startsWith($imageUrl, ['http://', 'https://'])) {
                        // Backend lưu ảnh dạng "/storage/posts/...", ta dùng asset() để tự sinh full URL
                        $imageUrl = asset($imageUrl);
                    }
                ?>
                <a href="<?php echo e(route('blog.show', $post->slug)); ?>" class="blog-card" style="display: block; text-decoration: none; color: inherit;">
                    <div class="blog-card-image">
                        <img src="<?php echo e($imageUrl); ?>" alt="<?php echo e($post->title); ?>">
                        <span class="blog-card-category">Bài viết</span>
                    </div>
                    <div class="blog-card-content">
                        <h2 class="blog-card-title">
                            <?php echo e($post->title); ?>

                        </h2>
                        <p class="blog-card-excerpt" style="color: #5f7066;"><?php echo e($post->description ?? Str::limit(strip_tags($post->content), 120)); ?></p>
                        <div class="blog-card-meta">
                            <span class="blog-read-more" style="color:#003b0d; font-weight:600; display:flex; align-items:center; gap:5px;">
                                Xem chi tiết <i class="fas fa-arrow-right" style="font-size:0.8rem;"></i>
                            </span>
                            <span class="blog-card-date" style="color:#666; font-size:0.85rem;">
                                <i class="far fa-eye"></i> <?php echo e(number_format($post->views ?? 0)); ?> lượt xem
                            </span>
                        </div>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Chưa có bài viết nào được xuất bản.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if($posts->hasPages()): ?>
        <div class="blog-pagination" style="margin-top: 40px; text-align: center;">
            <?php echo e($posts->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</main>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
<style>
.blog-page {
    padding: 40px 0 80px;
    background: #f8faf8;
    min-height: 70vh;
}

.blog-header {
    text-align: center;
    margin-bottom: 40px;
}

.blog-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #1a2e1a;
    margin: 0 0 12px;
}

.blog-subtitle {
    font-size: 1.1rem;
    color: #5f7066;
    margin: 0;
    max-width: 600px;
    margin: 0 auto;
}

.blog-categories {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-bottom: 40px;
    flex-wrap: wrap;
}

.blog-category-btn {
    padding: 10px 20px;
    border: 1px solid #d7e3d2;
    border-radius: 999px;
    background: white;
    font-size: 0.9rem;
    font-weight: 500;
    color: #5f7066;
    cursor: pointer;
    transition: all 0.2s;
}

.blog-category-btn:hover {
    border-color: #003b0d;
    color: #003b0d;
}

.blog-category-btn.is-active {
    background: #003b0d;
    border-color: #003b0d;
    color: white;
}

.blog-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
}

@media (max-width: 992px) {
    .blog-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 576px) {
    .blog-grid {
        grid-template-columns: 1fr;
    }
    
    .blog-title {
        font-size: 1.8rem;
    }
    
    .blog-subtitle {
        font-size: 1rem;
    }
}

.blog-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    transition: all 0.3s;
}

.blog-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
}

.blog-card-image {
    display: block;
    position: relative;
    height: 200px;
    overflow: hidden;
}

.blog-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.blog-card:hover .blog-card-image img {
    transform: scale(1.08);
}

.blog-card-category {
    position: absolute;
    top: 14px;
    left: 14px;
    padding: 6px 14px;
    background: white;
    border-radius: 999px;
    font-size: 0.78rem;
    font-weight: 600;
    color: #003b0d;
}

.blog-card-content {
    padding: 20px;
}

.blog-card-title {
    margin: 0 0 12px;
    font-size: 1.1rem;
    font-weight: 700;
    line-height: 1.4;
}

.blog-card-title a {
    color: #1a2e1a;
    text-decoration: none;
    transition: color 0.2s;
}

.blog-card-title a:hover {
    color: #003b0d;
}

.blog-card-excerpt {
    margin: 0 0 16px;
    font-size: 0.9rem;
    color: #5f7066;
    line-height: 1.6;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.blog-card-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 16px;
    border-top: 1px solid #eef3ec;
}

.blog-card-author {
    display: flex;
    align-items: center;
    gap: 10px;
}

.blog-author-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
}

.blog-author-name {
    font-size: 0.85rem;
    font-weight: 600;
    color: #1a2e1a;
}

.blog-card-date {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.82rem;
    color: #5f7066;
}

.blog-card-date i {
    font-size: 0.85rem;
}

.blog-pagination {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-top: 50px;
}

.blog-page-btn {
    min-width: 42px;
    height: 42px;
    padding: 0 14px;
    border: 1px solid #d7e3d2;
    border-radius: 8px;
    background: white;
    font-size: 0.9rem;
    font-weight: 500;
    color: #1a2e1a;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.blog-page-btn:hover:not(:disabled) {
    border-color: #003b0d;
    color: #003b0d;
}

.blog-page-btn.is-active {
    background: #003b0d;
    border-color: #003b0d;
    color: white;
}

.blog-page-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
</style>

<script>
document.querySelectorAll('.blog-category-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.blog-category-btn').forEach(b => b.classList.remove('is-active'));
        this.classList.add('is-active');
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('clients.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/clients/blog/index.blade.php ENDPATH**/ ?>