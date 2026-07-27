

<?php $__env->startSection('title', 'Gestion des fidèles'); ?>
<?php $__env->startSection('page-title', 'Gestion des fidèles'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-4">

    
    <div class="flex items-center justify-between">
        <a href="<?php echo e(route(auth()->user()->dashboardRoute())); ?>"
           class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700">
            ← Retour au dashboard
        </a>
        <div class="flex items-center gap-2 flex-wrap">
            
            <a href="<?php echo e(route('believers.export.pdf', request()->only(['gender','marital_status','age_group','team_id','status']))); ?>"
               class="inline-flex items-center gap-1 px-3 py-2 text-white text-xs font-medium rounded-md"
               style="background:#e53e3e" title="Exporter la liste en PDF">
                📄 PDF
            </a>
 
            
            <a href="<?php echo e(route('believers.export.excel', request()->only(['gender','marital_status','age_group','team_id','status']))); ?>"
               class="inline-flex items-center gap-1 px-3 py-2 text-white text-xs font-medium rounded-md"
               style="background:#3FA46A" title="Exporter la liste en Excel">
                📊 Excel
            </a>
 
            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('believers.create')): ?>
            <a href="<?php echo e(route('believers.import.form')); ?>"
               class="inline-flex items-center gap-1 px-3 py-2 text-white text-xs font-medium rounded-md"
               style="background:#C9A635" title="Importer une liste Excel">
                ⬆ Import
            </a>
            <?php endif; ?>
 
            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('believers.create')): ?>
            <a href="<?php echo e(route('believers.create')); ?>"
               class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-md"
               style="background:#3A9BDC">
                + Nouveau fidèle
            </a>
            <?php endif; ?>
        </div>
    </div>

            
            <?php if(session('success')): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if(session('import_result')): ?>
                <?php $result = session('import_result'); ?>

                <?php if($result['imported'] > 0): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-3">
                    ✅ <?php echo e($result['imported']); ?> fidèle(s) importé(s) avec succès.
                    <?php if($result['skipped'] > 0): ?>
                        <?php echo e($result['skipped']); ?> ligne(s) ignorée(s).
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <?php if(count($result['errors']) > 0): ?>
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded mb-3">
                    <p class="font-semibold mb-2">⚠ Détail des lignes ignorées :</p>
                    <ul class="list-disc list-inside text-sm space-y-1">
                        <?php $__currentLoopData = $result['errors']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
                <?php endif; ?>
            <?php endif; ?>

            
            <div class="bg-white shadow-sm rounded-lg p-4">
                <form method="GET" action="<?php echo e(route('believers.index')); ?>" class="grid grid-cols-2 md:grid-cols-5 gap-3">
                    <input
                        type="text"
                        name="search"
                        value="<?php echo e(request('search')); ?>"
                        placeholder="Nom, prénom, CNI..."
                        class="col-span-2 md:col-span-1 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                    />

                    <select name="gender" class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Genre</option>
                        <option value="M" <?php if(request('gender') === 'M'): echo 'selected'; endif; ?>>Homme</option>
                        <option value="F" <?php if(request('gender') === 'F'): echo 'selected'; endif; ?>>Femme</option>
                    </select>

                    <select name="marital_status" class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Situation maritale</option>
                        <option value="Célibataire" <?php if(request('marital_status') === 'Célibataire'): echo 'selected'; endif; ?>>Célibataire</option>
                        <option value="Marié(e)"       <?php if(request('marital_status') === 'Marié(e)'): echo 'selected'; endif; ?>>Marié(e)</option>
                        <option value="Veuf(ve)"        <?php if(request('marital_status') === 'Veuf(ve)'): echo 'selected'; endif; ?>>Veuf(ve)</option>
                        <option value="Divorcé"     <?php if(request('marital_status') === 'Divorcé'): echo 'selected'; endif; ?>>Divorcé</option>
                    </select>

                    <select name="age_group" class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Tranche d'âge</option>
                        <option value="nourrisson"   <?php if(request('age_group') === 'nourrisson'): echo 'selected'; endif; ?>>Nourrisson (0-2)</option>
                        <option value="pre_scolaire" <?php if(request('age_group') === 'pre_scolaire'): echo 'selected'; endif; ?>>Pré-scolaire (3-4)</option>
                        <option value="ecodim"       <?php if(request('age_group') === 'ecodim'): echo 'selected'; endif; ?>>ECODIM (5-18)</option>
                        <option value="jeunes"       <?php if(request('age_group') === 'jeunes'): echo 'selected'; endif; ?>>Jeunes (19-40)</option>
                        <option value="adultes"      <?php if(request('age_group') === 'adultes'): echo 'selected'; endif; ?>>Adultes (41+)</option>
                    </select>

                    <select name="team_id" class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Équipe</option>
                        <?php $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($team->id); ?>" <?php if(request('team_id') == $team->id): echo 'selected'; endif; ?>>
                                <?php echo e($team->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>

                    <div class="col-span-2 md:col-span-5 flex gap-2">
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700">
                            Filtrer
                        </button>
                        <a href="<?php echo e(route('believers.index')); ?>"
                           class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded-md hover:bg-gray-300">
                            Réinitialiser
                        </a>
                        <span class="ml-auto text-sm text-gray-500 self-center">
                            <?php echo e($believers->total()); ?> fidèle(s) trouvé(s)
                        </span>
                    </div>
                </form>
            </div>

            
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Nom & Prénom</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Genre</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Tranche d'âge</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Situation</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Équipes</th>
                            <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = $believers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $believer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-400"><?php echo e($i + 1); ?></td>
                            <td class="px-4 py-3 font-medium text-gray-900">
                                <a href="<?php echo e(route('believers.show', $believer)); ?>" class="hover:text-indigo-600">
                                    <?php echo e($believer->full_name); ?>

                                </a>
                                <?php if($believer->is_sanctioned ?? false): ?>
                                    <span class="ml-1 px-1.5 py-0.5 bg-red-100 text-red-600 text-xs rounded">Sanction</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-gray-600"><?php echo e($believer->gender_label); ?></td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-xs font-medium <?php echo e($believer->age_group_color); ?>">
                                    <?php echo e($believer->age_group); ?>

                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600"><?php echo e($believer->marital_status); ?></td>
                            <td class="px-4 py-3 text-gray-600">
                                <?php echo e($believer->address?->whatsapp ?? '—'); ?>

                            </td>
                            <td class="px-4 py-3">
                                <?php $__currentLoopData = $believer->teams->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="inline-block px-2 py-0.5 bg-indigo-100 text-indigo-700 text-xs rounded mr-1">
                                        <?php echo e($team->name); ?>

                                    </span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php if($believer->teams->count() > 2): ?>
                                    <span class="text-xs text-gray-400">+<?php echo e($believer->teams->count() - 2); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-center whitespace-nowrap space-x-1">
                                
                                <a href="<?php echo e(route('believers.show', $believer)); ?>"
                                   class="inline-flex items-center px-2.5 py-1 bg-cyan-100 text-cyan-700 hover:bg-cyan-200 text-xs font-medium rounded">
                                    Voir
                                </a>

                                
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('believers.edit')): ?>
                                <a href="<?php echo e(route('believers.edit', $believer)); ?>"
                                   class="inline-flex items-center px-2.5 py-1 bg-yellow-100 text-yellow-700 hover:bg-yellow-200 text-xs font-medium rounded">
                                    Modifier
                                </a>
                                <?php endif; ?>

                                
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('believers.edit')): ?>
                                    <?php if($believer->sanctions()->where('is_active', true)->exists()): ?>
                                        <button type="button"
                                            onclick="openLiftSanctionModal(<?php echo e($believer->id); ?>, '<?php echo e(addslashes($believer->full_name)); ?>')"
                                            class="inline-flex items-center px-2.5 py-1 bg-green-500 text-white hover:bg-green-400 text-xs font-medium rounded">
                                            Lever la sanction
                                        </button>
                                    <?php else: ?>
                                        <button type="button"
                                            onclick="openSanctionModal(<?php echo e($believer->id); ?>, '<?php echo e(addslashes($believer->full_name)); ?>')"
                                            class="inline-flex items-center px-2.5 py-1 bg-red-400 text-white hover:bg-red-500 text-xs font-medium rounded">
                                            Sanctionner
                                        </button>
                                    <?php endif; ?>
                                <?php endif; ?>
                                
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('believers.edit')): ?>
                                <?php if(!in_array($believer->status, ['parti', 'decede'])): ?>
                                <button type="button"
                                    onclick="openDepartModal(<?php echo e($believer->id); ?>, '<?php echo e(addslashes($believer->full_name)); ?>')"
                                    class="inline-flex items-center px-2.5 py-1 bg-gray-200 text-gray-700 hover:bg-gray-300 text-xs font-medium rounded">
                                    Départ
                                </button>
                                <?php endif; ?>
                                <?php if($believer->status === 'parti'): ?>
                                <form method="POST" action="<?php echo e(route('believers.reinstate', $believer)); ?>" class="inline">
                                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                    <button type="submit"
                                        onclick="return confirm('Réintégrer <?php echo e(addslashes($believer->full_name)); ?> ?')"
                                        class="inline-flex items-center px-2.5 py-1 bg-blue-100 text-blue-700 hover:bg-blue-200 text-xs font-medium rounded">
                                        Réintégrer
                                    </button>
                                </form>
                                <?php endif; ?>
                                <?php if($believer->status === 'decede'): ?>
                                <span class="inline-flex items-center px-2.5 py-1 bg-gray-100 text-gray-400 text-xs rounded">
                                    🕊 Décédé
                                </span>
                                <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-gray-400">
                                Aucun fidèle trouvé.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                
                <?php if($believers->hasPages()): ?>
                <div class="px-4 py-3 border-t border-gray-200">
                    <?php echo e($believers->links()); ?>

                </div>
                <?php endif; ?>
            </div>

        </div>
    </div>

