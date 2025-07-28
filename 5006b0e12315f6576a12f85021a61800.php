[2025-05-06 03:15:49] production.ERROR: View [welcome] not found. {"exception":"[object] (InvalidArgumentException(code: 0): View [welcome] not found. at /home2/hydrosite/public_html/vendor/laravel/framework/src/Illuminate/View/FileViewFinder.php:139)
[stacktrace]
#0 /home2/hydrosite/public_html/vendor/laravel/framework/src/Illuminate/View/FileViewFinder.php(79): Illuminate\\View\\FileViewFinder->findInPaths()
#1 /home2/hydrosi<?php $__env->startSection('title', __('Server Error')); ?>
<?php $__env->startSection('code', '500'); ?>
<?php $__env->startSection('message', __('Server Error')); ?>

<?php echo $__env->make('errors::minimal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home2/hydrosite/public_html/vendor/laravel/framework/src/Illuminate/Foundation/Exceptions/views/500.blade.php ENDPATH**/ ?>