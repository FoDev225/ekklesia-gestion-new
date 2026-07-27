

<?php $__env->startSection('title', $team->name); ?>
<?php $__env->startSection('page-title', 'Détail de l\'équipe'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-4">

    
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="<?php echo e(route(auth()->user()->dashboardRoute())); ?>"
            class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewAny', \App\Models\Team::class)): ?>
                <span class="text-gray-300">/</span>
                <a href="<?php echo e(route('teams.index')); ?>" class="text-sm text-gray-500 hover:text-gray-700">Équipes</a>
            <?php endif; ?>
            <span class="text-gray-300">/</span>
            <span class="text-sm text-gray-700 font-medium"><?php echo e($team->name); ?></span>
        </div>
        <div class="flex gap-2">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $team)): ?>
            <a href="<?php echo e(route('teams.edit', $team)); ?>"
            class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-md"
            style="background:#C9A635">
                Modifier
            </a>
            <?php endif; ?>
            <a href="<?php echo e(route(auth()->user()->dashboardRoute())); ?>"
            class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300">
                Retour
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>

    
    <div class="bg-white shadow-sm rounded-lg p-4 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-gray-900"><?php echo e($team->name); ?></h2>
            <span class="text-xs px-1.5 py-0.5 bg-gray-100 text-gray-600 rounded"><?php echo e($team->slug); ?></span>
        </div>
        <a href="<?php echo e(route('teams.members-pdf', $team)); ?>"
           class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-md"
           style="background:#1a2e4a">
            📄 Télécharger liste des membres (PDF)
        </a>
    </div>

    
    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3FA46A">
            <p class="text-xs text-gray-500 uppercase font-medium">Membres</p>
            <p class="text-2xl font-bold mt-1" style="color:#3FA46A"><?php echo e($team->believers->count()); ?></p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3A9BDC">
            <p class="text-xs text-gray-500 uppercase font-medium">Responsable</p>
            <p class="text-sm font-bold mt-2" style="color:#3A9BDC">
                <?php echo e($team->leader->full_name ?? $team->leader->name ?? '—'); ?>

            </p>
        </div>
    </div>

    
    <div class="bg-white shadow-sm rounded-lg p-4">
        <h3 class="text-sm font-semibold text-gray-700 uppercase mb-3">Statistiques du programme d'activité</h3>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
            <div class="rounded-lg p-3 border-l-4" style="border-color:#3A9BDC; background:#f0f9ff">
                <p class="text-xs text-gray-500 uppercase">Programmées</p>
                <p class="text-xl font-bold" style="color:#3A9BDC"><?php echo e($activityStats['total']); ?></p>
            </div>
            <div class="rounded-lg p-3 border-l-4" style="border-color:#3FA46A; background:#f0fdf4">
                <p class="text-xs text-gray-500 uppercase">Réalisées</p>
                <p class="text-xl font-bold" style="color:#3FA46A">
                    <?php echo e($activityStats['realisees']); ?> <span class="text-xs font-normal">(<?php echo e($activityStats['pct_realisees']); ?>%)</span>
                </p>
            </div>
            <div class="rounded-lg p-3 border-l-4" style="border-color:#e11d48; background:#fef2f2">
                <p class="text-xs text-gray-500 uppercase">Non réalisées</p>
                <p class="text-xl font-bold" style="color:#e11d48">
                    <?php echo e($activityStats['non_realisees']); ?> <span class="text-xs font-normal">(<?php echo e($activityStats['pct_non_realisees']); ?>%)</span>
                </p>
            </div>
            <div class="rounded-lg p-3 border-l-4" style="border-color:#C9A635; background:#fefce8">
                <p class="text-xs text-gray-500 uppercase">En cours</p>
                <p class="text-xl font-bold" style="color:#C9A635">
                    <?php echo e($activityStats['en_cours']); ?> <span class="text-xs font-normal">(<?php echo e($activityStats['pct_en_cours']); ?>%)</span>
                </p>
            </div>
            <div class="rounded-lg p-3 border-l-4" style="border-color:#1a2e4a; background:#f8fafc">
                <p class="text-xs text-gray-500 uppercase">Budget total</p>
                <p class="text-lg font-bold" style="color:#1a2e4a"><?php echo e(number_format($activityStats['budget_total'], 0, ',', ' ')); ?> F</p>
            </div>
        </div>

        <div class="flex gap-2 mt-4">
            <a href="<?php echo e(route('teams.activities.program-pdf', $team)); ?>"
               class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-md"
               style="background:#3A9BDC">
                📄 Télécharger programme (PDF)
            </a>
            <a href="<?php echo e(route('teams.activities.report-pdf', $team)); ?>"
               class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-md"
               style="background:#1a2e4a">
                📄 Télécharger rapport d'activités (PDF)
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        
        <div class="md:col-span-2 space-y-4">

            <div class="bg-white shadow-sm rounded-lg p-4">
                <h3 class="text-sm font-semibold text-gray-700 uppercase mb-3">Informations</h3>
                <p class="text-sm text-gray-600">
                    <span class="font-medium text-gray-800">Description :</span>
                    <?php echo e($team->description ?: '—'); ?>

                </p>
            </div>

            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase">Programme d'activité</h3>
                </div>
                <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-3 text-left font-medium text-gray-500 uppercase">Activité</th>
                            <th class="px-3 py-3 text-left font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-3 py-3 text-left font-medium text-gray-500 uppercase">Thème</th>
                            <th class="px-3 py-3 text-left font-medium text-gray-500 uppercase">Modérateur</th>
                            <th class="px-3 py-3 text-left font-medium text-gray-500 uppercase">Prédicateur</th>
                            <th class="px-3 py-3 text-center font-medium text-gray-500 uppercase">Présence</th>
                            <th class="px-3 py-3 text-center font-medium text-gray-500 uppercase">Rapport</th>
                            <th class="px-3 py-3 text-left font-medium text-gray-500 uppercase">Budget</th>
                            <th class="px-3 py-3 text-left font-medium text-gray-500 uppercase">Statut</th>
                            <th class="px-3 py-3 text-center font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-3 font-medium text-gray-900"><?php echo e($activity->title); ?></td>
                            <td class="px-3 py-3 text-gray-600"><?php echo e($activity->date->format('d/m/Y')); ?></td>
                            <td class="px-3 py-3 text-gray-600"><?php echo e($activity->theme ?? '—'); ?></td>
                            <td class="px-3 py-3 text-gray-600"><?php echo e($activity->moderator ?? '—'); ?></td>
                            <td class="px-3 py-3 text-gray-600"><?php echo e($activity->preacher ?? '—'); ?></td>
                            <td class="px-3 py-3 text-center">
                                <?php if($activity->attendance_list_path): ?>
                                    <a href="<?php echo e(Storage::url($activity->attendance_list_path)); ?>" target="_blank"
                                       class="text-xs px-2 py-1 bg-cyan-100 text-cyan-700 rounded">Voir</a>
                                <?php else: ?>
                                    <span class="text-gray-300 text-xs">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-3 py-3 text-center">
                                <?php if($activity->report_path): ?>
                                    <a href="<?php echo e(Storage::url($activity->report_path)); ?>" target="_blank"
                                       class="text-xs px-2 py-1 bg-cyan-100 text-cyan-700 rounded">Voir</a>
                                <?php else: ?>
                                    <span class="text-gray-300 text-xs">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-3 py-3 text-gray-600"><?php echo e($activity->budget ? number_format($activity->budget, 0, ',', ' ') . ' F' : '—'); ?></td>
                            <td class="px-3 py-3">
                                <?php switch($activity->status):
                                    case ('realisee'): ?>
                                        <span class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded">Réalisée</span>
                                        <?php break; ?>
                                    <?php case ('non_realisee'): ?>
                                        <span class="text-xs px-2 py-1 bg-red-100 text-red-700 rounded">Non réalisée</span>
                                        <?php break; ?>
                                    <?php default: ?>
                                        <span class="text-xs px-2 py-1 bg-yellow-100 text-yellow-700 rounded">En cours</span>
                                <?php endswitch; ?>
                            </td>
                            <td class="px-3 py-3 text-center whitespace-nowrap">
                                <?php if($activity->isTerminable()): ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage', $team)): ?>
                                    <button type="button"
                                        onclick="openFinishModal(<?php echo e($activity->id); ?>, '<?php echo e($activity->title); ?>')"
                                        class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded mr-1">
                                        Terminer
                                    </button>
                                    <button type="button"
                                        onclick="openPostponeModal(<?php echo e($activity->id); ?>, '<?php echo e($activity->title); ?>')"
                                        class="text-xs px-2 py-1 bg-orange-100 text-orange-700 rounded">
                                        Ajourner
                                    </button>
                                <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-gray-300 text-xs">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="10" class="px-4 py-8 text-center text-gray-400">
                                Aucune activité programmée.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                </div>
            </div>

            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase">Membres de l'équipe</h3>
                </div>
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Nom</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Membre depuis</th>
                            <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = $team->believers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $believer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-900"><?php echo e($believer->full_name ?? $believer->name); ?></td>
                            <td class="px-4 py-3 text-gray-600">
                                <?php echo e($believer->pivot->joined_at ? \Carbon\Carbon::parse($believer->pivot->joined_at)->format('d/m/Y') : '—'); ?>

                            </td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                <form action="<?php echo e(route('teams.believers.destroy', [$team, $believer])); ?>" method="POST"
                                      onsubmit="return confirm('Retirer ce membre de l\'équipe ?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit"
                                        class="inline-flex items-center px-2.5 py-1 bg-red-100 text-red-700 text-xs font-medium rounded">
                                        Retirer
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-gray-400">Aucun membre pour le moment.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>

        
        <div class="md:col-span-1 space-y-4">

            <div class="bg-white shadow-sm rounded-lg p-4">
                <h3 class="text-sm font-semibold text-gray-700 uppercase mb-3">Programmer une activité</h3>
                <form action="<?php echo e(route('teams.activities.store', $team)); ?>" method="POST" class="space-y-3">
                    <?php echo csrf_field(); ?>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Activité</label>
                        <input type="text" name="title" required
                               class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500 <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('title')); ?>">
                        <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Date</label>
                        <input type="date" name="date" required
                               class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500 <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('date')); ?>">
                        <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Thème</label>
                        <input type="text" name="theme"
                               class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                               value="<?php echo e(old('theme')); ?>">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Président</label>
                        <input type="text" name="moderator"
                               class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                               value="<?php echo e(old('moderator')); ?>">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Prédicateur</label>
                        <input type="text" name="precher"
                               class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                               value="<?php echo e(old('precher')); ?>">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Budget (FCFA)</label>
                        <input type="number" step="0.01" name="budget"
                               class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                               value="<?php echo e(old('budget')); ?>">
                    </div>
                    <button type="submit"
                        class="w-full inline-flex items-center justify-center px-4 py-2 text-white text-sm font-medium rounded-md"
                        style="background:#3A9BDC">
                        Programmer
                    </button>
                </form>
            </div>

            <div class="bg-white shadow-sm rounded-lg p-4">
                <h3 class="text-sm font-semibold text-gray-700 uppercase mb-3">Affecter un fidèle</h3>
                <form action="<?php echo e(route('teams.believers.store', $team)); ?>" method="POST" class="space-y-3">
                    <?php echo csrf_field(); ?>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Fidèle</label>
                        <select name="believer_id" class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500 <?php $__errorArgs = ['believer_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="">— Sélectionner —</option>
                            <?php $__currentLoopData = $availableBelievers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $believer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($believer->id); ?>"><?php echo e($believer->full_name ?? $believer->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['believer_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Date d'adhésion</label>
                        <input type="date" name="joined_at" class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                               value="<?php echo e(old('joined_at', now()->format('Y-m-d'))); ?>">
                    </div>
                    <button type="submit"
                        class="w-full inline-flex items-center justify-center px-4 py-2 text-white text-sm font-medium rounded-md"
                        style="background:#3A9BDC">
                        Affecter
                    </button>
                </form>
            </div>

        </div>

    </div>

</div>


<div id="finishModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="text-sm font-semibold text-gray-700 uppercase mb-4">
            Clôturer l'activité : <span id="finishActivityTitle" class="font-normal normal-case"></span>
        </h3>
        <form id="finishForm" method="POST" enctype="multipart/form-data" class="space-y-3">
            <?php echo csrf_field(); ?>
            <div>
                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Liste de présence (PDF)</label>
                <input type="file" name="attendance_list" accept="application/pdf" required
                       class="w-full border-gray-300 rounded-md text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Rapport (PDF)</label>
                <input type="file" name="report" accept="application/pdf" required
                       class="w-full border-gray-300 rounded-md text-sm">
            </div>
            <div class="flex gap-2 pt-2">
                <button type="submit"
                    class="flex-1 inline-flex items-center justify-center px-4 py-2 text-white text-sm font-medium rounded-md"
                    style="background:#3FA46A">
                    Confirmer
                </button>
                <button type="button" onclick="closeFinishModal()"
                    class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300">
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>


<div id="postponeModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="text-sm font-semibold text-gray-700 uppercase mb-4">
            Ajourner l'activité : <span id="postponeActivityTitle" class="font-normal normal-case"></span>
        </h3>
        <form id="postponeForm" method="POST" class="space-y-3">
            <?php echo csrf_field(); ?>
            <div>
                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Nouvelle date</label>
                <input type="date" name="new_date" required
                       class="w-full border-gray-300 rounded-md text-sm">
            </div>
            <div class="flex gap-2 pt-2">
                <button type="submit"
                    class="flex-1 inline-flex items-center justify-center px-4 py-2 text-white text-sm font-medium rounded-md"
                    style="background:#C9A635">
                    Confirmer
                </button>
                <button type="button" onclick="closePostponeModal()"
                    class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300">
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openFinishModal(activityId, title) {
        document.getElementById('finishActivityTitle').textContent = title;
        document.getElementById('finishForm').action = `/teams/<?php echo e($team->id); ?>/activities/${activityId}/finish`;
        document.getElementById('finishModal').classList.remove('hidden');
    }
    function closeFinishModal() {
        document.getElementById('finishModal').classList.add('hidden');
    }

    function openPostponeModal(activityId, title) {
        document.getElementById('postponeActivityTitle').textContent = title;
        document.getElementById('postponeForm').action = `/teams/<?php echo e($team->id); ?>/activities/${activityId}/postpone`;
        document.getElementById('postponeModal').classList.remove('hidden');
    }
    function closePostponeModal() {
        document.getElementById('postponeModal').classList.add('hidden');
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ekklesia-gestion\resources\views/teams/show.blade.php ENDPATH**/ ?>