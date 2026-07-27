
<?php $__env->startSection('title', 'Cultes — ' . $periode->name); ?>
<?php $__env->startSection('page-title', 'Gestion des cultes'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-4">

    <div class="flex items-center justify-between flex-wrap gap-2">
        <div class="flex items-center gap-3">
            <a href="<?php echo e(route(auth()->user()->dashboardRoute())); ?>" class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
            <span class="text-gray-300">/</span>
            <a href="<?php echo e(route('cultes.periodes')); ?>" class="text-sm text-gray-500 hover:text-gray-700">Périodes</a>
            <span class="text-gray-300">/</span>
            <span class="text-sm font-medium text-gray-700"><?php echo e($periode->name); ?></span>
        </div>
        <div class="flex gap-2">
            <a href="<?php echo e(route('cultes.programme.pdf', $periode)); ?>"
               class="px-3 py-2 text-white text-sm rounded-md flex items-center gap-1"
               style="background:#1a2e4a">
                📄 Programme PDF
            </a>
            <?php if (\Illuminate\Support\Facades\Blade::check('hasanyrole', 'admin|pasteur|secretariat|direction_culte')): ?>
            <a href="<?php echo e(route('cultes.services.create', $periode)); ?>"
               class="px-4 py-2 text-white text-sm rounded-md" style="background:#3A9BDC">
                + Ajouter un culte
            </a>
            <?php endif; ?>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <ul class="list-disc list-inside text-sm"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
        </div>
    <?php endif; ?>

    
    <div class="bg-white shadow-sm rounded-lg p-4">
        <div class="flex items-center gap-6 text-sm">
            <div>
                <span class="text-gray-500">Période :</span>
                <strong class="ml-1" style="color:#1F4E79"><?php echo e($periode->name); ?></strong>
            </div>
            <?php if($periode->general_theme): ?>
            <div>
                <span class="text-gray-500">Thème :</span>
                <strong class="ml-1"><?php echo e($periode->general_theme); ?></strong>
            </div>
            <?php endif; ?>
            <div>
                <span class="text-gray-500">Du</span>
                <strong class="ml-1"><?php echo e($periode->start_date?->format('d/m/Y')); ?></strong>
                <span class="text-gray-500 ml-1">au</span>
                <strong class="ml-1"><?php echo e($periode->end_date?->format('d/m/Y')); ?></strong>
            </div>
            <div>
                <span class="text-gray-500">Cultes :</span>
                <strong class="ml-1" style="color:#3A9BDC"><?php echo e($services->count()); ?></strong>
            </div>
        </div>
    </div>

    
    <?php if($nextSundayService): ?>
        <div class="bg-white shadow-sm rounded-lg overflow-hidden border-l-4" style="border-color:#3A9BDC; background:#d1f3db">
            <div class="px-5 py-4">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-bold uppercase" style="color:#1F4E79">
                        🗓️ Culte du dimanche prochain — <?php echo e($nextSundayService->service_date->translatedFormat('d F Y')); ?>

                    </h3>
                    <span class="px-2 py-0.5 rounded text-xs font-medium text-white" style="background:#C9A635">
                        <?php echo e($nextSundayService->service_type_label); ?>

                    </span>
                </div>
                <?php
                    $predTit = $nextSundayService->titulaireFor('predicateur');
                    $presTit = $nextSundayService->titulaireFor('president');
                    $louangeGroups = $nextSundayService->worshipGroupsAssigned();
                    $annonceur = $nextSundayService->titulaireFor('annonceur');
                ?>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <p class="text-xs text-orange-500 uppercase">Prédicateur</p>
                        <p class="font-semibold text-gray-800"><?php echo e($predTit?->believer?->full_name ?? '—'); ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-orange-500 uppercase">Président</p>
                        <p class="font-semibold text-gray-800"><?php echo e($presTit?->believer?->full_name ?? '—'); ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-orange-500 uppercase">Louange</p>
                        <p class="font-semibold text-gray-800">
                            <?php echo e($louangeGroups->isNotEmpty() ? $louangeGroups->pluck('name')->join(', ') : '—'); ?>

                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-orange-500 uppercase">Annonces</p>
                        <p class="font-semibold text-gray-800"><?php echo e($annonceur?->believer?->full_name ?? '—'); ?></p>
                    </div>
                </div>
                <?php if($nextSundayService->service_theme): ?>
                    <p class="text-sm text-orange-700 mt-3 italic">Thème : <?php echo e($nextSundayService->service_theme); ?></p>
                <?php endif; ?>
            </div>
        </div>
    <?php elseif(now()->dayOfWeekIso >= 3): ?>
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-sm text-yellow-800">
            ⚠ Aucun culte n'est encore programmé pour le dimanche <?php echo e(\App\Models\Service::currentWeekSunday()->translatedFormat('d F Y')); ?>.
        </div>
    <?php endif; ?>

    
    <?php if (\Illuminate\Support\Facades\Blade::check('hasanyrole', 'admin|pasteur|secretariat|direction_culte')): ?>
    <div class="bg-white shadow-sm rounded-lg p-4">
        <h3 class="text-sm font-semibold text-gray-700 mb-3">Ajouter un culte</h3>
        <form method="POST" action="<?php echo e(route('cultes.services.store', $periode)); ?>"
              class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <?php echo csrf_field(); ?>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Date <span class="text-red-500">*</span></label>
                <input type="date" name="service_date" value="<?php echo e(old('service_date')); ?>"
                    class="block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Type</label>
                <select name="service_type"
                    class="block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="commun">Culte commun</option>
                    <option value="francais">Français</option>
                    <option value="senoufo">Sénoufo</option>
                    <option value="special">Spécial</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs text-gray-500 mb-1">Thème du culte</label>
                <input type="text" name="service_theme" value="<?php echo e(old('service_theme')); ?>"
                    placeholder="Optionnel..."
                    class="block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="md:col-span-4 text-right">
                <button type="submit"
                    class="px-5 py-2 text-white text-sm rounded-md" style="background:#3FA46A">
                    + Ajouter
                </button>
            </div>
        </form>
    </div>
    <?php endif; ?>

    
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Thème</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Prédicateur</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Président</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Louange</th>
                    <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $predicateur = $service->titulaireFor('predicateur');
                    $president   = $service->titulaireFor('president');
                    $louangeGroups = $service->worshipGroupsAssigned();
                ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-800">
                        <?php echo e($service->service_date?->translatedFormat('d/m/Y')); ?>

                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded text-xs font-medium
                            <?php echo e($service->service_type === 'commun' ? 'bg-blue-100 text-blue-700' :
                               ($service->service_type === 'francais' ? 'bg-green-100 text-green-700' :
                               ($service->service_type === 'senoufo' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700'))); ?>">
                            <?php echo e($service->service_type); ?>

                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-600 text-xs max-w-xs truncate">
                        <?php echo e($service->service_theme ?? '—'); ?>

                    </td>
                    <td class="px-4 py-3 text-xs text-gray-700">
                        <?php echo e($predicateur?->believer?->full_name ?? '—'); ?>

                    </td>
                    <td class="px-4 py-3 text-xs text-gray-700">
                        <?php echo e($president?->believer?->full_name ?? '—'); ?>

                    </td>
                    <td class="px-4 py-3 text-xs text-gray-700">
                        <?php if($louangeGroups->isNotEmpty()): ?>
                            <?php echo e($louangeGroups->pluck('name')->join(', ')); ?>

                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </td>
                    <?php if (\Illuminate\Support\Facades\Blade::check('hasanyrole', 'admin|pasteur|secretariat|direction_culte')): ?>
                    <td class="px-4 py-3 text-center whitespace-nowrap space-x-1">
                        <a href="<?php echo e(route('cultes.assignations', $service)); ?>"
                           class="inline-flex items-center px-2.5 py-1 bg-indigo-100 text-indigo-700 text-xs font-medium rounded">
                            Programmer
                        </a>
                        <form method="POST" action="<?php echo e(route('cultes.services.destroy', $service)); ?>"
                              class="inline" onsubmit="return confirm('Supprimer ce culte ?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="inline-flex items-center px-2.5 py-1 bg-red-100 text-red-600 text-xs rounded">
                                Suppr.
                            </button>
                        </form>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">Aucun culte enregistré.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ekklesia-gestion\resources\views/cultes/services/index.blade.php ENDPATH**/ ?>