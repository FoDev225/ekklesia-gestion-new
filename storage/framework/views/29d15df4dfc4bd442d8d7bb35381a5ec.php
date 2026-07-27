
<div id="lift-modal-sanction"
     class="fixed inset-0 z-50 hidden overflow-y-auto"
     aria-labelledby="modal-title" role="dialog" aria-modal="true">

    
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeLiftSanctionModal()"></div>

        
        <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full z-10">

            
            <div class="flex items-center justify-between px-6 py-4 border-b bg-green-500 text-white border-gray-200">
                <h3 class="text-lg font-semibold" id="modal-title">
                    Lever la sanction disciplinaire
                </h3>
                <button type="button" onclick="closeLiftSanctionModal()"
                    class="text-gray-400 hover:text-gray-600 text-xl font-bold leading-none">
                    &times;
                </button>
            </div>

            
            <form id="form-lift-sanction" method="POST" action="<?php echo e(route('believers.lift-sanction', $believer->id)); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>

                <div class="px-6 py-5 space-y-4">

                    <p class="text-sm text-gray-600">
                        Vous êtes sur le point de lever la sanction disciplinaire de
                        <strong id="lift-modal-believer-name" class="text-gray-900"></strong>.
                    </p>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Observation <span class="text-red-500">*</span>
                        </label>
                        <textarea name="lift_note" rows="3" required
                            placeholder="Observations sur la levée de la sanction..."
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>

                </div>

                
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                    <button type="button" onclick="closeLiftSanctionModal()"
                        class="px-4 py-2 bg-gray-400 text-gray-700 text-sm rounded-md hover:bg-gray-500">
                        Annuler
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-500 text-white text-sm font-medium rounded-md hover:bg-green-600">
                        Lever la sanction
                    </button>
                </div>

            </form>
        </div>
    </div>
</div><?php /**PATH C:\laragon\www\ekklesia-gestion\resources\views/believers/partials/lift-sanction-modal.blade.php ENDPATH**/ ?>