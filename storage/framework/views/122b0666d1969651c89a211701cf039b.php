
<?php $__env->startSection('title', 'Périodes des cultes'); ?>
<?php $__env->startSection('page-title', 'Gestion des cultes'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-4">

    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="<?php echo e(route(auth()->user()->dashboardRoute())); ?>" class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
            <span class="text-gray-300">/</span>
            <span class="text-sm font-medium text-gray-700">Périodes</span>
        </div>
        <div class="flex gap-2">
            <a href="<?php echo e(route('cultes.acteurs')); ?>"
               class="px-3 py-2 text-white text-sm rounded-md" style="background:#C9A635">
                👥 Acteurs de culte
            </a>
            <a href="<?php echo e(route('cultes.periodes.create')); ?>"
               class="px-4 py-2 text-white text-sm rounded-md" style="background:#3A9BDC">
                + Nouvelle période
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Période</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Thème général</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Du</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Au</th>
                    <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase">Cultes</th>
                    <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase">Statut</th>
                    <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $periodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $periode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50 <?php echo e($periode->is_active ? 'bg-blue-50' : ''); ?>">
                    <td class="px-4 py-3 font-medium text-gray-900"><?php echo e($periode->name); ?></td>
                    <td class="px-4 py-3 text-gray-600 text-xs max-w-xs"><?php echo e($periode->general_theme ?? '—'); ?></td>
                    <td class="px-4 py-3 text-gray-600"><?php echo e($periode->start_date?->format('d/m/Y')); ?></td>
                    <td class="px-4 py-3 text-gray-600"><?php echo e($periode->end_date?->format('d/m/Y')); ?></td>
                    <td class="px-4 py-3 text-center">
                        <span class="font-bold" style="color:#3A9BDC"><?php echo e($periode->services_count); ?></span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <?php if($periode->is_archive): ?>
                            <span class="px-2 py-1 bg-gray-100 text-gray-500 text-xs rounded-full">Archivée</span>
                        <?php elseif($periode->is_active): ?>
                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full font-medium">● Active</span>
                        <?php else: ?>
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full">En attente</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 text-center whitespace-nowrap space-x-1">
                        <a href="<?php echo e(route('cultes.services', $periode)); ?>"
                           class="inline-flex items-center px-2.5 py-1 bg-cyan-100 text-cyan-700 text-xs font-medium rounded">
                            Cultes
                        </a>
                        <a href="<?php echo e(route('cultes.programme.pdf', $periode)); ?>"
                           class="inline-flex items-center px-2.5 py-1 text-white text-xs font-medium rounded"
                           style="background:#1a2e4a">
                            📄 PDF
                        </a>
                        <?php if(!$periode->is_active && !$periode->is_archive): ?>
                        <form method="POST" action="<?php echo e(route('cultes.periode.activate', $periode)); ?>" class="inline">
                            <?php echo csrf_field(); ?>
                            <button class="inline-flex items-center px-2.5 py-1 bg-green-100 text-green-700 text-xs font-medium rounded">
                                Activer
                            </button>
                        </form>
                        <?php endif; ?>
                        <?php if(!$periode->is_archive): ?>
                        <form method="POST" action="<?php echo e(route('cultes.periode.archive', $periode)); ?>" class="inline"
                              onsubmit="return confirm('Archiver cette période ?')">
                            <?php echo csrf_field(); ?>
                            <button class="inline-flex items-center px-2.5 py-1 bg-gray-100 text-gray-500 text-xs rounded">
                                Archiver
                            </button>
                        </form>
                        <?php endif; ?>
                        <a href="<?php echo e(route('cultes.periodes.edit', $periode)); ?>"
                           class="inline-flex items-center px-2.5 py-1 bg-yellow-100 text-yellow-700 text-xs font-medium rounded">
                            Modifier
                        </a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">Aucune période enregistrée.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?php if($periodes->hasPages()): ?>
        <div class="px-4 py-3 border-t"><?php echo e($periodes->links()); ?></div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ekklesia-gestion\resources\views/cultes/periodes/index.blade.php ENDPATH**/ ?>