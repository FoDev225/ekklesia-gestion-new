

<?php $__env->startSection('title', 'Nouveau mariage'); ?>
<?php $__env->startSection('page-title', 'Registre des mariages'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto space-y-4">

    
    <div class="flex items-center gap-3">
        <a href="<?php echo e(route(auth()->user()->dashboardRoute())); ?>" class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
        <span class="text-gray-300">/</span>
        <a href="<?php echo e(route('mariage.index')); ?>" class="text-sm text-gray-500 hover:text-gray-700">Mariages</a>
        <span class="text-gray-300">/</span>
        <span class="text-sm text-gray-700 font-medium">Nouveau</span>
    </div>

    <?php if($errors->any()): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        <strong>Erreurs :</strong>
        <ul class="mt-1 list-disc list-inside text-sm">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('mariage.store')); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>

        
        <div class="bg-white shadow-sm rounded-lg p-6 space-y-4">
            <h3 class="font-semibold text-gray-700 border-b pb-3 flex items-center gap-2">
                <span class="px-2 py-0.5 rounded text-xs font-bold text-white" style="background:#3A9BDC">ÉPOUX</span>
                Informations de l'époux
            </h3>

            
            <div class="flex gap-4 mb-2">
                <label class="flex items-center gap-2 cursor-pointer text-sm">
                    <input type="radio" name="groom_type" value="believer" id="groom_believer"
                        <?php echo e(old('groom_type', 'believer') === 'believer' ? 'checked' : ''); ?>

                        onchange="togglePerson('groom', 'believer')"
                        class="text-indigo-600">
                    Fidèle de l'église
                </label>
                <label class="flex items-center gap-2 cursor-pointer text-sm">
                    <input type="radio" name="groom_type" value="external" id="groom_external"
                        <?php echo e(old('groom_type') === 'external' ? 'checked' : ''); ?>

                        onchange="togglePerson('groom', 'external')"
                        class="text-indigo-600">
                    Externe à l'église
                </label>
            </div>

            
            <div id="groom_believer_fields" class="<?php echo e(old('groom_type') === 'external' ? 'hidden' : ''); ?>">
                <label class="block text-sm font-medium text-gray-700">Sélectionner le fidèle</label>
                <select name="groom_id" id="groom_id_select"
                    onchange="fillFromBeliever('groom', this.value)"
                    class="mt-1 block w-full md:w-1/2 border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">-- Choisir un fidèle --</option>
                    <?php $__currentLoopData = $believers->where('gender', 'M'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($b->id); ?>" <?php if(old('groom_id') == $b->id): echo 'selected'; endif; ?>>
                            <?php echo e($b->lastname); ?> <?php echo e($b->firstname); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div id="groom_name_field" class="<?php echo e(old('groom_type') !== 'external' ? 'hidden' : ''); ?>">
                    <label class="block text-sm font-medium text-gray-700">Nom & Prénom <span class="text-red-500">*</span></label>
                    <input type="text" name="groom_name" value="<?php echo e(old('groom_name')); ?>"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date de naissance</label>
                    <input type="date" name="groom_birthdate" id="groom_birthdate" value="<?php echo e(old('groom_birthdate')); ?>"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Lieu de naissance</label>
                    <input type="text" name="groom_birth_place" id="groom_birth_place" value="<?php echo e(old('groom_birth_place')); ?>"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date de baptême</label>
                    <input type="date" name="groom_bapistism_date" id="groom_bapistism_date" value="<?php echo e(old('groom_bapistism_date')); ?>"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Lieu de baptême</label>
                    <input type="text" name="groom_bapistism_place" id="groom_bapistism_place" value="<?php echo e(old('groom_bapistism_place')); ?>"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pasteur du baptême</label>
                    <input type="text" name="baptism_officer_groom" id="baptism_officer_groom" value="<?php echo e(old('baptism_officer_groom')); ?>"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Profession</label>
                    <input type="text" name="groom_profession" id="groom_profession" value="<?php echo e(old('groom_profession')); ?>"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Photo</label>
                    <input type="file" name="groom_photo" accept="image/*"
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700">
                </div>
            </div>
        </div>

        
        <div class="bg-white shadow-sm rounded-lg p-6 space-y-4">
            <h3 class="font-semibold text-gray-700 border-b pb-3 flex items-center gap-2">
                <span class="px-2 py-0.5 rounded text-xs font-bold text-white" style="background:#C9A635">ÉPOUSE</span>
                Informations de l'épouse
            </h3>

            <div class="flex gap-4 mb-2">
                <label class="flex items-center gap-2 cursor-pointer text-sm">
                    <input type="radio" name="bride_type" value="believer"
                        <?php echo e(old('bride_type', 'believer') === 'believer' ? 'checked' : ''); ?>

                        onchange="togglePerson('bride', 'believer')" class="text-indigo-600">
                    Fidèle de l'église
                </label>
                <label class="flex items-center gap-2 cursor-pointer text-sm">
                    <input type="radio" name="bride_type" value="external"
                        <?php echo e(old('bride_type') === 'external' ? 'checked' : ''); ?>

                        onchange="togglePerson('bride', 'external')" class="text-indigo-600">
                    Externe à l'église
                </label>
            </div>

            <div id="bride_believer_fields" class="<?php echo e(old('bride_type') === 'external' ? 'hidden' : ''); ?>">
                <label class="block text-sm font-medium text-gray-700">Sélectionner la fidèle</label>
                <select name="bride_id" id="bride_id_select"
                    onchange="fillFromBeliever('bride', this.value)"
                    class="mt-1 block w-full md:w-1/2 border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">-- Choisir une fidèle --</option>
                    <?php $__currentLoopData = $believers->where('gender', 'F'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($b->id); ?>" <?php if(old('bride_id') == $b->id): echo 'selected'; endif; ?>>
                            <?php echo e($b->lastname); ?> <?php echo e($b->firstname); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div id="bride_name_field" class="<?php echo e(old('bride_type') !== 'external' ? 'hidden' : ''); ?>">
                    <label class="block text-sm font-medium text-gray-700">Nom & Prénom <span class="text-red-500">*</span></label>
                    <input type="text" name="bride_name" value="<?php echo e(old('bride_name')); ?>"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date de naissance</label>
                    <input type="date" name="bride_birthdate" id="bride_birthdate" value="<?php echo e(old('bride_birthdate')); ?>"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Lieu de naissance</label>
                    <input type="text" name="bride_birth_place" id="bride_birth_place" value="<?php echo e(old('bride_birth_place')); ?>"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date de baptême</label>
                    <input type="date" name="bride_bapistism_date" id="bride_bapistism_date" value="<?php echo e(old('bride_bapistism_date')); ?>"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Lieu de baptême</label>
                    <input type="text" name="bride_bapistism_place" id="bride_bapistism_place" value="<?php echo e(old('bride_bapistism_place')); ?>"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pasteur du baptême</label>
                    <input type="text" name="baptism_officer_bride" id="baptism_officer_bride" value="<?php echo e(old('baptism_officer_bride')); ?>"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Profession</label>
                    <input type="text" name="bride_profession" id="bride_profession" value="<?php echo e(old('bride_profession')); ?>"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Photo</label>
                    <input type="file" name="bride_photo" accept="image/*"
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-yellow-50 file:text-yellow-700">
                </div>
            </div>
        </div>

        
        <div class="bg-white shadow-sm rounded-lg p-6 space-y-4">
            <h3 class="font-semibold text-gray-700 border-b pb-3">Cérémonie civile</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date <span class="text-red-500">*</span></label>
                    <input type="date" name="civil_marriage_date" value="<?php echo e(old('civil_marriage_date')); ?>"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <?php $__errorArgs = ['civil_marriage_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Lieu <span class="text-red-500">*</span></label>
                    <input type="text" name="civil_marriage_place" value="<?php echo e(old('civil_marriage_place')); ?>"
                        placeholder="Ex: Mairie de Yopougon..."
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <?php $__errorArgs = ['civil_marriage_place'];
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
            <h3 class="font-semibold text-gray-700 border-b pb-3">Cérémonie religieuse</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date <span class="text-red-500">*</span></label>
                    <input type="date" name="religious_marriage_date" value="<?php echo e(old('religious_marriage_date')); ?>"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <?php $__errorArgs = ['religious_marriage_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Lieu <span class="text-red-500">*</span></label>
                    <input type="text" name="religious_marriage_place" value="<?php echo e(old('religious_marriage_place')); ?>"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <?php $__errorArgs = ['religious_marriage_place'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Maître de cérémonie</label>
                    <input type="text" name="wedding_mc" value="<?php echo e(old('wedding_mc')); ?>"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Prédicateur <span class="text-red-500">*</span></label>
                    <input type="text" name="wedding_preacher" value="<?php echo e(old('wedding_preacher')); ?>"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <?php $__errorArgs = ['wedding_preacher'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">La Bible remise par</label>
                    <input type="text" name="hand_bible" value="<?php echo e(old('hand_bible')); ?>"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pasteur officiant <span class="text-red-500">*</span></label>
                    <input type="text" name="officiant" value="<?php echo e(old('officiant')); ?>"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <?php $__errorArgs = ['officiant'];
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
            <h3 class="font-semibold text-gray-700 border-b pb-3">Témoins</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-3">
                    <p class="text-xs font-semibold text-blue-700 uppercase">Témoin de l'époux</p>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nom <span class="text-red-500">*</span></label>
                        <input type="text" name="groom_witness" value="<?php echo e(old('groom_witness')); ?>"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <?php $__errorArgs = ['groom_witness'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Profession</label>
                        <input type="text" name="groom_witness_profession" value="<?php echo e(old('groom_witness_profession')); ?>"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
                <div class="space-y-3">
                    <p class="text-xs font-semibold text-yellow-700 uppercase">Témoin de l'épouse</p>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nom <span class="text-red-500">*</span></label>
                        <input type="text" name="bride_witness" value="<?php echo e(old('bride_witness')); ?>"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <?php $__errorArgs = ['bride_witness'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Profession</label>
                        <input type="text" name="bride_witness_profession" value="<?php echo e(old('bride_witness_profession')); ?>"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
            </div>
        </div>

        
        <div class="flex justify-between mt-3">
            <a href="<?php echo e(route('mariage.index')); ?>"
               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm hover:bg-gray-300">Annuler</a>
            <button type="submit"
                class="px-6 py-2 text-white rounded-md text-sm font-medium" style="background:#3FA46A">
                ✓ Enregistrer le mariage
            </button>
        </div>
    </form>

</div>

<script>
// Données des fidèles pour pré-remplissage
const believers = <?php echo json_encode($believers->keyBy('id'), 15, 512) ?>;

function togglePerson(person, type) {
    const believerFields = document.getElementById(person + '_believer_fields');
    const nameField = document.getElementById(person + '_name_field');
    if (type === 'believer') {
        believerFields.classList.remove('hidden');
        nameField.classList.add('hidden');
        document.querySelector(`[name="${person}_id"]`).name = person + '_id';
    } else {
        believerFields.classList.add('hidden');
        nameField.classList.remove('hidden');
        // Vider la sélection fidèle
        const select = document.getElementById(person + '_id_select');
        if (select) select.value = '';
    }
}

function fillFromBeliever(person, believerId) {
    if (!believerId) return;
    const b = believers[believerId];
    if (!b) return;

    // Pré-remplir les champs disponibles depuis le fidèle
    const birthDate = document.getElementById(person + '_birthdate');
    const birthPlace = document.getElementById(person + '_birth_place');
    const profession = document.getElementById(person + '_profession');

    if (birthDate && b.birth_date)  birthDate.value  = b.birth_date;
    if (birthPlace && b.birth_place) birthPlace.value = b.birth_place;
    if (profession && b.profession)  profession.value = b.profession?.profession ?? '';
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ekklesia-gestion\resources\views/mariage/create.blade.php ENDPATH**/ ?>