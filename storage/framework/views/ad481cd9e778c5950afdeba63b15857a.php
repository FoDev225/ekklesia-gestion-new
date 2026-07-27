
<div id="modal-depart"
     class="fixed inset-0 z-50 hidden overflow-y-auto"
     aria-labelledby="modal-depart-title" role="dialog" aria-modal="true">
 
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeDepartModal()"></div>
 
        <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full z-10">
 
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200"
                 style="background:#1a2e4a">
                <h3 class="text-lg font-semibold text-white" id="modal-depart-title">
                    Enregistrer un départ
                </h3>
                <button type="button" onclick="closeDepartModal()"
                    class="text-gray-300 hover:text-white text-xl font-bold leading-none">
                    &times;
                </button>
            </div>
 
            <form id="form-depart" method="POST" action="">
                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
 
                <div class="px-6 py-5 space-y-4">
 
                    <p class="text-sm text-gray-600">
                        Enregistrer le départ de
                        <strong id="modal-depart-name" class="text-gray-900"></strong>.
                    </p>
 
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Type de départ <span class="text-red-500">*</span>
                        </label>
                        <select name="departure_type" id="departure_type" required
                            onchange="toggleDestination(this.value)"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">-- Choisir --</option>
                            <option value="depart">🚶 Quitter la communauté</option>
                            <option value="deces">🕊 Décès</option>
                        </select>
                    </div>
 
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="departure_date" required
                            value="<?php echo e(date('Y-m-d')); ?>"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
 
                    <div id="destination-field">
                        <label class="block text-sm font-medium text-gray-700">
                            Destination / Nouvelle église
                            <span class="text-gray-400 text-xs">(optionnel)</span>
                        </label>
                        <input type="text" name="departure_destination"
                            placeholder="Ex: AEBECI Bouaké, Retour au village..."
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
 
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Motif / Observation</label>
                        <textarea name="departure_reason" rows="2"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>
 
                </div>
 
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                    <button type="button" onclick="closeDepartModal()"
                        class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-md hover:bg-gray-200">
                        Annuler
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-white text-sm font-medium rounded-md"
                        style="background:#1a2e4a">
                        Confirmer
                    </button>
                </div>
 
            </form>
        </div>
    </div>
</div><?php /**PATH C:\laragon\www\ekklesia-gestion\resources\views/believers/partials/departure.blade.php ENDPATH**/ ?>