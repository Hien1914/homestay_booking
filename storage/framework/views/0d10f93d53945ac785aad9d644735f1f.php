<?php if($paginator->hasPages()): ?>
    <nav class="custom-pagination">
        
        <?php if($paginator->onFirstPage()): ?>
            <span class="pagination-link pagination-arrow disabled"><i class="bi bi-chevron-left"></i></span>
        <?php else: ?>
            <a href="<?php echo e($paginator->previousPageUrl()); ?>" class="pagination-link pagination-arrow" rel="prev"><i class="bi bi-chevron-left"></i></a>
        <?php endif; ?>

        
        <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            
            <?php if(is_string($element)): ?>
                <span class="pagination-link disabled"><?php echo e($element); ?></span>
            <?php endif; ?>

            
            <?php if(is_array($element)): ?>
                <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($page == $paginator->currentPage()): ?>
                        <span class="pagination-link active"><?php echo e($page); ?></span>
                    <?php else: ?>
                        <a href="<?php echo e($url); ?>" class="pagination-link"><?php echo e($page); ?></a>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <?php if($paginator->hasMorePages()): ?>
            <a href="<?php echo e($paginator->nextPageUrl()); ?>" class="pagination-link pagination-arrow" rel="next"><i class="bi bi-chevron-right"></i></a>
        <?php else: ?>
            <span class="pagination-link pagination-arrow disabled"><i class="bi bi-chevron-right"></i></span>
        <?php endif; ?>
    </nav>
<?php endif; ?>
<?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/vendor/pagination/custom.blade.php ENDPATH**/ ?>