

<?php $__env->startSection('title', 'Nouvelle présentation d\'enfant'); ?>
<?php $__env->startSection('page-title', 'Présentations d\'enfants'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto space-y-4">

    
    <div class="flex items-center gap-3">
        <a href="<?php echo e(route(auth()->user()->dashboardRoute())); ?>" class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
        <span class="text-gray-300">/</span>
        <a href="<?php echo e(route('dedication.index')); ?>" class="text-sm text-gray-500 hover:text-gray-700">Présentations</a>
        <span class="text-gray-300">/</span>
        <span class="text-sm text-gray-700 font-medium">Nouvelle</span>
    </div>

    <?php if($errors->any()): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        <strong>Erreurs :</strong>
        <ul class="mt-1 list-disc list-inside text-sm">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('dedication.store')); ?>">
        <?php echo csrf_field(); ?>

        
        <div class="bg-white shadow-sm rounded-lg p-6 space-y-4">
            <h3 class="font-semibold text-gray-700 border-b pb-3">Dates</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Date de la demande <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="demande_date"
                        value="<?php echo e(old('demande_date', date('Y-m-d'))); ?>"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <?php $__errorArgs = ['demande_date'];
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
                        Date de présentation <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="dedication_date" value="<?php echo e(old('dedication_date')); ?>"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <?php $__errorArgs = ['dedication_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        
        <div class="bg-white shadow-sm rounded-lg p-6 space-y-4">
            <h3 class="font-semibold text-gray-700 border-b pb-3">Parents</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Père <span class="text-red-500">*</span>
                    </label>
                    <select name="father_id" id="father_id" required
                        onchange="fillParent('father', this.value)"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 <?php $__errorArgs = ['father_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value="">-- Sélectionner le père --</option>
                        <?php $__currentLoopData = $believers->whereIn('gender', ['M']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($b->id); ?>" <?php if(old('father_id') == $b->id): echo 'selected'; endif; ?>>
                                <?php echo e($b->lastname); ?> <?php echo e($b->firstname); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['father_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <div class="mt-2">
                        <label class="block text-xs text-gray-500">Nom affiché sur la fiche (si différent)</label>
                        <input type="text" name="father_name" id="father_name"
                            value="<?php echo e(old('father_name')); ?>"
                            placeholder="Laisser vide pour utiliser le nom du fidèle"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-xs focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Mère <span class="text-red-500">*</span>
                    </label>
                    <select name="mother_id" id="mother_id" required
                        onchange="fillParent('mother', this.value)"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 <?php $__errorArgs = ['mother_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value="">-- Sélectionner la mère --</option>
                        <?php $__currentLoopData = $believers->whereIn('gender', ['F']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($b->id); ?>" <?php if(old('mother_id') == $b->id): echo 'selected'; endif; ?>>
                                <?php echo e($b->lastname); ?> <?php echo e($b->firstname); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['mother_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <div class="mt-2">
                        <label class="block text-xs text-gray-500">Nom affiché sur la fiche (si différent)</label>
                        <input type="text" name="mother_name" id="mother_name"
                            value="<?php echo e(old('mother_name')); ?>"
                            placeholder="Laisser vide pour utiliser le nom de la fidèle"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-xs focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

            </div>
        </div>

        
        <div class="bg-white shadow-sm rounded-lg p-6 space-y-4">
            <h3 class="font-semibold text-gray-700 border-b pb-3">Informations de l'enfant</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Nom <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="child_lastname" value="<?php echo e(old('child_lastname')); ?>"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 <?php $__errorArgs = ['child_lastname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['child_lastname'];
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
                        Prénom(s) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="child_firstname" value="<?php echo e(old('child_firstname')); ?>"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 <?php $__errorArgs = ['child_firstname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['child_firstname'];
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
                        Sexe <span class="text-red-500">*</span>
                    </label>
                    <select name="gender" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Choisir --</option>
                        <option value="Masculin" <?php if(old('gender') === 'Masculin'): echo 'selected'; endif; ?>>Masculin</option>
                        <option value="Féminin"  <?php if(old('gender') === 'Féminin'): echo 'selected'; endif; ?>>Féminin</option>
                    </select>
                    <?php $__errorArgs = ['gender'];
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
                        Date de naissance <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="child_birthdate" value="<?php echo e(old('child_birthdate')); ?>"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <?php $__errorArgs = ['child_birthdate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Lieu de naissance <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="child_birthplace" value="<?php echo e(old('child_birthplace')); ?>"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <?php $__errorArgs = ['child_birthplace'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

            </div>
        </div>

        <div class="flex justify-between mt-3">
            <a href="<?php echo e(route('dedication.index')); ?>"
               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm hover:bg-gray-300">Annuler</a>
            <button type="submit"
                class="px-6 py-2 text-white rounded-md text-sm font-medium" style="background:#3FA46A">
                ✓ Enregistrer
            </button>
        </div>
    </form>
</div>

<script>
const believers = <?php echo json_encode($believers->keyBy('id'), 15, 512) ?>;

function fillParent(role, believerId) {
    if (!believerId) return;
    const b = believers[believerId];
    if (!b) return;
    // Pré-remplir le nom affiché avec le nom du fidèle
    const nameField = document.getElementById(role + '_name');
    if (nameField && !nameField.value) {
        nameField.value = b.lastname + ' ' + b.firstname;
    }
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ekklesia-gestion\resources\views/dedication/create.blade.php ENDPATH**/ ?>