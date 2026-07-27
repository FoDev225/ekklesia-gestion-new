{{-- ===================== MODAL SANCTION ===================== --}}
<div id="modal-sanction"
     class="fixed inset-0 z-50 hidden overflow-y-auto"
     aria-labelledby="modal-title" role="dialog" aria-modal="true">

    {{-- Overlay --}}
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeSanctionModal()"></div>

        {{-- Contenu --}}
        <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full z-10">

            {{-- En-tête --}}
            <div class="flex items-center justify-between px-6 py-4 border-b bg-red-500 text-white border-gray-200">
                <h3 class="text-lg font-semibold" id="modal-title">
                    Sanction disciplinaire
                </h3>
                <button type="button" onclick="closeSanctionModal()"
                    class="text-gray-400 hover:text-gray-600 text-xl font-bold leading-none">
                    &times;
                </button>
            </div>

            {{-- Corps --}}
            <form id="form-sanction" method="POST" action="{{ route('believers.sanction', $believer->id) }}">
                @csrf
                @method('PATCH')

                <div class="px-6 py-5 space-y-4">

                    <p class="text-sm text-gray-600">
                        Vous êtes sur le point d'appliquer une sanction disciplinaire à
                        <strong id="modal-believer-name" class="text-gray-900"></strong>.
                    </p>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Date de début <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="start_date" required
                            value="{{ date('Y-m-d') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Date de fin <span class="text-gray-400 text-xs">(laisser vide si indéterminée)</span>
                        </label>
                        <input type="date" name="end_date"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Motif <span class="text-red-500">*</span>
                        </label>
                        <textarea name="reason" rows="3" required
                            placeholder="Décrivez le motif de la sanction..."
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Décidé par
                        </label>
                        <input type="text" name="decided_by" readonly
                            value="Comité"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                </div>

                {{-- Pied --}}
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                    <button type="button" onclick="closeSanctionModal()"
                        class="px-4 py-2 bg-gray-400 text-gray-700 text-sm rounded-md hover:bg-gray-500">
                        Annuler
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-500 text-white text-sm font-medium rounded-md hover:bg-green-600">
                        Appliquer la sanction
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

