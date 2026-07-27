

<?php $__env->startSection('title', 'Fiche fidèle'); ?>
<?php $__env->startSection('page-title', 'Gestion des fidèles'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto space-y-4">

    
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="<?php echo e(route(auth()->user()->dashboardRoute())); ?>"
               class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
            <span class="text-gray-300">/</span>
            <a href="<?php echo e(route('believers.index')); ?>"
               class="text-sm text-gray-500 hover:text-gray-700">Fidèles</a>
            <span class="text-gray-300">/</span>
            <span class="text-sm text-gray-700 font-medium"><?php echo e($believer->full_name); ?></span>
        </div>
        <div class="flex gap-2">
            
            <a href="<?php echo e(route('believers.fiche', $believer)); ?>"
               target="_blank"
               class="px-3 py-1.5 text-white text-sm rounded-md flex items-center gap-1"
               style="background:#1a2e4a">
                📄 Fiche fidèle PDF
            </a>
            <a href="<?php echo e(route('believers.card', $believer)); ?>"
                class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-md"
                style="background:#C9A635">
                    🪪 Carte de membre
            </a>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('believers.edit')): ?>
            <a href="<?php echo e(route('believers.edit', $believer)); ?>"
               class="px-4 py-2 text-white text-sm rounded-md" style="background:#C9A635">
                Modifier
            </a>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('believers.delete')): ?>
            <form method="POST" action="<?php echo e(route('believers.destroy', $believer)); ?>"
                  onsubmit="return confirm('Archiver ce fidèle ?')">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button type="submit"
                    class="px-4 py-2 bg-red-500 text-white text-sm rounded-md hover:bg-red-600">
                    Archiver
                </button>
            </form>
            <?php endif; ?>
        </div>
    </div>

    
    <?php if(session('success')): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>

    
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="flex items-start justify-between">
            <div class="flex items-start gap-4">
                
                <?php if($believer->profile_picture): ?>
                <img src="<?php echo e($believer->profile_picture_url); ?>" alt="<?php echo e($believer->full_name); ?>"
                    class="w-20 h-20 rounded-full object-cover border-2 border-gray-100 flex-shrink-0">
                <?php else: ?>
                <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 text-2xl font-bold flex-shrink-0">
                    <?php echo e(strtoupper(substr($believer->firstname, 0, 1) . substr($believer->lastname, 0, 1))); ?>

                </div>
                <?php endif; ?>

                <div>
                    <h3 class="text-2xl font-bold text-gray-900"><?php echo e($believer->full_name); ?></h3>
                    <div class="flex items-center gap-3 mt-2">
                        <span class="px-2 py-1 rounded-full text-xs font-medium <?php echo e($believer->age_group_color); ?>">
                            <?php echo e($believer->age_group); ?>

                        </span>
                        <span class="text-sm text-gray-500"><?php echo e($believer->gender_label); ?></span>
                        <span class="text-sm text-gray-500"><?php echo e($believer->marital_status); ?></span>
                        <?php if($believer->age): ?>
                            <span class="text-sm text-gray-500"><?php echo e($believer->age); ?> ans</span>
                        <?php endif; ?>
                        <?php if($believer->sanctions()->where('is_active', true)->exists()): ?>
                            <span class="text-sm bg-red-500 text-white px-2 py-1 rounded-full">Sous discipline</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="text-right text-xs text-gray-400">
                <div>Fidèle #<?php echo e($believer->id); ?></div>
                <div class="font-mono font-semibold text-gray-600"><?php echo e($believer->register_number); ?></div>
                <div>Enregistré le <?php echo e($believer->created_at->format('d/m/Y')); ?></div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h4 class="font-semibold text-gray-700 border-b pb-2 mb-4">Informations générales</h4>
            <?php echo $__env->make('believers._info-row', ['label' => 'Nom', 'value' => $believer->lastname], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->make('believers._info-row', ['label' => 'Prénom', 'value' => $believer->firstname], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->make('believers._info-row', ['label' => 'Numéro CNI', 'value' => $believer->cni_number], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->make('believers._info-row', ['label' => 'Date de naissance', 'value' => $believer->birth_date?->format('d/m/Y')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->make('believers._info-row', ['label' => 'Lieu de naissance', 'value' => $believer->birth_place], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->make('believers._info-row', ['label' => 'Nationalité', 'value' => $believer->nationality], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->make('believers._info-row', ['label' => 'Nombre d\'enfants', 'value' => $believer->number_of_children], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->make('believers._info-row', ['label' => 'Famille', 'value' => $believer->family?->name], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h4 class="font-semibold text-gray-700 border-b pb-2 mb-4">Adresse & Contact</h4>
            <?php if($believer->address): ?>
                <?php echo $__env->make('believers._info-row', ['label' => 'Commune', 'value' => $believer->address->commune], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('believers._info-row', ['label' => 'Quartier', 'value' => $believer->address->quartier], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('believers._info-row', ['label' => 'Sous-quartier', 'value' => $believer->address->sous_quartier], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('believers._info-row', ['label' => 'Téléphone', 'value' => $believer->address->phone], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('believers._info-row', ['label' => 'WhatsApp', 'value' => $believer->address->whatsapp], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('believers._info-row', ['label' => 'Email', 'value' => $believer->address->email], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php else: ?>
                <p class="text-gray-400 text-sm">Aucune adresse enregistrée.</p>
            <?php endif; ?>
        </div>

        
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h4 class="font-semibold text-gray-700 border-b pb-2 mb-4">Vie spirituelle</h4>
            <?php if($believer->churchInformation): ?>
                <?php $ci = $believer->churchInformation; ?>
                <?php echo $__env->make('believers._info-row', ['label' => 'Connaissance de l\'église', 'value' => $ci->connaissance_eglise], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('believers._info-row', ['label' => 'Église d\'origine', 'value' => $ci->original_church], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('believers._info-row', ['label' => 'Année d\'arrivée', 'value' => $ci->arrival_year], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('believers._info-row', ['label' => 'Date de conversion', 'value' => $ci->conversion_date?->format('d/m/Y')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('believers._info-row', ['label' => 'Lieu de conversion', 'value' => $ci->conversion_place], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <div class="flex justify-between py-1 border-b border-gray-50">
                    <span class="text-sm text-gray-500">Baptisé(e)</span>
                    <span class="text-sm font-medium">
                        <?php if($ci->baptised): ?>
                            <span class="text-green-600">✓ Oui</span>
                        <?php else: ?>
                            <span class="text-gray-400">Non</span>
                        <?php endif; ?>
                    </span>
                </div>
                <?php if($ci->baptised): ?>
                    <?php echo $__env->make('believers._info-row', ['label' => 'Date de baptême', 'value' => $ci->baptism_date?->format('d/m/Y')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php echo $__env->make('believers._info-row', ['label' => 'Lieu de baptême', 'value' => $ci->baptism_place], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php echo $__env->make('believers._info-row', ['label' => 'Pasteur officiant', 'value' => $ci->baptism_pastor], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php echo $__env->make('believers._info-row', ['label' => 'N° carte', 'value' => $ci->baptism_card_number], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?>
            <?php else: ?>
                <p class="text-gray-400 text-sm">Aucune information spirituelle enregistrée.</p>
            <?php endif; ?>
        </div>

        
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h4 class="font-semibold text-gray-700 border-b pb-2 mb-4">Éducation</h4>
            <?php if($believer->education): ?>
                <?php echo $__env->make('believers._info-row', ['label' => 'Niveau d\'étude', 'value' => $believer->education->niveau_etude], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('believers._info-row', ['label' => 'Diplôme', 'value' => $believer->education->diploma], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('believers._info-row', ['label' => 'Qualification', 'value' => $believer->education->qualification], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php else: ?>
                <p class="text-gray-400 text-sm">Aucune information renseignée.</p>
            <?php endif; ?>

            <h4 class="font-semibold text-gray-700 border-b pb-2 mb-4 mt-6">Profession</h4>
            <?php if($believer->profession): ?>
                <?php echo $__env->make('believers._info-row', ['label' => 'Profession', 'value' => $believer->profession->profession], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('believers._info-row', ['label' => 'Fonction', 'value' => $believer->profession->function], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('believers._info-row', ['label' => 'Entreprise', 'value' => $believer->profession->company], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('believers._info-row', ['label' => 'Contact pro.', 'value' => $believer->profession->professional_contact], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php else: ?>
                <p class="text-gray-400 text-sm">Aucune information renseignée.</p>
            <?php endif; ?>
        </div>

    </div>

    
    <?php if($believer->responsibility): ?>
    <div class="bg-white shadow-sm rounded-lg p-6">
        <h4 class="font-semibold text-gray-700 border-b pb-2 mb-4">Responsabilités</h4>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div>
                <p class="text-gray-500 font-medium mb-1">Anciennes</p>
                <p class="text-gray-700"><?php echo e($believer->responsibility->old ?: '—'); ?></p>
            </div>
            <div>
                <p class="text-gray-500 font-medium mb-1">Actuelles</p>
                <p class="text-gray-700"><?php echo e($believer->responsibility->current ?: '—'); ?></p>
            </div>
            <div>
                <p class="text-gray-500 font-medium mb-1">Souhaits de service</p>
                <p class="text-gray-700"><?php echo e($believer->responsibility->desire ?: '—'); ?></p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    
    <div class="bg-white shadow-sm rounded-lg p-6">
        <h4 class="font-semibold text-gray-700 border-b pb-2 mb-4">Appartenance</h4>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <p class="text-xs text-gray-500 uppercase font-medium mb-2">Équipes</p>
                <?php $__empty_1 = true; $__currentLoopData = $believer->teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <span class="inline-block px-2 py-1 bg-indigo-100 text-indigo-700 text-xs rounded mb-1">
                        <?php echo e($team->name); ?>

                    </span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-gray-400 text-sm">Aucune équipe</p>
                <?php endif; ?>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-medium mb-2">Groupes de louange</p>
                <?php $__empty_1 = true; $__currentLoopData = $believer->worshipGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <span class="inline-block px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded mb-1">
                        <?php echo e($group->name); ?>

                    </span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-gray-400 text-sm">Aucun groupe</p>
                <?php endif; ?>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-medium mb-2">Cellule de quartier</p>
                <?php $__empty_1 = true; $__currentLoopData = $believer->cells; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cell): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <span class="inline-block px-2 py-1 bg-green-100 text-green-700 text-xs rounded mb-1">
                        <?php echo e($cell->name); ?>

                    </span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-gray-400 text-sm">Aucune cellule</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ekklesia-gestion\resources\views/believers/show.blade.php ENDPATH**/ ?>