<?php $__env->startSection('title', $post->title); ?>

<?php $__env->startSection('content'); ?>
<main class="blog-detail-page">
    <div class="container container-setting">
        <header class="blog-detail-header">
            <h1 class="blog-detail-title"><?php echo e($post->title); ?></h1>
            <div class="blog-detail-meta">
                <span class="blog-detail-date">
                    <i class="far fa-calendar-alt"></i>
                    <?php echo e($post->created_at->format('d/m/Y H:i')); ?>

                </span>
                <span class="blog-detail-views">
                    <i class="far fa-eye"></i>
                    <?php echo e(number_format($post->views ?? 0)); ?> lượt xem
                </span>
            </div>
            
            <?php if($post->description): ?>
                <p class="blog-detail-description"><?php echo e($post->description); ?></p>
            <?php endif; ?>
        </header>

        <div class="blog-detail-content">
            <?php echo $post->content; ?>

        </div>

        <?php if($relatedPosts->count() > 0): ?>
        <section class="related-posts">
            <h3 class="related-title">Bài viết liên quan</h3>
            <div class="blog-grid">
                <?php $__currentLoopData = $relatedPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rd_post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $imageUrl = $rd_post->image;
                        if (!$imageUrl) {
                            $imageUrl = 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=600&h=400&fit=crop';
                        } elseif (!Str::startsWith($imageUrl, ['http://', 'https://'])) {
                            $imageUrl = asset($imageUrl);
                        }
                    ?>
                    <a href="<?php echo e(route('blog.show', $rd_post->slug)); ?>" class="blog-card" style="display: block; text-decoration: none; color: inherit;">
                        <div class="blog-card-image">
                            <img src="<?php echo e($imageUrl); ?>" alt="<?php echo e($rd_post->title); ?>">
                            <span class="blog-card-category">Bài viết</span>
                        </div>
                        <div class="blog-card-content">
                            <h2 class="blog-card-title">
                                <?php echo e($rd_post->title); ?>

                            </h2>
                            <p class="blog-card-excerpt" style="color: #5f7066;"><?php echo e($rd_post->description ?? Str::limit(strip_tags($rd_post->content), 120)); ?></p>
                            <div class="blog-card-meta">
                                <span class="blog-read-more" style="color:#003b0d; font-weight:600; display:flex; align-items:center; gap:5px;">
                                    Xem chi tiết <i class="fas fa-arrow-right" style="font-size:0.8rem;"></i>
                                </span>
                                <span class="blog-card-date" style="color:#666; font-size:0.85rem;">
                                    <i class="far fa-eye"></i> <?php echo e(number_format($rd_post->views ?? 0)); ?> lượt xem
                                </span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </section>
        <?php endif; ?>
    </div>
</main>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<style>
.blog-detail-page {
    padding: 40px 0 80px;
    background: #fff;
    min-height: 70vh;
}

.blog-detail-header {
    text-align: center;
    max-width: 800px;
    margin: 0 auto 40px;
}

.blog-detail-title {
    font-size: 2.2rem;
    font-weight: 700;
    color: #1a2e1a;
    line-height: 1.3;
    margin-bottom: 20px;
}

.blog-detail-meta {
    display: flex;
    justify-content: center;
    gap: 24px;
    color: #666;
    font-size: 0.95rem;
    margin-bottom: 20px;
}

.blog-detail-meta i {
    margin-right: 6px;
}

.blog-detail-description {
    font-size: 1.1rem;
    color: #4a5c51;
    font-style: italic;
    background: #f8faf8;
    padding: 20px;
    border-left: 4px solid #003b0d;
    border-radius: 4px;
    text-align: left;
    margin: 0;
}

.blog-detail-content {
    max-width: 1000px;
    margin: 0 auto 60px;
    line-height: 1.8;
    font-size: 1.1rem;
    color: #333;
    word-break: break-word;
}

.blog-detail-content img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 20px 0;
}

.blog-detail-content h2, .blog-detail-content h3 {
    margin-top: 30px;
    margin-bottom: 15px;
    color: #1a2e1a;
}

/* Related Posts Section */
.related-posts {
    margin-top: 60px;
    padding-top: 50px;
    border-top: 1px solid #eaeaea;
}

.related-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: #1a2e1a;
    margin-bottom: 30px;
    text-align: center;
}

/* Tái sử dụng CSS chia cột grid */
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
    .blog-detail-title {
        font-size: 1.6rem;
    }
}

/* Card Styling */
.blog-card {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    transition: all 0.3s;
    border: 1px solid #f2f2f2;
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
    color: #1a2e1a;
}

.blog-card-excerpt {
    margin: 0 0 16px;
    font-size: 0.9rem;
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
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('clients.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/clients/blog/show.blade.php ENDPATH**/ ?>