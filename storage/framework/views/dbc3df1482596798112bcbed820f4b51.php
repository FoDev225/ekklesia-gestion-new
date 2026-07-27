

<?php $__env->startSection('title', 'Registre des mariages'); ?>
<?php $__env->startSection('page-title', 'Registre des mariages'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-4">

    
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="<?php echo e(route(auth()->user()->dashboardRoute())); ?>"
               class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
            <span class="text-gray-300">/</span>
            <span class="text-sm text-gray-700 font-medium">Registre des mariages</span>
        </div>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('believers.create')): ?>
        <a href="<?php echo e(route('mariage.create')); ?>"
           class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-md"
           style="background:#3A9BDC">
            + Nouveau mariage
        </a>
        <?php endif; ?>
    </div>

    <?php if(session('success')): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>

    
    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#C9A635">
            <p class="text-xs text-gray-500 uppercase font-medium">Total mariages</p>
            <p class="text-2xl font-bold mt-1" style="color:#C9A635"><?php echo e($stats['total']); ?></p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3FA46A">
            <p class="text-xs text-gray-500 uppercase font-medium">Cette année</p>
            <p class="text-2xl font-bold mt-1" style="color:#3FA46A"><?php echo e($stats['annee']); ?></p>
        </div>
    </div>

    
    <div class="bg-white shadow-sm rounded-lg p-4">
        <form method="GET" action="<?php echo e(route('mariage.index')); ?>"
              class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                placeholder="Nom époux ou épouse..."
                class="col-span-2 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
            <select name="year"
                class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Année</option>
                <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($year); ?>" <?php if(request('year') == $year): echo 'selected'; endif; ?>><?php echo e($year); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <div class="flex gap-2">
                <button type="submit"
                    class="px-4 py-2 text-white text-sm rounded-md" style="background:#3A9BDC">
                    Filtrer
                </button>
                <a href="<?php echo e(route('mariage.index')); ?>"
                   class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded-md hover:bg-gray-300">
                    Reset
                </a>
            </div>
            <span class="col-span-2 md:col-span-4 text-sm text-gray-500 text-right">
                <?php echo e($registers->total()); ?> mariage(s)
            </span>
        </form>
    </div>

    
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Époux</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Épouse</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Mariage civil</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Mariage religieux</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Officiant</th>
                    <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $registers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mariage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-900">
                        <?php echo e($mariage->groom_display_name); ?>

                        <?php if($mariage->groom_id): ?>
                            <span class="text-xs ml-1 px-1.5 py-0.5 bg-blue-100 text-blue-600 rounded">fidèle</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 font-medium text-gray-900">
                        <?php echo e($mariage->bride_display_name); ?>

                        <?php if($mariage->bride_id): ?>
                            <span class="text-xs ml-1 px-1.5 py-0.5 bg-pink-100 text-pink-600 rounded">fidèle</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        <?php echo e($mariage->civil_marriage_date?->format('d/m/Y')); ?><br>
                        <span class="text-xs text-gray-400"><?php echo e($mariage->civil_marriage_place); ?></span>
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        <?php echo e($mariage->religious_marriage_date?->format('d/m/Y')); ?><br>
                        <span class="text-xs text-gray-400"><?php echo e($mariage->religious_marriage_place); ?></span>
                    </td>
                    <td class="px-4 py-3 text-gray-600 text-xs"><?php echo e($mariage->officiant); ?></td>
                    <td class="px-4 py-3 text-center whitespace-nowrap space-x-1">
                        <a href="<?php echo e(route('mariage.show', $mariage)); ?>"
                           class="inline-flex items-center px-2.5 py-1 bg-cyan-100 text-cyan-700 text-xs font-medium rounded">
                            Voir
                        </a>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('believers.edit')): ?>
                        <a href="<?php echo e(route('mariage.edit', $mariage)); ?>"
                           class="inline-flex items-center px-2.5 py-1 bg-yellow-100 text-yellow-700 text-xs font-medium rounded">
                            Modifier
                        </a>
                        <?php endif; ?>
                        <a href="<?php echo e(route('mariage.fiche', $mariage)); ?>"
                           class="inline-flex items-center px-2.5 py-1 text-white text-xs font-medium rounded"
                           style="background:#1a2e4a">
                            📄 PDF
                        </a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                        Aucun mariage enregistré.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?php if($registers->hasPages()): ?>
        <div class="px-4 py-3 border-t border-gray-200">
            <?php echo e($registers->links()); ?>

        </div>
        <?php endif; ?>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ekklesia-gestion\resources\views/mariage/index.blade.php ENDPATH**/ ?>