<?php echo $__env->make('believers.partials.departure', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<?php echo $__env->make('believers.partials.sanction-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<?php echo $__env->make('believers.partials.lift-sanction-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<script>
    // ── Départ / Décès ──
    function openDepartModal(believerId, believerName) {
        document.getElementById('modal-depart-name').textContent = believerName;
        document.getElementById('form-depart').action = '/believers/' + believerId + '/depart';
        document.getElementById('modal-depart').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeDepartModal() {
        document.getElementById('modal-depart').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        document.getElementById('form-depart').reset();
        document.getElementById('destination-field').classList.remove('hidden');
    }

    function toggleDestination(type) {
        document.getElementById('destination-field').classList.toggle('hidden', type === 'deces');
    }
    // ── Sanction ──
    function openSanctionModal(believerId, believerName) {
        document.getElementById('modal-believer-name').textContent = believerName;
        document.getElementById('form-sanction').action = '/believers/' + believerId + '/sanction';
        document.getElementById('modal-sanction').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeSanctionModal() {
        document.getElementById('modal-sanction').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        document.getElementById('form-sanction').reset();
    }

    // ── Lever la sanction ──
    function openLiftSanctionModal(believerId, believerName) {
        document.getElementById('lift-modal-believer-name').textContent = believerName;
        document.getElementById('form-lift-sanction').action = '/believers/' + believerId + '/lift-sanction';
        document.getElementById('lift-modal-sanction').classList.remove('hidden'); // ← ID corrigé
        document.body.classList.add('overflow-hidden');
    }

    function closeLiftSanctionModal() {
        document.getElementById('lift-modal-sanction').classList.add('hidden'); // ← ID corrigé
        document.body.classList.remove('overflow-hidden');
        document.getElementById('form-lift-sanction').reset();
    }

    // Fermeture avec Échap
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeSanctionModal();
            closeLiftSanctionModal();
        }
    });
</script>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ekklesia-gestion\resources\views/believers/index.blade.php ENDPATH**/ ?>