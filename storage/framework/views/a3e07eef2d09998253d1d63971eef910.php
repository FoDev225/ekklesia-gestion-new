

<?php $__env->startSection('title', 'Nouvelles personnes'); ?>
<?php $__env->startSection('page-title', 'Nouvelles personnes'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-4">

    
    <div class="flex items-center justify-between">
        <a href="<?php echo e(route(auth()->user()->dashboardRoute())); ?>"
           class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('newcomers.create')): ?>
        <a href="<?php echo e(route('newcomers.create')); ?>"
           class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-md"
           style="background:#3A9BDC">
            + Nouvelle personne
        </a>
        <?php endif; ?>
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
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3A9BDC">
            <p class="text-xs text-gray-500 uppercase">Total</p>
            <p class="text-2xl font-bold mt-1" style="color:#3A9BDC"><?php echo e($stats['total']); ?></p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3FA46A">
            <p class="text-xs text-gray-500 uppercase">Année <?php echo e(now()->year); ?></p>
            <p class="text-2xl font-bold mt-1" style="color:#3FA46A"><?php echo e($stats['annee']); ?></p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#C9A635">
            <p class="text-xs text-gray-500 uppercase">Demeurants</p>
            <p class="text-2xl font-bold mt-1" style="color:#C9A635"><?php echo e($stats['demeurant']); ?></p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-purple-400">
            <p class="text-xs text-gray-500 uppercase">Convertis en fidèles</p>
            <p class="text-2xl font-bold mt-1 text-purple-600"><?php echo e($stats['convertis']); ?></p>
        </div>
    </div>

    
    <div class="bg-white shadow-sm rounded-lg p-4">
        <form method="GET" action="<?php echo e(route('newcomers.index')); ?>"
              class="grid grid-cols-2 md:grid-cols-5 gap-3">

            <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                placeholder="Nom, prénom, téléphone..."
                class="col-span-2 md:col-span-1 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">

            <select name="category"
                class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Catégorie</option>
                <option value="passage"          <?php if(request('category') === 'passage'): echo 'selected'; endif; ?>>De passage</option>
                <option value="court_sejour"     <?php if(request('category') === 'court_sejour'): echo 'selected'; endif; ?>>Court séjour</option>
                <option value="demeurant"        <?php if(request('category') === 'demeurant'): echo 'selected'; endif; ?>>Demeurant</option>
                <option value="nouveau_converti" <?php if(request('category') === 'nouveau_converti'): echo 'selected'; endif; ?>>Nouveau converti</option>
            </select>

            <select name="gender"
                class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Genre</option>
                <option value="M" <?php if(request('gender') === 'M'): echo 'selected'; endif; ?>>Homme</option>
                <option value="F" <?php if(request('gender') === 'F'): echo 'selected'; endif; ?>>Femme</option>
            </select>

            <select name="year"
                class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Année</option>
                <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($year); ?>" <?php if(request('year') == $year): echo 'selected'; endif; ?>><?php echo e($year); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            <div class="col-span-2 md:col-span-5 flex gap-2">
                <button type="submit"
                    class="px-4 py-2 text-white text-sm rounded-md" style="background:#3A9BDC">
                    Filtrer
                </button>
                <a href="<?php echo e(route('newcomers.index')); ?>"
                   class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded-md hover:bg-gray-300">
                    Réinitialiser
                </a>
                <span class="ml-auto text-sm text-gray-500 self-center">
                    <?php echo e($newcomers->total()); ?> personne(s) trouvée(s)
                </span>
            </div>
        </form>
    </div>

    
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Nom & Prénom</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Genre</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Catégorie</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Recommandé</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">1ère visite</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $newcomers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $newcomer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-900">
                        <a href="<?php echo e(route('newcomers.show', $newcomer)); ?>" class="hover:underline"
                           style="color:#3A9BDC">
                            <?php echo e($newcomer->full_name); ?>

                        </a>
                    </td>
                    <td class="px-4 py-3 text-gray-600"><?php echo e($newcomer->gender_label); ?></td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-medium <?php echo e($newcomer->category_color); ?>">
                            <?php echo e($newcomer->category); ?>

                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        <?php if($newcomer->category === 'nouveau_converti'): ?>
                            <span class="text-gray-300 text-xs">N/A</span>
                        <?php elseif($newcomer->is_recommended): ?>
                            <span class="text-green-600 text-xs font-medium">✓ Oui</span>
                            <?php if($newcomer->recommended_by): ?>
                                <span class="text-gray-400 text-xs ml-1">(<?php echo e($newcomer->recommended_by); ?>)</span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="text-red-400 text-xs">✗ Non</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        <?php echo e($newcomer->first_visit_date?->format('d/m/Y') ?? '—'); ?>

                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        <?php echo e($newcomer->phone ?? $newcomer->whatsapp ?? '—'); ?>

                    </td>
                    <td class="px-4 py-3">
                        <?php if($newcomer->is_converted): ?>
                            <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded-full font-medium">
                                Fidèle ✓
                            </span>
                        <?php else: ?>
                            <span class="px-2 py-1 bg-gray-100 text-gray-500 text-xs rounded-full">
                                En suivi
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 text-center whitespace-nowrap space-x-1">
                        <a href="<?php echo e(route('newcomers.show', $newcomer)); ?>"
                           class="inline-flex items-center px-2.5 py-1 bg-cyan-100 text-cyan-700 hover:bg-cyan-200 text-xs font-medium rounded">
                            Voir
                        </a>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('newcomers.edit')): ?>
                        <a href="<?php echo e(route('newcomers.edit', $newcomer)); ?>"
                           class="inline-flex items-center px-2.5 py-1 bg-yellow-100 text-yellow-700 hover:bg-yellow-200 text-xs font-medium rounded">
                            Modifier
                        </a>
                        <?php endif; ?>
                        <?php if($newcomer->category === 'demeurant' && !$newcomer->is_converted): ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('believers.create')): ?>
                        <form method="POST" action="<?php echo e(route('newcomers.convert', $newcomer)); ?>"
                              class="inline"
                              onsubmit="return confirm('Convertir <?php echo e(addslashes($newcomer->full_name)); ?> en fidèle ?')">
                            <?php echo csrf_field(); ?>
                            <button type="submit"
                                class="inline-flex items-center px-2.5 py-1 text-white text-xs font-medium rounded"
                                style="background:#3FA46A">
                                → Fidèle
                            </button>
                        </form>
                        <?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="px-4 py-8 text-center text-gray-400">
                        Aucune nouvelle personne trouvée.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <?php if($newcomers->hasPages()): ?>
        <div class="px-4 py-3 border-t border-gray-200">
            <?php echo e($newcomers->links()); ?>

        </div>
        <?php endif; ?>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ekklesia-gestion\resources\views/newcomers/index.blade.php ENDPATH**/ ?>