
<?php $__env->startSection('title', 'Programmer le culte du ' . $service->service_date?->format('d/m/Y')); ?>
<?php $__env->startSection('page-title', 'Gestion des cultes'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto space-y-4">

    <div class="flex items-center gap-3">
        <a href="<?php echo e(route(auth()->user()->dashboardRoute())); ?>" class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
        <span class="text-gray-300">/</span>
        <a href="<?php echo e(route('cultes.periodes')); ?>" class="text-sm text-gray-500 hover:text-gray-700">Périodes</a>
        <span class="text-gray-300">/</span>
        <a href="<?php echo e(route('cultes.services', $service->periode)); ?>" class="text-sm text-gray-500 hover:text-gray-700"><?php echo e($service->periode->name); ?></a>
        <span class="text-gray-300">/</span>
        <span class="text-sm font-medium text-gray-700"><?php echo e($service->service_date?->format('d/m/Y')); ?></span>
    </div>

    <?php if(session('success')): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        <ul class="list-disc list-inside text-sm"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
    </div>
    <?php endif; ?>

    
    <div class="bg-white shadow-sm rounded-lg p-4 flex items-center justify-between">
        <div>
            <h3 class="font-bold text-gray-800 text-lg">
                Culte du <?php echo e($service->service_date?->translatedFormat('l d F Y')); ?>

            </h3>
            <div class="flex items-center gap-3 mt-1">
                <span class="px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700">
                    <?php echo e($service->service_type_label); ?>

                </span>
                <?php if($service->service_theme): ?>
                <span class="text-sm text-gray-500"><?php echo e($service->service_theme); ?></span>
                <?php endif; ?>
            </div>
        </div>
        <div class="text-xs text-gray-400"><?php echo e($service->assignments->count()); ?> attribution(s)</div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h4 class="font-semibold text-gray-700 border-b pb-3 mb-4">Ajouter une attribution</h4>
            <form method="POST" action="<?php echo e(route('cultes.assignations.store', $service)); ?>" class="space-y-3">
                <?php echo csrf_field(); ?>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Rôle <span class="text-red-500">*</span></label>
                    <select name="service_role_id" id="role_select" required
                        onchange="onRoleChange(this)"
                        class="mt-1 block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Choisir un rôle --</option>
                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($role->id); ?>" data-slug="<?php echo e($role->slug); ?>"><?php echo e($role->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div id="acteur_field">
                    <label class="block text-sm font-medium text-gray-700">Acteur <span class="text-red-500">*</span></label>
                    <select name="believer_id" id="acteur_select"
                        class="mt-1 block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Sélectionner un acteur --</option>
                        <?php $__currentLoopData = $acteurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roleId => $roleActeurs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $__currentLoopData = $roleActeurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acteur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($acteur->believer_id); ?>"
                                    data-role="<?php echo e($acteur->service_role_id); ?>"
                                    class="acteur-option">
                                <?php echo e($acteur->believer->full_name); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div id="worship_group_field" class="hidden">
                    <label class="block text-sm font-medium text-gray-700">Groupe de louange <span class="text-red-500">*</span></label>
                    <select name="worship_group_id" id="worship_group_select"
                        class="mt-1 block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Sélectionner un groupe --</option>
                        <?php $__currentLoopData = $worshipGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($group->id); ?>"><?php echo e($group->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <p class="text-xs text-gray-400 mt-1">
                        <?php echo e($louangeCount); ?> / <?php echo e($maxGroups); ?> groupe(s) déjà programmé(s) pour ce culte
                    </p>
                </div>

                <div id="backup_field">
                    <label class="flex items-center gap-2 cursor-pointer text-sm">
                        <input type="checkbox" name="is_backup" value="1" class="rounded border-gray-300 text-indigo-600">
                        Suppléant
                    </label>
                </div>

                <button type="submit"
                    class="w-full px-4 py-2 text-white text-sm rounded-md font-medium" style="background:#3A9BDC">
                    + Ajouter l'attribution
                </button>
            </form>
        </div>

        
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h4 class="font-semibold text-gray-700 border-b pb-3 mb-4">Attributions actuelles</h4>

            <?php
                $grouped = $service->assignments->groupBy('role.name');
            ?>

            <?php $__empty_1 = true; $__currentLoopData = $grouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roleName => $assignments): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="mb-4">
                <p class="text-xs font-bold uppercase text-gray-500 mb-2" style="color:#1F4E79"><?php echo e($roleName); ?></p>
                <?php $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                    <div class="flex items-center gap-2">
                        <?php if($assignment->worshipGroup): ?>
                            <span class="text-sm text-gray-800"><?php echo e($assignment->worshipGroup->name); ?></span>
                            <span class="px-1.5 py-0.5 bg-purple-100 text-purple-600 text-xs rounded">Groupe</span>
                        <?php else: ?>
                            <span class="text-sm text-gray-800"><?php echo e($assignment->believer?->full_name); ?></span>
                            <?php if($assignment->is_backup): ?>
                                <span class="px-1.5 py-0.5 bg-yellow-100 text-black text-xs rounded">Suppléant</span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <div class="flex items-center gap-2">
                        <?php if($assignment->is_backup && !$assignment->worshipGroup): ?>
                        <form method="POST" action="<?php echo e(route('cultes.assignations.promote', $assignment)); ?>" class="inline">
                            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                            <button type="submit" class="text-xs text-blue-500 hover:text-blue-700 hover:underline">
                                Promouvoir
                            </button>
                        </form>
                        <?php endif; ?>
                        <form method="POST"
                            action="<?php echo e(route('cultes.assignations.destroy', $assignment)); ?>" class="inline">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="text-red-400 hover:text-red-600 text-xs">✕</button>
                        </form>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-gray-400 text-sm">Aucune attribution pour ce culte.</p>
            <?php endif; ?>
        </div>

    </div>
</div>

<script>
    const acteurs = <?php echo json_encode($acteurs, 15, 512) ?>;

    function onRoleChange(select) {
        const selectedOption = select.options[select.selectedIndex];
        const slug = selectedOption?.dataset.slug;
        const isLouange = slug === 'louange';

        document.getElementById('acteur_field').classList.toggle('hidden', isLouange);
        document.getElementById('worship_group_field').classList.toggle('hidden', !isLouange);
        document.getElementById('backup_field').classList.toggle('hidden', isLouange);

        document.getElementById('acteur_select').required = !isLouange;
        document.getElementById('worship_group_select').required = isLouange;

        if (!isLouange) {
            filterActeurs(select.value);
        }
    }

    function filterActeurs(roleId) {
        const select = document.getElementById('acteur_select');
        const options = select.querySelectorAll('.acteur-option');
        options.forEach(opt => {
            opt.hidden = roleId && opt.dataset.role !== roleId;
        });
        select.value = '';
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ekklesia-gestion\resources\views/cultes/services/assignations.blade.php ENDPATH**/ ?>