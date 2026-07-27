

<?php $__env->startSection('title', 'Nouveau fidèle'); ?>
<?php $__env->startSection('page-title', 'Gestion des fidèles'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto space-y-4">

    
    <div class="flex items-center gap-3">
        <a href="<?php echo e(route(auth()->user()->dashboardRoute())); ?>"
           class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
        <span class="text-gray-300">/</span>
        <a href="<?php echo e(route('believers.index')); ?>"
           class="text-sm text-gray-500 hover:text-gray-700">Fidèles</a>
        <span class="text-gray-300">/</span>
        <span class="text-sm text-gray-700 font-medium">Nouveau</span>
    </div>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <?php if($errors->any()): ?>
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <strong>Veuillez corriger les erreurs suivantes :</strong>
                <ul class="mt-1 list-disc list-inside text-sm">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('believers.store')); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>

                
                <div class="border-b border-gray-200 mb-6">
                    <nav class="-mb-px flex space-x-6 overflow-x-auto" id="tabs-nav">
                        <?php $__currentLoopData = [
                            ['id' => 'general',        'label' => '① Infos générales'],
                            ['id' => 'adresse',        'label' => '② Adresse & Contact'],
                            ['id' => 'eglise',         'label' => '③ Vie spirituelle'],
                            ['id' => 'education',      'label' => '④ Éducation'],
                            ['id' => 'profession',     'label' => '⑤ Profession'],
                            ['id' => 'responsabilite', 'label' => '⑥ Responsabilités'],
                            ['id' => 'appartenance',   'label' => '⑦ Équipes & Groupes'],
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <button type="button"
                            onclick="switchTab('<?php echo e($tab['id']); ?>')"
                            id="tab-<?php echo e($tab['id']); ?>"
                            class="tab-btn whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            <?php echo e($tab['label']); ?>

                        </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </nav>
                </div>

                
                <div id="panel-general" class="tab-panel bg-white shadow-sm rounded-lg p-6 space-y-4">
                    <h3 class="font-medium text-gray-700 mb-4">Informations générales</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Matricule</label>
                            <input type="text" value="Généré automatiquement à l'enregistrement" disabled
                                class="mt-1 block w-full border-gray-200 rounded-md bg-gray-50 text-gray-400 text-sm italic">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nom <span class="text-red-500">*</span></label>
                            <input type="text" name="lastname" value="<?php echo e(old('lastname')); ?>"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 <?php $__errorArgs = ['lastname'];
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
                            <label class="block text-sm font-medium text-gray-700">Prénom <span class="text-red-500">*</span></label>
                            <input type="text" name="firstname" value="<?php echo e(old('firstname')); ?>"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 <?php $__errorArgs = ['firstname'];
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
                            <label class="block text-sm font-medium text-gray-700">Genre <span class="text-red-500">*</span></label>
                            <select name="gender"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">-- Choisir --</option>
                                <option value="M" <?php if(old('gender') === 'M'): echo 'selected'; endif; ?>>Homme</option>
                                <option value="F" <?php if(old('gender') === 'F'): echo 'selected'; endif; ?>>Femme</option>
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
                            <label class="block text-sm font-medium text-gray-700">Situation maritale</label>
                            <select name="marital_status"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">-- Choisir --</option>
                                <option value="celibataire" <?php if(old('marital_status') === 'celibataire'): echo 'selected'; endif; ?>>Célibataire</option>
                                <option value="marie"       <?php if(old('marital_status') === 'marie'): echo 'selected'; endif; ?>>Marié(e)</option>
                                <option value="veuf"        <?php if(old('marital_status') === 'veuf'): echo 'selected'; endif; ?>>Veuf/Veuve</option>
                                <option value="divorce"     <?php if(old('marital_status') === 'divorce'): echo 'selected'; endif; ?>>Divorcé(e)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date de naissance</label>
                            <input type="date" name="birth_date" value="<?php echo e(old('birth_date')); ?>"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Lieu de naissance</label>
                            <input type="text" name="birth_place" value="<?php echo e(old('birth_place')); ?>"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nationalité</label>
                            <input type="text" name="nationality" value="<?php echo e(old('nationality', 'Ivoirienne')); ?>"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Numéro CNI</label>
                            <input type="text" name="cni_number" value="<?php echo e(old('cni_number')); ?>"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 <?php $__errorArgs = ['cni_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['cni_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre d'enfants</label>
                            <input type="number" name="number_of_children" value="<?php echo e(old('number_of_children', 0)); ?>" min="0"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Photo de profil</label>
                            <input type="file" name="profile_picture" accept="image/*"
                                class="mt-1 block w-full text-sm">
                            <?php $__errorArgs = ['profile_picture'];
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

                    </div>
                </div>

                
                <div id="panel-adresse" class="tab-panel hidden bg-white shadow-sm rounded-lg p-6 space-y-4">
                    <h3 class="font-medium text-gray-700 mb-4">Adresse & Contact</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Commune</label>
                            <input type="text" name="address[commune]" value="<?php echo e(old('address.commune')); ?>"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Quartier</label>
                            <input type="text" name="address[quartier]" value="<?php echo e(old('address.quartier')); ?>"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Sous-quartier</label>
                            <input type="text" name="address[sous_quartier]" value="<?php echo e(old('address.sous_quartier')); ?>"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Téléphone</label>
                            <input type="text" name="address[phone]" value="<?php echo e(old('address.phone')); ?>"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">WhatsApp</label>
                            <input type="text" name="address[whatsapp]" value="<?php echo e(old('address.whatsapp')); ?>"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="address[email]" value="<?php echo e(old('address.email')); ?>"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                    </div>
                </div>

                
                <div id="panel-eglise" class="tab-panel hidden bg-white shadow-sm rounded-lg p-6 space-y-4">
                    <h3 class="font-medium text-gray-700 mb-4">Vie spirituelle & Église</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Connaissance à l'église</label>
                            <input type="text" name="church[connaissance_eglise]" value="<?php echo e(old('church.connaissance_eglise')); ?>"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Église d'origine</label>
                            <input type="text" name="church[original_church]" value="<?php echo e(old('church.original_church')); ?>"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Année d'arrivée</label>
                            <input type="number" name="church[arrival_year]" value="<?php echo e(old('church.arrival_year')); ?>"
                                min="1900" max="<?php echo e(date('Y')); ?>"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date de conversion</label>
                            <input type="date" name="church[conversion_date]" value="<?php echo e(old('church.conversion_date')); ?>"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Lieu de conversion</label>
                            <input type="text" name="church[conversion_place]" value="<?php echo e(old('church.conversion_place')); ?>"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div class="md:col-span-2">
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700 cursor-pointer">
                                <input type="checkbox" name="church[baptised]" value="1"
                                    <?php if(old('church.baptised')): echo 'checked'; endif; ?>
                                    class="rounded border-gray-300 text-indigo-600"
                                    onchange="toggleBaptism(this)">
                                Baptisé(e)
                            </label>
                        </div>

                        <div id="baptism-fields" class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4 <?php echo e(old('church.baptised') ? '' : 'hidden'); ?>">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Date de baptême</label>
                                <input type="date" name="church[baptism_date]" value="<?php echo e(old('church.baptism_date')); ?>"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Lieu de baptême</label>
                                <input type="text" name="church[baptism_place]" value="<?php echo e(old('church.baptism_place')); ?>"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Pasteur officiant</label>
                                <input type="text" name="church[baptism_pastor]" value="<?php echo e(old('church.baptism_pastor')); ?>"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">N° carte de baptême</label>
                                <input type="text" name="church[baptism_card_number]" value="<?php echo e(old('church.baptism_card_number')); ?>"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>

                    </div>
                </div>

                
                <div id="panel-education" class="tab-panel hidden bg-white shadow-sm rounded-lg p-6 space-y-4">
                    <h3 class="font-medium text-gray-700 mb-4">Éducation</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Niveau d'étude</label>
                            <select name="education[niveau_etude]"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">-- Choisir --</option>
                                <?php $__currentLoopData = ['Primaire','Secondaire','Baccalauréat','Licence','Master','Doctorat','Aucun']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($n); ?>" <?php if(old('education.niveau_etude') === $n): echo 'selected'; endif; ?>><?php echo e($n); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Diplôme obtenu</label>
                            <input type="text" name="education[diploma]" value="<?php echo e(old('education.diploma')); ?>"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Qualification / Spécialité</label>
                            <input type="text" name="education[qualification]" value="<?php echo e(old('education.qualification')); ?>"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                </div>

                
                <div id="panel-profession" class="tab-panel hidden bg-white shadow-sm rounded-lg p-6 space-y-4">
                    <h3 class="font-medium text-gray-700 mb-4">Profession</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Profession</label>
                            <input type="text" name="profession[profession]" value="<?php echo e(old('profession.profession')); ?>"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fonction</label>
                            <input type="text" name="profession[function]" value="<?php echo e(old('profession.function')); ?>"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Entreprise / Structure</label>
                            <input type="text" name="profession[company]" value="<?php echo e(old('profession.company')); ?>"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Contact professionnel</label>
                            <input type="text" name="profession[professional_contact]" value="<?php echo e(old('profession.professional_contact')); ?>"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                </div>

                
                <div id="panel-responsabilite" class="tab-panel hidden bg-white shadow-sm rounded-lg p-6 space-y-4">
                    <h3 class="font-medium text-gray-700 mb-4">Responsabilités dans l'église</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Anciennes responsabilités</label>
                            <textarea name="responsibility[old]" rows="2"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"><?php echo e(old('responsibility.old')); ?></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Responsabilités actuelles</label>
                            <textarea name="responsibility[current]" rows="2"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"><?php echo e(old('responsibility.current')); ?></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Souhaits de service</label>
                            <textarea name="responsibility[desire]" rows="2"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"><?php echo e(old('responsibility.desire')); ?></textarea>
                        </div>
                    </div>
                </div>

                
                <div id="panel-appartenance" class="tab-panel hidden bg-white shadow-sm rounded-lg p-6 space-y-6">
                    <h3 class="font-medium text-gray-700 mb-4">Appartenance aux équipes & groupes</h3>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Équipes</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                            <?php $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="flex items-center gap-2 text-sm cursor-pointer">
                                <input type="checkbox" name="teams[]" value="<?php echo e($team->id); ?>"
                                    <?php if(in_array($team->id, old('teams', []))): echo 'checked'; endif; ?>
                                    class="rounded border-gray-300 text-indigo-600">
                                <?php echo e($team->name); ?>

                            </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Groupes de louange</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                            <?php $__currentLoopData = $worshipGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="flex items-center gap-2 text-sm cursor-pointer">
                                <input type="checkbox" name="worship_groups[]" value="<?php echo e($group->id); ?>"
                                    <?php if(in_array($group->id, old('worship_groups', []))): echo 'checked'; endif; ?>
                                    class="rounded border-gray-300 text-indigo-600">
                                <?php echo e($group->name); ?>

                            </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Cellule de quartier</label>
                        <select name="cell_id"
                            class="mt-1 block w-full md:w-1/2 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">-- Aucune --</option>
                            <?php $__currentLoopData = $cells; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cell): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($cell->id); ?>" <?php if(old('cell_id') == $cell->id): echo 'selected'; endif; ?>>
                                    <?php echo e($cell->name); ?> (<?php echo e($cell->quartier); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                
                <div class="mt-6 flex items-center justify-between">
                    <a href="<?php echo e(route('believers.index')); ?>"
                       class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 text-sm">
                        Annuler
                    </a>
                    <div class="flex gap-3">
                        <button type="button" onclick="prevTab()"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 text-sm">
                            ← Précédent
                        </button>
                        <button type="button" onclick="nextTab()"
                            id="btn-next"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm">
                            Suivant →
                        </button>
                        <button type="submit" id="btn-submit"
                            class="hidden px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm">
                            ✓ Enregistrer
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <script>
        const tabs = ['general','adresse','eglise','education','profession','responsabilite','appartenance'];
        let current = 0;

        function switchTab(id) {
            current = tabs.indexOf(id);
            renderTab();
        }

        function renderTab() {
            tabs.forEach((t, i) => {
                document.getElementById('panel-' + t).classList.toggle('hidden', i !== current);
                const btn = document.getElementById('tab-' + t);
                btn.classList.toggle('border-indigo-500', i === current);
                btn.classList.toggle('text-indigo-600', i === current);
                btn.classList.toggle('border-transparent', i !== current);
                btn.classList.toggle('text-gray-500', i !== current);
            });
            const isLast = current === tabs.length - 1;
            document.getElementById('btn-next').classList.toggle('hidden', isLast);
            document.getElementById('btn-submit').classList.toggle('hidden', !isLast);
        }

        function nextTab() { if (current < tabs.length - 1) { current++; renderTab(); } }
        function prevTab() { if (current > 0) { current--; renderTab(); } }
        function toggleBaptism(el) {
            document.getElementById('baptism-fields').classList.toggle('hidden', !el.checked);
        }

        renderTab();
    </script>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ekklesia-gestion\resources\views/believers/create.blade.php ENDPATH**/ ?>