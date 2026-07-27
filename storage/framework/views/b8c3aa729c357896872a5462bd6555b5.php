

<?php $__env->startSection('title', 'Départs & Décès'); ?>
<?php $__env->startSection('page-title', 'Départs & Décès'); ?>

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
            <span class="text-sm text-gray-700 font-medium">Départs & Décès</span>
        </div>
    </div>

    
    <?php if(session('success')): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        <?php echo e(session('error')); ?>

    </div>
    <?php endif; ?>

    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#1a2e4a">
            <p class="text-xs text-gray-500 uppercase font-medium">Total enregistrés</p>
            <p class="text-2xl font-bold mt-1" style="color:#1a2e4a"><?php echo e($stats['total']); ?></p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#C9A635">
            <p class="text-xs text-gray-500 uppercase font-medium">Départs</p>
            <p class="text-2xl font-bold mt-1" style="color:#C9A635"><?php echo e($stats['departs']); ?></p>
            <p class="text-xs text-gray-400 mt-1">Réintégration possible</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-gray-400">
            <p class="text-xs text-gray-500 uppercase font-medium">Décès</p>
            <p class="text-2xl font-bold mt-1 text-gray-500"><?php echo e($stats['deces']); ?></p>
            <p class="text-xs text-gray-400 mt-1">Définitif</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3A9BDC">
            <p class="text-xs text-gray-500 uppercase font-medium">Cette année</p>
            <p class="text-2xl font-bold mt-1" style="color:#3A9BDC"><?php echo e($stats['annee']); ?></p>
        </div>
    </div>

    
    <div class="bg-white shadow-sm rounded-lg p-4">
        <form method="GET" action="<?php echo e(route('departures.index')); ?>"
              class="grid grid-cols-2 md:grid-cols-4 gap-3">

            <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                placeholder="Nom, prénom..."
                class="col-span-2 md:col-span-1 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">

            <select name="type"
                class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Type</option>
                <option value="depart" <?php if(request('type') === 'depart'): echo 'selected'; endif; ?>>🚶 Départ</option>
                <option value="deces"  <?php if(request('type') === 'deces'): echo 'selected'; endif; ?>>🕊 Décès</option>
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
                <a href="<?php echo e(route('departures.index')); ?>"
                   class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded-md hover:bg-gray-300">
                    Réinitialiser
                </a>
                <span class="ml-auto text-sm text-gray-500 self-center">
                    <?php echo e($departures->total()); ?> enregistrement(s)
                </span>
            </div>
        </form>
    </div>

    
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Fidèle</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Destination</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Motif</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Enregistré par</th>
                    <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $departures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $departure): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <a href="<?php echo e(route('believers.show', $departure->believer)); ?>"
                           class="font-medium hover:underline" style="color:#3A9BDC">
                            <?php echo e($departure->believer->full_name); ?>

                        </a>
                        <p class="text-xs text-gray-400">
                            <?php echo e($departure->believer->gender_label); ?>

                            <?php if($departure->believer->age): ?>
                                · <?php echo e($departure->believer->age); ?> ans
                            <?php endif; ?>
                        </p>
                    </td>
                    <td class="px-4 py-3">
                        <?php if($departure->type === 'depart'): ?>
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                🚶 Départ
                            </span>
                        <?php else: ?>
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                🕊 Décès
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        <?php echo e($departure->departure_date?->format('d/m/Y') ?? '—'); ?>

                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        <?php echo e($departure->destination ?? '—'); ?>

                    </td>
                    <td class="px-4 py-3 text-gray-600 max-w-xs">
                        <span class="line-clamp-2"><?php echo e($departure->reason ?? '—'); ?></span>
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs">
                        <?php echo e($departure->recorded_by ?? '—'); ?>

                    </td>
                    <td class="px-4 py-3 text-center">
                        <?php if($departure->type === 'depart'): ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('believers.edit')): ?>
                            <button type="button"
                                onclick="openReinstateModal(<?php echo e($departure->believer->id); ?>, '<?php echo e(addslashes($departure->believer->full_name)); ?>')"
                                class="inline-flex items-center px-3 py-1.5 text-white text-xs font-medium rounded-md"
                                style="background:#3A9BDC">
                                ↩ Réintégrer
                            </button>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="text-xs text-gray-400 italic">Définitif</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                        Aucun départ enregistré.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <?php if($departures->hasPages()): ?>
        <div class="px-4 py-3 border-t border-gray-200">
            <?php echo e($departures->links()); ?>

        </div>
        <?php endif; ?>
    </div>

</div>

<div id="modal-reinstate"
     class="fixed inset-0 z-50 hidden overflow-y-auto"
     role="dialog" aria-modal="true">

    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75" onclick="closeReinstateModal()"></div>

        <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full z-10">

            
            <div class="flex items-center justify-between px-6 py-4 border-b"
                 style="background:#3A9BDC">
                <h3 class="text-lg font-semibold text-white">
                    ↩ Réintégration d'un fidèle
                </h3>
                <button type="button" onclick="closeReinstateModal()"
                    class="text-white hover:text-gray-200 text-xl font-bold leading-none">
                    &times;
                </button>
            </div>

            
            <form id="form-reinstate" method="POST" action="">
                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>

                <div class="px-6 py-5 space-y-4">

                    <p class="text-sm text-gray-600">
                        Vous êtes sur le point de réintégrer
                        <strong id="reinstate-believer-name" class="text-gray-900"></strong>
                        dans la communauté. Le fidèle repassera au statut <strong>Actif</strong>
                        et réapparaîtra dans la liste des fidèles.
                    </p>

                    <div class="bg-blue-50 border border-blue-200 rounded p-3 text-xs text-blue-700">
                        ℹ L'enregistrement de départ sera supprimé de cette liste.
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Motif de réintégration
                            <span class="text-gray-400 text-xs">(optionnel)</span>
                        </label>
                        <textarea name="reinstate_note" rows="3"
                            placeholder="Ex: Retour définitif dans la ville, réconciliation..."
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>

                </div>

                
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                    <button type="button" onclick="closeReinstateModal()"
                        class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-md hover:bg-gray-200">
                        Annuler
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-white text-sm font-medium rounded-md"
                        style="background:#3A9BDC">
                        ✓ Confirmer la réintégration
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    function openReinstateModal(believerId, believerName) {
        document.getElementById('reinstate-believer-name').textContent = believerName;
        document.getElementById('form-reinstate').action = '/believers/' + believerId + '/reinstate';
        document.getElementById('modal-reinstate').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeReinstateModal() {
        document.getElementById('modal-reinstate').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        document.getElementById('form-reinstate').reset();
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeReinstateModal();
    });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ekklesia-gestion\resources\views/believers/departures/departure.blade.php ENDPATH**/ ?>