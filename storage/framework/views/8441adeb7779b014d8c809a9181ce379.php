

<?php $__env->startSection('title', 'Tableau de bord — J-AEBECI'); ?>
<?php $__env->startSection('page-title', 'Tableau de bord J-AEBECI'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-4">

    
    <div class="bg-white shadow-sm rounded-lg p-4 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-gray-900"><?php echo e($team->name); ?></h2>
            <p class="text-sm text-gray-500 mt-1">
                Responsable : <?php echo e($team->leader->full_name ?? $team->leader->name ?? '—'); ?>

            </p>
        </div>
        <a href="<?php echo e(route('teams.show', $team)); ?>"
           class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-md"
           style="background:#3A9BDC">
            Gérer l'équipe →
        </a>
    </div>

    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3FA46A">
            <p class="text-xs text-gray-500 uppercase font-medium">Membres</p>
            <p class="text-2xl font-bold mt-1" style="color:#3FA46A"><?php echo e($team->believers->count()); ?></p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3A9BDC">
            <p class="text-xs text-gray-500 uppercase font-medium">Activités programmées</p>
            <p class="text-2xl font-bold mt-1" style="color:#3A9BDC"><?php echo e($activityStats['total']); ?></p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#C9A635">
            <p class="text-xs text-gray-500 uppercase font-medium">Taux de réalisation</p>
            <p class="text-2xl font-bold mt-1" style="color:#C9A635"><?php echo e($activityStats['pct_realisees']); ?>%</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#1a2e4a">
            <p class="text-xs text-gray-500 uppercase font-medium">Budget total</p>
            <p class="text-lg font-bold mt-1" style="color:#1a2e4a">
                <?php echo e(number_format($activityStats['budget_total'], 0, ',', ' ')); ?> F
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        
        <div class="md:col-span-2">
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase">Prochaines activités</h3>
                    <a href="<?php echo e(route('teams.show', $team)); ?>" class="text-xs text-blue-600 hover:underline">
                        Voir tout →
                    </a>
                </div>
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Activité</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Thème</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = $prochainesActivites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-900"><?php echo e($activity->title); ?></td>
                            <td class="px-4 py-3 text-gray-600"><?php echo e($activity->date->format('d/m/Y')); ?></td>
                            <td class="px-4 py-3 text-gray-600"><?php echo e($activity->theme ?? '—'); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-gray-400">
                                Aucune activité programmée à venir.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        
        <div class="md:col-span-1">
            <div class="bg-white shadow-sm rounded-lg p-4">
                <h3 class="text-sm font-semibold text-gray-700 uppercase mb-3">Actions rapides</h3>
                <div class="space-y-2">
                    <a href="<?php echo e(route('teams.show', $team)); ?>"
                       class="flex items-center gap-2 px-3 py-2 rounded-md bg-gray-50 hover:bg-gray-100 text-sm text-gray-700">
                        🤝 Gérer l'équipe & les membres
                    </a>
                    <a href="<?php echo e(route('teams.show', $team)); ?>#programme"
                       class="flex items-center gap-2 px-3 py-2 rounded-md bg-gray-50 hover:bg-gray-100 text-sm text-gray-700">
                        📅 Programmer une activité
                    </a>
                    <a href="<?php echo e(route('teams.members-pdf', $team)); ?>"
                       class="flex items-center gap-2 px-3 py-2 rounded-md bg-gray-50 hover:bg-gray-100 text-sm text-gray-700">
                        📄 Liste des membres (PDF)
                    </a>
                    <a href="<?php echo e(route('teams.activities.program-pdf', $team)); ?>"
                       class="flex items-center gap-2 px-3 py-2 rounded-md bg-gray-50 hover:bg-gray-100 text-sm text-gray-700">
                        📄 Programme d'activité (PDF)
                    </a>
                    <a href="<?php echo e(route('teams.activities.report-pdf', $team)); ?>"
                       class="flex items-center gap-2 px-3 py-2 rounded-md bg-gray-50 hover:bg-gray-100 text-sm text-gray-700">
                        📄 Rapport d'activités (PDF)
                    </a>
                </div>
            </div>
        </div>

    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ekklesia-gestion\resources\views/dashboards/jeunes.blade.php ENDPATH**/ ?>