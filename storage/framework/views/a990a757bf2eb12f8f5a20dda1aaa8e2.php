<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="icon" type="image/svg+xml" href="<?php echo e(asset('favicon.svg')); ?>">
    <link rel="alternate icon" href="<?php echo e(asset('favicon.ico')); ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Stylesheets -->
    <link rel="stylesheet" href="<?php echo e(asset('css/variable.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/clients/layout.css?v=2.0')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/clients/responsive.css?v=1.2')); ?>">

    <title><?php echo $__env->yieldContent('title', 'NestAway'); ?> | Homestay Việt Nam</title>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php echo $__env->make('clients.layout.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <main class="flex-grow-1">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <?php echo $__env->make('clients.layout.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\Study\KLTN\homestay_booking\resources\views/clients/layout/app.blade.php ENDPATH**/ ?>