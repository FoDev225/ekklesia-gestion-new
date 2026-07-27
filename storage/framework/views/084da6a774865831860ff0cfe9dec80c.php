

<?php $__env->startSection('title', 'Registre funéraire'); ?>
<?php $__env->startSection('page-title', 'Registre funéraire'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-4">

    
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="<?php echo e(route(auth()->user()->dashboardRoute())); ?>"
               class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
            <span class="text-gray-300">/</span>
            <span class="text-sm text-gray-700 font-medium">Registre funéraire</span>
        </div>
        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('believers.create')): ?>
        <a href="<?php echo e(route('funeral.create')); ?>"
           class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-md"
           style="background:#3A9BDC">
            + Nouvel enregistrement
        </a>
        <?php endif; ?>
    </div>

    
    <?php if(session('success')): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>

    
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#1a2e4a">
            <p class="text-xs text-gray-500 uppercase font-medium">Total</p>
            <p class="text-2xl font-bold mt-1" style="color:#1a2e4a"><?php echo e($stats['total']); ?></p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3A9BDC">
            <p class="text-xs text-gray-500 uppercase font-medium">Père</p>
            <p class="text-2xl font-bold mt-1" style="color:#3A9BDC"><?php echo e($stats['pere']); ?></p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#C9A635">
            <p class="text-xs text-gray-500 uppercase font-medium">Mère</p>
            <p class="text-2xl font-bold mt-1" style="color:#C9A635"><?php echo e($stats['mere']); ?></p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3FA46A">
            <p class="text-xs text-gray-500 uppercase font-medium">Enfant</p>
            <p class="text-2xl font-bold mt-1" style="color:#3FA46A"><?php echo e($stats['enfant']); ?></p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-gray-400">
            <p class="text-xs text-gray-500 uppercase font-medium">Cette année</p>
            <p class="text-2xl font-bold mt-1 text-gray-600"><?php echo e($stats['annee']); ?></p>
        </div>
    </div>

    
    <div class="bg-white shadow-sm rounded-lg p-4">
        <form method="GET" action="<?php echo e(route('funeral.index')); ?>"
              class="grid grid-cols-2 md:grid-cols-4 gap-3">

            <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                placeholder="Nom défunt ou fidèle..."
                class="col-span-2 md:col-span-1 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">

            <select name="relationship"
                class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Lien de parenté</option>
                <option value="pere"   <?php if(request('relationship') === 'pere'): echo 'selected'; endif; ?>>Père</option>
                <option value="mere"   <?php if(request('relationship') === 'mere'): echo 'selected'; endif; ?>>Mère</option>
                <option value="enfant" <?php if(request('relationship') === 'enfant'): echo 'selected'; endif; ?>>Enfant biologique</option>
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
                <a href="<?php echo e(route('funeral.index')); ?>"
                   class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded-md hover:bg-gray-300">
                    Réinitialiser
                </a>
                <span class="ml-auto text-sm text-gray-500 self-center">
                    <?php echo e($registers->total()); ?> enregistrement(s)
                </span>
            </div>
        </form>
    </div>

    
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Fidèle</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Défunt</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Lien</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Date décès</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Date funérailles</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Assistance église</th>
                    <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $registers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $register): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <a href="<?php echo e(route('believers.show', $register->believer)); ?>"
                           class="font-medium hover:underline" style="color:#3A9BDC">
                            <?php echo e($register->believer->full_name); ?>

                        </a>
                    </td>
                    <td class="px-4 py-3 font-medium text-gray-800">
                        <?php echo e($register->deceased_full_name); ?>

                    </td>
                    <td class="px-4 py-3">
                        <?php
                            $colors = ['pere' => 'bg-blue-100 text-blue-700', 'mere' => 'bg-yellow-100 text-yellow-700', 'enfant' => 'bg-green-100 text-green-700'];
                        ?>
                        <span class="px-2 py-1 rounded-full text-xs font-medium <?php echo e($colors[$register->family_relationship] ?? 'bg-gray-100 text-gray-600'); ?>">
                            <?php echo e($register->family_relationship_label); ?>

                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        <?php echo e($register->death_date?->format('d/m/Y')); ?>

                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        <?php echo e($register->funeral_date?->format('d/m/Y')); ?>

                    </td>
                    <td class="px-4 py-3 text-gray-600 text-xs">
                        <?php echo e($register->loincloths_number); ?> pagne(s)<br>
                        <span style="color:#3FA46A"><?php echo e($register->amount_paid); ?> FCFA</span>
                    </td>
                    <td class="px-4 py-3 text-center whitespace-nowrap space-x-1">
                        <a href="<?php echo e(route('funeral.show', $register)); ?>"
                           class="inline-flex items-center px-2.5 py-1 bg-cyan-100 text-cyan-700 hover:bg-cyan-200 text-xs font-medium rounded">
                            Voir
                        </a>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('believers.edit')): ?>
                        <a href="<?php echo e(route('funeral.edit', $register)); ?>"
                           class="inline-flex items-center px-2.5 py-1 bg-yellow-100 text-yellow-700 hover:bg-yellow-200 text-xs font-medium rounded">
                            Modifier
                        </a>
                        <?php endif; ?>
                        <a href="<?php echo e(route('funeral.fiche', $register)); ?>"
                           class="inline-flex items-center px-2.5 py-1 text-white text-xs font-medium rounded"
                           style="background:#1a2e4a">
                            📄 PDF
                        </a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                        Aucun enregistrement funéraire.
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
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ekklesia-gestion\resources\views/funeral/index.blade.php ENDPATH**/ ?>