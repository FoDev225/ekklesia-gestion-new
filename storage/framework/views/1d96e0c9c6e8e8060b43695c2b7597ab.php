

<?php $__env->startSection('title', 'Gestion des équipes'); ?>
<?php $__env->startSection('page-title', 'Gestion des équipes'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-4">

    
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="<?php echo e(route(auth()->user()->dashboardRoute())); ?>"
               class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
            <span class="text-gray-300">/</span>
            <span class="text-sm text-gray-700 font-medium">Équipes</span>
        </div>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('teams.create')): ?>
        <a href="<?php echo e(route('teams.create')); ?>"
           class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-md"
           style="background:#3A9BDC">
            + Nouvelle équipe
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
            <p class="text-xs text-gray-500 uppercase font-medium">Total équipes</p>
            <p class="text-2xl font-bold mt-1" style="color:#C9A635"><?php echo e($stats['total']); ?></p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3FA46A">
            <p class="text-xs text-gray-500 uppercase font-medium">Total membres</p>
            <p class="text-2xl font-bold mt-1" style="color:#3FA46A"><?php echo e($stats['total_members']); ?></p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3A9BDC">
            <p class="text-xs text-gray-500 uppercase font-medium">Sans responsable</p>
            <p class="text-2xl font-bold mt-1" style="color:#3A9BDC"><?php echo e($stats['sans_responsable']); ?></p>
        </div>
    </div>

    
    <div class="bg-white shadow-sm rounded-lg p-4">
        <form method="GET" action="<?php echo e(route('teams.index')); ?>"
              class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                placeholder="Nom ou slug de l'équipe..."
                class="col-span-2 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
            <select name="leader_id"
                class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Responsable</option>
                <?php $__currentLoopData = $leaders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leader): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($leader->id); ?>" <?php if(request('leader_id') == $leader->id): echo 'selected'; endif; ?>>
                        <?php echo e($leader->full_name ?? $leader->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <div class="flex gap-2">
                <button type="submit"
                    class="px-4 py-2 text-white text-sm rounded-md" style="background:#3A9BDC">
                    Filtrer
                </button>
                <a href="<?php echo e(route('teams.index')); ?>"
                   class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded-md hover:bg-gray-300">
                    Reset
                </a>
            </div>
            <span class="col-span-2 md:col-span-4 text-sm text-gray-500 text-right">
                <?php echo e($teams->total()); ?> équipe(s)
            </span>
        </form>
    </div>

    
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Nom</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Slug</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Responsable</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Membres</th>
                    <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-900">
                        <?php echo e($team->name); ?>

                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        <span class="text-xs px-1.5 py-0.5 bg-gray-100 text-gray-600 rounded"><?php echo e($team->slug); ?></span>
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        <?php echo e($team->leader->full_name ?? $team->leader->name ?? '—'); ?>

                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        <span class="text-xs px-1.5 py-0.5 bg-blue-100 text-blue-600 rounded">
                            <?php echo e($team->believers_count); ?>

                        </span>
                    </td>
                    <td class="px-4 py-3 text-center whitespace-nowrap space-x-1">
                        <a href="<?php echo e(route('teams.show', $team)); ?>"
                           class="inline-flex items-center px-2.5 py-1 bg-cyan-100 text-cyan-700 text-xs font-medium rounded">
                            Voir
                        </a>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $team)): ?>
                        <a href="<?php echo e(route('teams.edit', $team)); ?>"
                           class="inline-flex items-center px-2.5 py-1 bg-yellow-100 text-yellow-700 text-xs font-medium rounded">
                            Modifier
                        </a>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $team)): ?>
                        <form action="<?php echo e(route('teams.destroy', $team)); ?>" method="POST" class="inline"
                              onsubmit="return confirm('Supprimer cette équipe ?');">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit"
                                class="inline-flex items-center px-2.5 py-1 bg-red-100 text-red-700 text-xs font-medium rounded">
                                Supprimer
                            </button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                        Aucune équipe enregistrée.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?php if($teams->hasPages()): ?>
        <div class="px-4 py-3 border-t border-gray-200">
            <?php echo e($teams->links()); ?>

        </div>
        <?php endif; ?>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ekklesia-gestion\resources\views/teams/index.blade.php ENDPATH**/ ?>