

<?php $__env->startSection('title', $group->name); ?>
<?php $__env->startSection('page-title', 'Détail du groupe'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-4">

    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="<?php echo e(route(auth()->user()->dashboardRoute())); ?>"
               class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
            <span class="text-gray-300">/</span>
            <a href="<?php echo e(route('groups.index')); ?>" class="text-sm text-gray-500 hover:text-gray-700">Groupes</a>
            <span class="text-gray-300">/</span>
            <span class="text-sm text-gray-700 font-medium"><?php echo e($group->name); ?></span>
        </div>
        <div class="flex gap-2">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('groups.edit')): ?>
            <a href="<?php echo e(route('groups.edit', $group)); ?>"
               class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-md"
               style="background:#C9A635">
                Modifier
            </a>
            <?php endif; ?>
            <a href="<?php echo e(route('groups.index')); ?>"
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
            <h2 class="text-lg font-bold text-gray-900"><?php echo e($group->name); ?></h2>
        </div>
        <a href="<?php echo e(route('groups.members-pdf', $group)); ?>"
           class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-md"
           style="background:#1a2e4a">
            📄 Télécharger liste des membres (PDF)
        </a>
    </div>

    
    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3FA46A">
            <p class="text-xs text-gray-500 uppercase font-medium">Membres</p>
            <p class="text-2xl font-bold mt-1" style="color:#3FA46A"><?php echo e($group->believers->count()); ?></p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3A9BDC">
            <p class="text-xs text-gray-500 uppercase font-medium">Responsable</p>
            <p class="text-sm font-bold mt-2" style="color:#3A9BDC">
                <?php echo e($group->leader->full_name ?? $group->leader->name ?? '—'); ?>

            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        
        <div class="md:col-span-2 space-y-4">

            <div class="bg-white shadow-sm rounded-lg p-4">
                <h3 class="text-sm font-semibold text-gray-700 uppercase mb-3">Informations</h3>
                <p class="text-sm text-gray-600">
                    <span class="font-medium text-gray-800">Description :</span>
                    <?php echo e($group->description ?: '—'); ?>

                </p>
            </div>

            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase">Membres du groupe</h3>
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
                        <?php $__empty_1 = true; $__currentLoopData = $group->believers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $believer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-900"><?php echo e($believer->full_name ?? $believer->name); ?></td>
                            <td class="px-4 py-3 text-gray-600">
                                <?php echo e($believer->pivot->joined_at
                                    ? \Carbon\Carbon::parse($believer->pivot->joined_at)->format('d/m/Y')
                                    : '—'); ?>

                            </td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                <form action="<?php echo e(route('groups.believers.destroy', [$group, $believer])); ?>" method="POST"
                                      onsubmit="return confirm('Retirer ce membre du groupe ?');">
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
                            <td colspan="3" class="px-4 py-8 text-center text-gray-400">
                                Aucun membre pour le moment.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>

        
        <div class="md:col-span-1">
            <div class="bg-white shadow-sm rounded-lg p-4">
                <h3 class="text-sm font-semibold text-gray-700 uppercase mb-3">Affecter un fidèle</h3>
                <form action="<?php echo e(route('groups.believers.store', $group)); ?>" method="POST" class="space-y-3">
                    <?php echo csrf_field(); ?>
                    <div>
                        <label for="believer_id" class="block text-xs font-medium text-gray-500 uppercase mb-1">
                            Fidèle
                        </label>
                        <select name="believer_id" id="believer_id"
                                class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500 <?php $__errorArgs = ['believer_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                required>
                            <option value="">— Sélectionner —</option>
                            <?php $__currentLoopData = $availableBelievers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $believer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($believer->id); ?>">
                                    <?php echo e($believer->full_name ?? $believer->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['believer_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="joined_at" class="block text-xs font-medium text-gray-500 uppercase mb-1">
                            Date d'adhésion
                        </label>
                        <input type="date" name="joined_at" id="joined_at"
                               class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ekklesia-gestion\resources\views/groups/show.blade.php ENDPATH**/ ?>