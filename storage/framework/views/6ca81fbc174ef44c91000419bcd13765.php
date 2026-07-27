

<?php $__env->startSection('title', 'Sanctions disciplinaires'); ?>
<?php $__env->startSection('page-title', 'Sanctions disciplinaires'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-4">

    
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="<?php echo e(route(auth()->user()->dashboardRoute())); ?>"
               class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
            <span class="text-gray-300">/</span>
            <a href="<?php echo e(route('believers.index')); ?>"
               class="text-sm text-gray-500 hover:text-gray-700">Fidèles</a>
            <span class="text-gray-300">/</span>
            <span class="text-sm text-gray-700 font-medium">Sanctions disciplinaires</span>
        </div>
    </div>

    
    <?php if(session('success')): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>

    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#1a2e4a">
            <p class="text-xs text-gray-500 uppercase font-medium">Total</p>
            <p class="text-2xl font-bold mt-1" style="color:#1a2e4a"><?php echo e($stats['total']); ?></p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-red-400">
            <p class="text-xs text-gray-500 uppercase font-medium">Actives</p>
            <p class="text-2xl font-bold mt-1 text-red-500"><?php echo e($stats['actives']); ?></p>
            <p class="text-xs text-gray-400 mt-1">En cours</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3FA46A">
            <p class="text-xs text-gray-500 uppercase font-medium">Levées</p>
            <p class="text-2xl font-bold mt-1" style="color:#3FA46A"><?php echo e($stats['levees']); ?></p>
            <p class="text-xs text-gray-400 mt-1">Terminées</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3A9BDC">
            <p class="text-xs text-gray-500 uppercase font-medium">Cette année</p>
            <p class="text-2xl font-bold mt-1" style="color:#3A9BDC"><?php echo e($stats['annee']); ?></p>
        </div>
    </div>

    
    <div class="bg-white shadow-sm rounded-lg p-4">
        <form method="GET" action="<?php echo e(route('sanctions.index')); ?>"
              class="grid grid-cols-2 md:grid-cols-4 gap-3">

            <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                placeholder="Nom, prénom..."
                class="col-span-2 md:col-span-1 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">

            <select name="status"
                class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Toutes</option>
                <option value="active" <?php if(request('status') === 'active'): echo 'selected'; endif; ?>>🔴 Actives</option>
                <option value="levee"  <?php if(request('status') === 'levee'): echo 'selected'; endif; ?>>🟢 Levées</option>
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
                <a href="<?php echo e(route('sanctions.index')); ?>"
                   class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded-md hover:bg-gray-300">
                    Réinitialiser
                </a>
                <span class="ml-auto text-sm text-gray-500 self-center">
                    <?php echo e($sanctions->total()); ?> sanction(s)
                </span>
            </div>
        </form>
    </div>

    
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Fidèle</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Date début</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Date fin</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Motif</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Décidé par</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $sanctions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sanction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <a href="<?php echo e(route('believers.show', $sanction->believer)); ?>"
                           class="font-medium hover:underline" style="color:#3A9BDC">
                            <?php echo e($sanction->believer->full_name); ?>

                        </a>
                        <p class="text-xs text-gray-400">
                            <?php echo e($sanction->believer->gender_label); ?>

                            <?php if($sanction->believer->age): ?>
                                · <?php echo e($sanction->believer->age); ?> ans
                            <?php endif; ?>
                        </p>
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        <?php echo e($sanction->start_date?->format('d/m/Y') ?? '—'); ?>

                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        <?php echo e($sanction->end_date?->format('d/m/Y') ?? 'Indéterminée'); ?>

                    </td>
                    <td class="px-4 py-3 text-gray-600 max-w-xs">
                        <span class="line-clamp-2"><?php echo e($sanction->reason ?? '—'); ?></span>
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs">
                        <?php echo e($sanction->decided_by ?? '—'); ?>

                    </td>
                    <td class="px-4 py-3">
                        <?php if($sanction->is_active): ?>
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-600">
                                🔴 Active
                            </span>
                        <?php else: ?>
                            <div>
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                    🟢 Levée
                                </span>
                                <?php if($sanction->lifted_at): ?>
                                    <p class="text-xs text-gray-400 mt-1">
                                        le <?php echo e($sanction->lifted_at->format('d/m/Y')); ?>

                                    </p>
                                <?php endif; ?>
                                <?php if($sanction->lift_note): ?>
                                    <p class="text-xs text-gray-400 italic mt-0.5 line-clamp-1">
                                        <?php echo e($sanction->lift_note); ?>

                                    </p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <?php if($sanction->is_active): ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('believers.edit')): ?>
                            <button type="button"
                                onclick="openLiftModal(<?php echo e($sanction->id); ?>, '<?php echo e(addslashes($sanction->believer->full_name)); ?>')"
                                class="inline-flex items-center px-3 py-1.5 text-white text-xs font-medium rounded-md"
                                style="background:#3FA46A">
                                ✓ Lever la sanction
                            </button>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="text-xs text-gray-400 italic">Terminée</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                        Aucune sanction enregistrée.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <?php if($sanctions->hasPages()): ?>
        <div class="px-4 py-3 border-t border-gray-200">
            <?php echo e($sanctions->links()); ?>

        </div>
        <?php endif; ?>
    </div>

</div>


<div id="modal-lift"
     class="fixed inset-0 z-50 hidden overflow-y-auto"
     role="dialog" aria-modal="true">

    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75" onclick="closeLiftModal()"></div>

        <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full z-10">

            
            <div class="flex items-center justify-between px-6 py-4 border-b"
                 style="background:#3FA46A">
                <h3 class="text-lg font-semibold text-white">
                    ✓ Lever une sanction disciplinaire
                </h3>
                <button type="button" onclick="closeLiftModal()"
                    class="text-white hover:text-gray-200 text-xl font-bold leading-none">
                    &times;
                </button>
            </div>

            
            <form id="form-lift" method="POST" action="">
                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>

                <div class="px-6 py-5 space-y-4">

                    <p class="text-sm text-gray-600">
                        Vous êtes sur le point de lever la sanction disciplinaire de
                        <strong id="lift-believer-name" class="text-gray-900"></strong>.
                        Le fidèle repassera au statut <strong>Actif</strong>.
                    </p>

                    <div class="bg-green-50 border border-green-200 rounded p-3 text-xs text-green-700">
                        ℹ La sanction sera conservée dans l'historique avec la date de levée.
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Observation sur la levée
                            <span class="text-gray-400 text-xs">(optionnel)</span>
                        </label>
                        <textarea name="lift_note" rows="3"
                            placeholder="Ex: Repentance sincère, décision du conseil..."
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>

                </div>

                
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                    <button type="button" onclick="closeLiftModal()"
                        class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-md hover:bg-gray-200">
                        Annuler
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-white text-sm font-medium rounded-md"
                        style="background:#3FA46A">
                        ✓ Confirmer la levée
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    function openLiftModal(sanctionId, believerName) {
        document.getElementById('lift-believer-name').textContent = believerName;
        document.getElementById('form-lift').action = '/sanctions/' + sanctionId + '/lift';
        document.getElementById('modal-lift').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeLiftModal() {
        document.getElementById('modal-lift').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        document.getElementById('form-lift').reset();
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeLiftModal();
    });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ekklesia-gestion\resources\views/believers/sanctions/sanction.blade.php ENDPATH**/ ?>