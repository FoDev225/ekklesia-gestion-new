

<?php $__env->startSection('title', 'Nouvelle personne'); ?>
<?php $__env->startSection('page-title', 'Nouvelles personnes'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto space-y-4">

    
    <div class="flex items-center gap-3">
        <a href="<?php echo e(route(auth()->user()->dashboardRoute())); ?>"
           class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
        <span class="text-gray-300">/</span>
        <a href="<?php echo e(route('newcomers.index')); ?>"
           class="text-sm text-gray-500 hover:text-gray-700">Nouvelles personnes</a>
        <span class="text-gray-300">/</span>
        <span class="text-sm text-gray-700 font-medium">Enregistrer</span>
    </div>

    <?php if($errors->any()): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        <strong>Erreurs :</strong>
        <ul class="mt-1 list-disc list-inside text-sm">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('newcomers.store')); ?>">
        <?php echo csrf_field(); ?>

        <div class="bg-white shadow-sm rounded-lg p-6 space-y-5">
            <h3 class="font-semibold text-gray-700 border-b pb-3">Informations personnelles</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Nom <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="lastname" value="<?php echo e(old('lastname')); ?>"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 <?php $__errorArgs = ['lastname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['lastname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Prénom <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="firstname" value="<?php echo e(old('firstname')); ?>"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 <?php $__errorArgs = ['firstname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['firstname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Genre</label>
                    <select name="gender"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Choisir --</option>
                        <option value="M" <?php if(old('gender') === 'M'): echo 'selected'; endif; ?>>Homme</option>
                        <option value="F" <?php if(old('gender') === 'F'): echo 'selected'; endif; ?>>Femme</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Date de naissance</label>
                    <input type="date" name="birth_date" value="<?php echo e(old('birth_date')); ?>"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Téléphone</label>
                    <input type="text" name="phone" value="<?php echo e(old('phone')); ?>"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">WhatsApp</label>
                    <input type="text" name="whatsapp" value="<?php echo e(old('whatsapp')); ?>"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

            </div>

            <h3 class="font-semibold text-gray-700 border-b pb-3 mt-2">Informations de visite</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Catégorie <span class="text-red-500">*</span>
                    </label>
                    <select name="category" id="category"
                        onchange="toggleRecommendation(this.value)"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Choisir --</option>
                        <option value="passage"          <?php if(old('category') === 'passage'): echo 'selected'; endif; ?>>De passage</option>
                        <option value="court_sejour"     <?php if(old('category') === 'court_sejour'): echo 'selected'; endif; ?>>Court séjour</option>
                        <option value="demeurant"        <?php if(old('category') === 'demeurant'): echo 'selected'; endif; ?>>Demeurant</option>
                        <option value="nouveau_converti" <?php if(old('category') === 'nouveau_converti'): echo 'selected'; endif; ?>>Nouveau converti</option>
                    </select>
                    <?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Date de 1ère visite <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="first_visit_date"
                        value="<?php echo e(old('first_visit_date', date('Y-m-d'))); ?>"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <?php $__errorArgs = ['first_visit_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

            </div>

            
            <div id="recommendation-fields" class="<?php echo e(old('category') === 'nouveau_converti' ? 'hidden' : ''); ?>">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700 cursor-pointer mt-2">
                            <input type="checkbox" name="is_recommended" value="1"
                                <?php if(old('is_recommended')): echo 'checked'; endif; ?>
                                onchange="toggleRecommendedBy(this)"
                                class="rounded border-gray-300 text-indigo-600">
                            Recommandé(e) par un membre
                        </label>
                    </div>
                    <div id="recommended-by-field" class="<?php echo e(old('is_recommended') ? '' : 'hidden'); ?>">
                        <label class="block text-sm font-medium text-gray-700">Recommandé par</label>
                        <input type="text" name="recommended_by" value="<?php echo e(old('recommended_by')); ?>"
                            placeholder="Nom du membre..."
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
            </div>

            
            <div>
                <label class="block text-sm font-medium text-gray-700">Notes / Observations</label>
                <textarea name="notes" rows="3"
                    placeholder="Informations complémentaires..."
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500"><?php echo e(old('notes')); ?></textarea>
            </div>

        </div>

        
        <div class="flex justify-between mt-4">
            <a href="<?php echo e(route('newcomers.index')); ?>"
               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm hover:bg-gray-300">
                Annuler
            </a>
            <button type="submit"
                class="px-6 py-2 text-white rounded-md text-sm font-medium"
                style="background:#3FA46A">
                ✓ Enregistrer
            </button>
        </div>
    </form>

</div>

<script>
    function toggleRecommendation(category) {
        const fields = document.getElementById('recommendation-fields');
        fields.classList.toggle('hidden', category === 'nouveau_converti');
    }

    function toggleRecommendedBy(checkbox) {
        document.getElementById('recommended-by-field').classList.toggle('hidden', !checkbox.checked);
    }

    // Init au chargement (old values)
    toggleRecommendation(document.getElementById('category').value);
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ekklesia-gestion\resources\views/newcomers/create.blade.php ENDPATH**/ ?>