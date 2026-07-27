
<?php $__env->startSection('title', 'Modifier compte'); ?>
<?php $__env->startSection('page-title', 'Administration'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto space-y-4">

    <div class="flex items-center gap-3">
        <a href="<?php echo e(route(auth()->user()->dashboardRoute())); ?>" class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
        <span class="text-gray-300">/</span>
        <a href="<?php echo e(route('users.index')); ?>" class="text-sm text-gray-500 hover:text-gray-700">Utilisateurs</a>
        <span class="text-gray-300">/</span>
        <span class="text-sm font-medium text-gray-700"><?php echo e($user->name); ?></span>
    </div>

    <?php if($errors->any()): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        <ul class="list-disc list-inside text-sm">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php endif; ?>

    
    <div class="bg-white shadow-sm rounded-lg p-5">
        <h4 class="font-semibold text-gray-700 border-b pb-3 mb-4">Informations du compte</h4>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500 text-xs">Nom</p>
                <p class="font-medium text-gray-800"><?php echo e($user->name); ?></p>
            </div>
            <div>
                <p class="text-gray-500 text-xs">Username</p>
                <p class="font-mono font-medium" style="color:#3A9BDC"><?php echo e($user->username); ?></p>
            </div>
            <div>
                <p class="text-gray-500 text-xs">Statut</p>
                <p class="font-medium <?php echo e($user->is_active ? 'text-green-600' : 'text-red-500'); ?>">
                    <?php echo e($user->is_active ? '● Actif' : '● Inactif'); ?>

                </p>
            </div>
            <div>
                <p class="text-gray-500 text-xs">Mot de passe</p>
                <p class="font-medium <?php echo e($user->must_change_password ? 'text-yellow-600' : 'text-green-600'); ?>">
                    <?php echo e($user->must_change_password ? '⚠ Temporaire' : '✓ Changé'); ?>

                </p>
            </div>
            <?php if($user->believer): ?>
            <div class="col-span-2">
                <p class="text-gray-500 text-xs">Fidèle lié</p>
                <a href="<?php echo e(route('believers.show', $user->believer)); ?>"
                   class="font-medium hover:underline" style="color:#3A9BDC">
                    <?php echo e($user->believer->full_name); ?>

                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <form method="POST" action="<?php echo e(route('users.update', $user)); ?>">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

        <div class="bg-white shadow-sm rounded-lg p-6 space-y-4">
            <h3 class="font-semibold text-gray-700 border-b pb-3">Modifier le rôle</h3>

            <div>
                <label class="block text-sm font-medium text-gray-700">Rôle <span class="text-red-500">*</span></label>
                <select name="role" required
                    onchange="toggleTeam(this.value)"
                    class="mt-1 block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($role->name); ?>"
                        <?php if(old('role', $user->getRoleNames()->first()) === $role->name): echo 'selected'; endif; ?>>
                        <?php echo e($role->name); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div id="team-field">
                <label class="block text-sm font-medium text-gray-700">Équipe à gérer</label>
                <select name="team_id"
                    class="mt-1 block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">-- Aucune --</option>
                    <?php $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($team->id); ?>"
                        <?php if(old('team_id', $user->believer?->teams->first()?->id) == $team->id): echo 'selected'; endif; ?>>
                        <?php echo e($team->name); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        <div class="flex justify-between mt-4">
            <a href="<?php echo e(route('users.index')); ?>"
               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm hover:bg-gray-300">Annuler</a>
            <button type="submit"
                class="px-6 py-2 text-white rounded-md text-sm font-medium" style="background:#3FA46A">
                ✓ Enregistrer
            </button>
        </div>
    </form>
</div>

<script>
function toggleTeam(role) {
    const teamField = document.getElementById('team-field');
    const rolesWithTeam = ['resp_j_aebeci', 'resp_afebeci', 'resp_ecodim', 'resp_culte'];
    teamField.style.opacity = rolesWithTeam.includes(role) ? '1' : '0.5';
}
toggleTeam('<?php echo e($user->getRoleNames()->first()); ?>');
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ekklesia-gestion\resources\views/admin/users/edit.blade.php ENDPATH**/ ?>