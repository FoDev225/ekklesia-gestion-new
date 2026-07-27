
<?php $__env->startSection('title', 'Nouvelle personne'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white shadow-sm rounded-lg p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-1">Accueil d'une nouvelle personne</h2>
    <p class="text-sm text-gray-500 mb-6">
        Formulaire réservé au service d'ordre et d'accueil.
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

    <form method="POST" action="<?php echo e(route('public.newcomer.store')); ?>" class="space-y-4" id="newcomer-form">
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
                <label class="block text-sm font-medium text-gray-700">Sexe</label>
                <select name="gender" class="mt-1 block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
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

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-medium text-gray-700">Téléphone <span class="text-red-500">*</span></label>
                <input type="tel" name="phone" value="<?php echo e(old('phone')); ?>" required
                       class="mt-1 block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">WhatsApp</label>
                <input type="tel" name="whatsapp" value="<?php echo e(old('whatsapp')); ?>"
                       class="mt-1 block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Catégorie <span class="text-red-500">*</span></label>
            <select name="category" required
                    class="mt-1 block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">-- Choisir --</option>
                <option value="Passage" <?php if(old('category') === 'Passage'): echo 'selected'; endif; ?>>De passage</option>
                <option value="Court_sejour" <?php if(old('category') === 'Court_sejour'): echo 'selected'; endif; ?>>Court séjour</option>
                <option value="Demeurant" <?php if(old('category') === 'Demeurant'): echo 'selected'; endif; ?>>Demeurant (résident du quartier)</option>
                <option value="Nouveau_converti" <?php if(old('category') === 'Nouveau_converti'): echo 'selected'; endif; ?>>Nouveau converti</option>
            </select>
        </div>

        <div id="recommendation-block">
            <label class="flex items-center gap-2 text-sm mb-2">
                <input type="checkbox" name="is_recommended" value="1" id="is_recommended_checkbox"
                       <?php if(old('is_recommended')): echo 'checked'; endif; ?>
                       class="rounded border-gray-300 text-indigo-600">
                Recommandé(e) par un fidèle
            </label>

            <div id="recommended_by_field" class="<?php echo e(old('is_recommended') ? '' : 'hidden'); ?>">
                <label class="block text-sm font-medium text-gray-700">Nom du fidèle qui recommande</label>
                <input type="text" name="recommended_by" value="<?php echo e(old('recommended_by')); ?>"
                       class="mt-1 block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Notes</label>
            <textarea name="notes" rows="3"
                      class="mt-1 block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"><?php echo e(old('notes')); ?></textarea>
        </div>

        <button type="submit"
            class="w-full py-3 text-white text-sm font-semibold rounded-md" style="background:#3FA46A">
            Enregistrer
        </button>
    </form>
</div>

<script>
    document.getElementById('is_recommended_checkbox').addEventListener('change', function() {
        document.getElementById('recommended_by_field').classList.toggle('hidden', !this.checked);
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('public.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ekklesia-gestion\resources\views/public/newcomer-form.blade.php ENDPATH**/ ?>