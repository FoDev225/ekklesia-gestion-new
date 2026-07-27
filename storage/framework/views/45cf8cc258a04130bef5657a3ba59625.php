
<?php $__env->startSection('title', 'Inscription fidèle'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white shadow-sm rounded-lg p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-1">Rejoignez notre communauté</h2>
    <p class="text-sm text-gray-500 mb-6">
        Remplissez ce formulaire pour vous inscrire comme fidèle de l'église. Le champ marqué <span class="text-red-500">*</span> est obligatoire.
    </p>

    <?php if($errors->any()): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
        <ul class="list-disc list-inside">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('public.believer.store')); ?>" class="space-y-4">
        <?php echo csrf_field(); ?>

        
        <div style="position:absolute; left:-9999px;" aria-hidden="true">
            <input type="text" name="website" tabindex="-1" autocomplete="off">
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nom <span class="text-red-500">*</span></label>
                <input type="text" name="lastname" value="<?php echo e(old('lastname')); ?>" required
                       class="mt-1 block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Prénoms <span class="text-red-500">*</span></label>
                <input type="text" name="firstname" value="<?php echo e(old('firstname')); ?>" required
                       class="mt-1 block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-medium text-gray-700">Sexe <span class="text-red-500">*</span></label>
                <select name="gender" required class="mt-1 block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">-- Choisir --</option>
                    <option value="M" <?php if(old('gender') === 'M'): echo 'selected'; endif; ?>>Homme</option>
                    <option value="F" <?php if(old('gender') === 'F'): echo 'selected'; endif; ?>>Femme</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Date de naissance</label>
                <input type="date" name="birth_date" value="<?php echo e(old('birth_date')); ?>"
                       class="mt-1 block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Situation matrimoniale</label>
            <select name="marital_status" class="mt-1 block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">-- Choisir --</option>
                <option value="celibataire" <?php if(old('marital_status') === 'celibataire'): echo 'selected'; endif; ?>>Célibataire</option>
                <option value="marie" <?php if(old('marital_status') === 'marie'): echo 'selected'; endif; ?>>Marié(e)</option>
                <option value="veuf" <?php if(old('marital_status') === 'veuf'): echo 'selected'; endif; ?>>Veuf/Veuve</option>
                <option value="divorce" <?php if(old('marital_status') === 'divorce'): echo 'selected'; endif; ?>>Divorcé(e)</option>
            </select>
        </div>

        <div class="border-t pt-4">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Contact & adresse</h3>

            <div>
                <label class="block text-sm font-medium text-gray-700">Téléphone <span class="text-red-500">*</span></label>
                <input type="tel" name="address[phone]" value="<?php echo e(old('address.phone')); ?>" required
                       class="mt-1 block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="grid grid-cols-2 gap-3 mt-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Commune</label>
                    <input type="text" name="address[commune]" value="<?php echo e(old('address.commune')); ?>"
                           class="mt-1 block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Quartier</label>
                    <input type="text" name="address[quartier]" value="<?php echo e(old('address.quartier')); ?>"
                           class="mt-1 block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
        </div>

        <div class="border-t pt-4">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Informations spirituelles</h3>

            <div>
                <label class="block text-sm font-medium text-gray-700">Comment avez-vous connu l'église ?</label>
                <input type="text" name="church[connaissance_eglise]" value="<?php echo e(old('church.connaissance_eglise')); ?>"
                       class="mt-1 block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <label class="flex items-center gap-2 mt-3 text-sm">
                <input type="checkbox" name="church[baptised]" value="1" <?php if(old('church.baptised')): echo 'checked'; endif; ?>
                       class="rounded border-gray-300 text-indigo-600">
                Je suis baptisé(e)
            </label>
        </div>

        <button type="submit"
            class="w-full py-3 text-white text-sm font-semibold rounded-md" style="background:#3A9BDC">
            S'inscrire
        </button>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('public.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ekklesia-gestion\resources\views/public/believer-form.blade.php ENDPATH**/ ?>