

<?php $__env->startSection('title', 'Importer des fidèles'); ?>
<?php $__env->startSection('page-title', 'Gestion des fidèles'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto space-y-4">

    
    <div class="flex items-center gap-3">
        <a href="<?php echo e(route(auth()->user()->dashboardRoute())); ?>"
           class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
        <span class="text-gray-300">/</span>
        <a href="<?php echo e(route('believers.index')); ?>"
           class="text-sm text-gray-500 hover:text-gray-700">Fidèles</a>
        <span class="text-gray-300">/</span>
        <span class="text-sm text-gray-700 font-medium">Import Excel</span>
    </div>

    <?php if($errors->any()): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        <ul class="list-disc list-inside text-sm">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php endif; ?>

    
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="flex items-start gap-4">
            <div class="text-3xl">📥</div>
            <div class="flex-1">
                <h3 class="font-semibold text-gray-700 mb-1">Étape 1 — Téléchargez le template</h3>
                <p class="text-sm text-gray-500 mb-3">
                    Utilisez ce fichier Excel comme modèle. Il contient les colonnes exactes attendues
                    et une ligne d'exemple. Ne modifiez pas les noms de colonnes.
                </p>
                <a href="<?php echo e(route('believers.template')); ?>"
                   class="inline-flex items-center gap-2 px-4 py-2 text-white text-sm rounded-md"
                   style="background:#3FA46A">
                    ⬇ Télécharger le template Excel
                </a>
            </div>
        </div>
    </div>

    
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="flex items-start gap-4">
            <div class="text-3xl">📤</div>
            <div class="flex-1">
                <h3 class="font-semibold text-gray-700 mb-1">Étape 2 — Importez votre fichier</h3>
                <p class="text-sm text-gray-500 mb-4">
                    Sélectionnez votre fichier Excel complété. Les doublons (même nom + prénom)
                    seront automatiquement ignorés.
                </p>

                <form method="POST" action="<?php echo e(route('believers.import')); ?>"
                      enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>

                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-indigo-400 transition-colors"
                         id="drop-zone">
                        <input type="file" name="file" id="file-input" accept=".xlsx,.xls"
                               class="hidden" onchange="updateFileName(this)">
                        <label for="file-input" class="cursor-pointer">
                            <p class="text-gray-400 text-sm mb-2">📎 Cliquez pour sélectionner un fichier</p>
                            <p class="text-gray-400 text-xs">Formats acceptés : .xlsx, .xls — Max 10 Mo</p>
                        </label>
                        <p id="file-name" class="mt-3 text-sm font-medium text-indigo-600 hidden"></p>
                    </div>

                    <div class="flex justify-end mt-4">
                        <button type="submit" id="btn-import"
                            class="px-6 py-2 text-white rounded-md text-sm font-medium disabled:opacity-50"
                            style="background:#3A9BDC">
                            ↑ Lancer l'import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <h4 class="font-semibold text-yellow-800 mb-2">⚠ Notes importantes</h4>
        <ul class="text-sm text-yellow-700 space-y-1 list-disc list-inside">
            <li>Les champs <strong>NOM</strong> et <strong>PRÉNOMS</strong> sont obligatoires.</li>
            <li>Les valeurs <strong>NEANT</strong> sont automatiquement ignorées.</li>
            <li>Un fidèle avec le même nom et prénom existant sera ignoré.</li>
            <li>Les dates acceptées : <code>JJ/MM/AAAA</code> ou numéros Excel.</li>
            <li>Situation matrimoniale : <code>célibataire | marié | veuf | divorcé</code></li>
        </ul>
    </div>

</div>

<script>
    function updateFileName(input) {
        const label = document.getElementById('file-name');
        if (input.files && input.files[0]) {
            label.textContent = '✓ ' + input.files[0].name;
            label.classList.remove('hidden');
        }
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ekklesia-gestion\resources\views/believers/imports/form.blade.php ENDPATH**/ ?>