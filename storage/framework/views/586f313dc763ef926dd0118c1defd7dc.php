<?php $__env->startSection('title', $post->title); ?>

<?php $__env->startSection('content'); ?>
<style>
    @import url('<?php echo e(asset('css/clients/blog.css')); ?>');
</style>
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





<?php echo $__env->make('clients.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/clients/blog/show.blade.php ENDPATH**/ ?>