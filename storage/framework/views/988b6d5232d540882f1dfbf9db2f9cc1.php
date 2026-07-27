
<?php $__env->startSection('title', 'Gestion des utilisateurs'); ?>
<?php $__env->startSection('page-title', 'Administration'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-4">

    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="<?php echo e(route(auth()->user()->dashboardRoute())); ?>" class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
            <span class="text-gray-300">/</span>
            <span class="text-sm font-medium text-gray-700">Utilisateurs</span>
        </div>
        <a href="<?php echo e(route('users.create')); ?>"
           class="px-4 py-2 text-white text-sm rounded-md" style="background:#3A9BDC">
            + Nouveau compte
        </a>
    </div>

    
    <?php if(session('user_created')): ?>
    <?php $uc = session('user_created'); ?>
    <div class="bg-green-50 border-2 border-green-400 rounded-lg p-5">
        <h4 class="font-bold text-green-800 mb-3 flex items-center gap-2">
            ✅ Compte créé avec succès — Transmettez ces identifiants à l'utilisateur
        </h4>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
            <div class="bg-white rounded p-3 border">
                <p class="text-gray-500 text-xs">Nom</p>
                <p class="font-bold text-gray-800"><?php echo e($uc['name']); ?></p>
            </div>
            <div class="bg-white rounded p-3 border">
                <p class="text-gray-500 text-xs">Nom d'utilisateur</p>
                <p class="font-bold font-mono" style="color:#3A9BDC"><?php echo e($uc['username']); ?></p>
            </div>
            <div class="bg-white rounded p-3 border">
                <p class="text-gray-500 text-xs">Mot de passe temporaire</p>
                <p class="font-bold font-mono text-red-600"><?php echo e($uc['password']); ?></p>
            </div>
            <div class="bg-white rounded p-3 border">
                <p class="text-gray-500 text-xs">Rôle</p>
                <p class="font-bold text-gray-800"><?php echo e($uc['role']); ?></p>
            </div>
        </div>
        <p class="text-xs text-green-700 mt-3">
            ⚠ Ce mot de passe temporaire ne sera plus affiché. L'utilisateur devra le changer à sa première connexion.
        </p>
    </div>
    <?php endif; ?>

    
    <?php if(session('password_reset')): ?>
    <?php $pr = session('password_reset'); ?>
    <div class="bg-yellow-50 border-2 border-yellow-400 rounded-lg p-5">
        <h4 class="font-bold text-yellow-800 mb-3">🔑 Mot de passe réinitialisé</h4>
        <div class="grid grid-cols-3 gap-3 text-sm">
            <div class="bg-white rounded p-3 border">
                <p class="text-gray-500 text-xs">Utilisateur</p>
                <p class="font-bold"><?php echo e($pr['name']); ?></p>
            </div>
            <div class="bg-white rounded p-3 border">
                <p class="text-gray-500 text-xs">Username</p>
                <p class="font-bold font-mono" style="color:#3A9BDC"><?php echo e($pr['username']); ?></p>
            </div>
            <div class="bg-white rounded p-3 border">
                <p class="text-gray-500 text-xs">Nouveau mot de passe temporaire</p>
                <p class="font-bold font-mono text-red-600"><?php echo e($pr['password']); ?></p>
            </div>
        </div>
        <p class="text-xs text-yellow-700 mt-2">L'utilisateur devra changer ce mot de passe à sa prochaine connexion.</p>
    </div>
    <?php endif; ?>

    <?php if(session('success')): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    
    <div class="bg-white shadow-sm rounded-lg p-4">
        <form method="GET" action="<?php echo e(route('users.index')); ?>" class="flex gap-3">
            <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                placeholder="Nom ou username..."
                class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500 flex-1">
            <select name="role" class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Tous les rôles</option>
                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($role->name); ?>" <?php if(request('role') === $role->name): echo 'selected'; endif; ?>>
                        <?php echo e($role->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button type="submit" class="px-4 py-2 text-white text-sm rounded-md" style="background:#3A9BDC">
                Filtrer
            </button>
            <a href="<?php echo e(route('users.index')); ?>" class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded-md">
                Reset
            </a>
        </form>
    </div>

    
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Utilisateur</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Username</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Rôle</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Fidèle lié</th>
                    <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase">Statut</th>
                    <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase">MDP</th>
                    <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50 <?php echo e(!$user->is_active ? 'opacity-60' : ''); ?>">
                    <td class="px-4 py-3">
                        <p class="font-medium text-gray-900"><?php echo e($user->name); ?></p>
                        <p class="text-xs text-gray-400"><?php echo e($user->email); ?></p>
                    </td>
                    <td class="px-4 py-3">
                        <span class="font-mono text-sm" style="color:#3A9BDC"><?php echo e($user->username); ?></span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
                            <?php echo e($user->getRoleNames()->first() ?? '—'); ?>

                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-600 text-xs">
                        <?php if($user->believer): ?>
                            <a href="<?php echo e(route('believers.show', $user->believer)); ?>"
                               class="hover:underline" style="color:#3A9BDC">
                                <?php echo e($user->believer->full_name); ?>

                            </a>
                        <?php else: ?>
                            <span class="text-gray-400">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <?php if($user->is_active): ?>
                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full font-medium">● Actif</span>
                        <?php else: ?>
                            <span class="px-2 py-1 bg-red-100 text-red-600 text-xs rounded-full font-medium">● Inactif</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <?php if($user->must_change_password): ?>
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full" title="Mot de passe temporaire non changé">
                                ⚠ Temporaire
                            </span>
                        <?php else: ?>
                            <span class="text-green-600 text-xs">✓ Changé</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 text-center whitespace-nowrap space-x-1">
                        
                        <form method="POST" action="<?php echo e(route('admin.users.toggle', $user)); ?>" class="inline">
                            <?php echo csrf_field(); ?>
                            <button class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded
                                <?php echo e($user->is_active ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-700'); ?>">
                                <?php echo e($user->is_active ? 'Désactiver' : 'Activer'); ?>

                            </button>
                        </form>

                        
                        <a href="<?php echo e(route('users.edit', $user)); ?>"
                           class="inline-flex items-center px-2.5 py-1 bg-yellow-100 text-yellow-700 text-xs font-medium rounded">
                            Modifier
                        </a>

                        
                        <button type="button"
                            onclick="openResetPasswordModal(
                                '<?php echo e($user->id); ?>',
                                '<?php echo e($user->name); ?>'
                            )"
                            class="inline-flex items-center px-2.5 py-1 bg-gray-100 text-gray-600 text-xs rounded hover:bg-gray-200">
                            🔑 Reset MDP
                        </button>
                        
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-400">Aucun utilisateur.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?php if($users->hasPages()): ?>
        <div class="px-4 py-3 border-t"><?php echo e($users->links()); ?></div>
        <?php endif; ?>
    </div>
</div>
    
    <div id="resetPasswordModal"
        class="fixed inset-0 z-50 hidden overflow-y-auto">

        
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75"
            onclick="closeResetPasswordModal()"></div>

        <div class="flex min-h-screen items-center justify-center px-4">

            <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full">

                
                <div class="flex items-center justify-between px-6 py-4 border-b bg-yellow-500 text-white">

                    <h3 class="text-lg font-semibold">
                        Réinitialisation du mot de passe
                    </h3>

                    <button
                        type="button"
                        onclick="closeResetPasswordModal()"
                        class="text-white text-2xl leading-none hover:text-gray-200">
                        &times;
                    </button>

                </div>

                
                <form id="reset-password-form" method="POST">
                    <?php echo csrf_field(); ?>

                    <div class="px-6 py-5 space-y-4">

                        <p class="text-sm text-gray-700">
                            Vous êtes sur le point de réinitialiser le mot de passe de
                            <strong id="reset-user-name"></strong>.
                        </p>

                        <div class="rounded-lg bg-yellow-50 border border-yellow-200 p-4">

                            <p class="text-sm text-yellow-800">
                                ⚠️ Un nouveau mot de passe temporaire sera généré automatiquement.
                            </p>

                            <p class="text-sm text-yellow-800 mt-2">
                                L'utilisateur devra le modifier lors de sa prochaine connexion.
                            </p>

                        </div>

                    </div>

                    
                    <div class="px-6 py-4 border-t flex justify-end gap-3">

                        <button
                            type="button"
                            onclick="closeResetPasswordModal()"
                            class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">

                            Annuler

                        </button>

                        <button
                            type="submit"
                            class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">

                            🔑 Réinitialiser

                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>

<script>

    function openResetPasswordModal(userId, userName)
    {
        document.getElementById('reset-user-name').textContent = userName;

        document.getElementById('reset-password-form').action =
            '/admin/users/' + userId + '/reset-password';

        document.getElementById('resetPasswordModal').classList.remove('hidden');
    }

    function closeResetPasswordModal()
    {
        document.getElementById('resetPasswordModal').classList.add('hidden');
    }

</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ekklesia-gestion\resources\views/admin/users/index.blade.php ENDPATH**/ ?>