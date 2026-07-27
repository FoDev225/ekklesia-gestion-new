<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name')); ?> — <?php echo $__env->yieldContent('title'); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css']); ?>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="max-w-xl mx-auto px-4 py-8">

        
        <div class="text-center mb-6">
            <div class="w-16 h-16 rounded-full mx-auto mb-3 flex items-center justify-center"
                 style="background:#3A9BDC">
                <?php if($church->photo_path): ?>
                    <img src="<?php echo e(Storage::url($church->photo_path)); ?>" alt="Logo" class="w-14 h-14 rounded-full object-cover">
                <?php else: ?>
                    <span class="text-white font-bold text-xl">✝</span>
                <?php endif; ?>
            </div>
            <h1 class="text-lg font-bold text-gray-800"><?php echo e($church->organisation_name); ?></h1>
            <p class="text-sm text-gray-500"><?php echo e($church->district); ?> — <?php echo e($church->church_name); ?></p>
        </div>

        <?php echo $__env->yieldContent('content'); ?>

    </div>
</body>
</html><?php /**PATH C:\laragon\www\ekklesia-gestion\resources\views/public/layout.blade.php ENDPATH**/ ?>