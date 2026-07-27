
<?php $__env->startSection('title', 'Acteurs de culte'); ?>
<?php $__env->startSection('page-title', 'Gestion des cultes'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto space-y-4">

    <div class="flex items-center gap-3">
        <a href="<?php echo e(route(auth()->user()->dashboardRoute())); ?>" class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
        <span class="text-gray-300">/</span>
        <a href="<?php echo e(route('cultes.periodes')); ?>" class="text-sm text-gray-500 hover:text-gray-700">Cultes</a>
        <span class="text-gray-300">/</span>
        <span class="text-sm font-medium text-gray-700">Acteurs de culte</span>
    </div>

    <?php if(session('success')): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        <ul class="list-disc list-inside text-sm"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h4 class="font-semibold text-gray-700 border-b pb-3 mb-4">Ajouter un acteur</h4>
            <form method="POST" action="<?php echo e(route('cultes.acteurs.store')); ?>" class="space-y-3">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Fidèle <span class="text-red-500">*</span></label>
                    <select name="believer_id" required
                        class="mt-1 block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Sélectionner --</option>
                        <?php $__currentLoopData = $believers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($b->id); ?>"><?php echo e($b->lastname); ?> <?php echo e($b->firstname); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Rôle <span class="text-red-500">*</span></label>
                    <select name="service_role_id" required
                        class="mt-1 block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Choisir un rôle --</option>
                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($role->id); ?>"><?php echo e($role->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <button type="submit"
                    class="w-full px-4 py-2 text-white text-sm rounded-md font-medium" style="background:#3FA46A">
                    + Ajouter
                </button>
            </form>
        </div>

        
        <div class="md:col-span-2 space-y-4">
            <?php $__empty_1 = true; $__currentLoopData = $acteurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roleName => $roleActeurs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white shadow-sm rounded-lg p-5">
                <h4 class="font-semibold border-b pb-2 mb-3 text-sm uppercase tracking-wide" style="color:#1F4E79">
                    <?php echo e($roleName); ?>

                    <span class="ml-2 px-2 py-0.5 rounded-full text-xs font-normal" style="background:#3A9BDC; color:white">
                        <?php echo e($roleActeurs->count()); ?>

                    </span>
                </h4>
                <div class="space-y-1">
                    <?php $__currentLoopData = $roleActeurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acteur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center justify-between py-1.5 border-b border-gray-50 last:border-0">
                        <span class="text-sm text-gray-800"><?php echo e($acteur->believer->full_name); ?></span>
                        <form method="POST" action="<?php echo e(route('cultes.acteurs.destroy', $acteur)); ?>" class="inline"
                              onsubmit="return confirm('Retirer cet acteur ?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="text-red-400 hover:text-red-600 text-xs px-2 py-1 rounded hover:bg-red-50">
                                Retirer
                            </button>
                        </form>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="bg-white shadow-sm rounded-lg p-8 text-center text-gray-400">
                Aucun acteur de culte enregistré.
            </div>
            <?php endif; ?>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ekklesia-gestion\resources\views/cultes/acteurs/index.blade.php ENDPATH**/ ?>