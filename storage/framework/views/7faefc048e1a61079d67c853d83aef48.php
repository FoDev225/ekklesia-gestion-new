

<?php $__env->startSection('title', 'Présentations d\'enfants'); ?>
<?php $__env->startSection('page-title', 'Présentations d\'enfants'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-4">

    
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="<?php echo e(route(auth()->user()->dashboardRoute())); ?>"
               class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
            <span class="text-gray-300">/</span>
            <span class="text-sm text-gray-700 font-medium">Présentations d'enfants</span>
        </div>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('believers.create')): ?>
        <a href="<?php echo e(route('dedication.create')); ?>"
           class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-md"
           style="background:#3A9BDC">
            + Nouvelle présentation
        </a>
        <?php endif; ?>
    </div>

    <?php if(session('success')): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>

    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3A9BDC">
            <p class="text-xs text-gray-500 uppercase font-medium">Total</p>
            <p class="text-2xl font-bold mt-1" style="color:#3A9BDC"><?php echo e($stats['total']); ?></p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#1a2e4a">
            <p class="text-xs text-gray-500 uppercase font-medium">Masculin</p>
            <p class="text-2xl font-bold mt-1" style="color:#1a2e4a"><?php echo e($stats['masculin']); ?></p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#C9A635">
            <p class="text-xs text-gray-500 uppercase font-medium">Féminin</p>
            <p class="text-2xl font-bold mt-1" style="color:#C9A635"><?php echo e($stats['feminin']); ?></p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3FA46A">
            <p class="text-xs text-gray-500 uppercase font-medium">Cette année</p>
            <p class="text-2xl font-bold mt-1" style="color:#3FA46A"><?php echo e($stats['annee']); ?></p>
        </div>
    </div>

    
    <div class="bg-white shadow-sm rounded-lg p-4">
        <form method="GET" action="<?php echo e(route('dedication.index')); ?>"
              class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                placeholder="Nom enfant, père ou mère..."
                class="col-span-2 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
            <select name="gender"
                class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Sexe</option>
                <option value="Masculin" <?php if(request('gender') === 'Masculin'): echo 'selected'; endif; ?>>Masculin</option>
                <option value="Féminin"  <?php if(request('gender') === 'Féminin'): echo 'selected'; endif; ?>>Féminin</option>
            </select>
            <select name="year"
                class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Année</option>
                <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($year); ?>" <?php if(request('year') == $year): echo 'selected'; endif; ?>><?php echo e($year); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <div class="col-span-2 md:col-span-4 flex gap-2">
                <button type="submit"
                    class="px-4 py-2 text-white text-sm rounded-md" style="background:#3A9BDC">
                    Filtrer
                </button>
                <a href="<?php echo e(route('dedication.index')); ?>"
                   class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded-md hover:bg-gray-300">
                    Réinitialiser
                </a>
                <span class="ml-auto text-sm text-gray-500 self-center">
                    <?php echo e($dedications->total()); ?> enregistrement(s)
                </span>
            </div>
        </form>
    </div>

    
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Enfant</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Sexe</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Naissance</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Père</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Mère</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Date présentation</th>
                    <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $dedications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dedication): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-900">
                        <?php echo e($dedication->child_full_name); ?>

                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                            <?php echo e($dedication->gender === 'Masculin' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700'); ?>">
                            <?php echo e($dedication->gender); ?>

                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        <?php echo e($dedication->child_birthdate?->format('d/m/Y')); ?><br>
                        <span class="text-xs text-gray-400"><?php echo e($dedication->child_birthplace); ?></span>
                    </td>
                    <td class="px-4 py-3 text-gray-700 text-xs"><?php echo e($dedication->father_display_name); ?></td>
                    <td class="px-4 py-3 text-gray-700 text-xs"><?php echo e($dedication->mother_display_name); ?></td>
                    <td class="px-4 py-3 text-gray-600">
                        <?php echo e($dedication->dedication_date?->format('d/m/Y')); ?>

                    </td>
                    <td class="px-4 py-3 text-center whitespace-nowrap space-x-1">
                        <a href="<?php echo e(route('dedication.show', $dedication)); ?>"
                           class="inline-flex items-center px-2.5 py-1 bg-cyan-100 text-cyan-700 text-xs font-medium rounded">
                            Voir
                        </a>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('believers.edit')): ?>
                        <a href="<?php echo e(route('dedication.edit', $dedication)); ?>"
                           class="inline-flex items-center px-2.5 py-1 bg-yellow-100 text-yellow-700 text-xs font-medium rounded">
                            Modifier
                        </a>
                        <?php endif; ?>
                        <a href="<?php echo e(route('dedication.fiche', $dedication)); ?>"
                           class="inline-flex items-center px-2.5 py-1 text-white text-xs font-medium rounded"
                           style="background:#1a2e4a">
                            📄 PDF
                        </a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                        Aucune présentation enregistrée.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?php if($dedications->hasPages()): ?>
        <div class="px-4 py-3 border-t border-gray-200">
            <?php echo e($dedications->links()); ?>

        </div>
        <?php endif; ?>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ekklesia-gestion\resources\views/dedication/index.blade.php ENDPATH**/ ?>