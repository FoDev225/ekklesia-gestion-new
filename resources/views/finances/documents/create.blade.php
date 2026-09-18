@extends('layouts.dashboard')

@section('title', 'Nouveau bordereau / bon de sortie')
@section('page-title', 'Nouveau document financier')

@section('content')
<div class="max-w-3xl mx-auto space-y-4">

    <div class="flex items-center gap-3">
        <a href="{{ route('finances.transactions.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Transactions</a>
    </div>

    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        <ul class="list-disc list-inside text-sm">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white shadow-sm rounded-lg p-6">
        <form action="{{ route('finances.documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type de document <span class="text-red-500">*</span></label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2">
                        <input type="radio" name="type" value="recette" checked onchange="toggleType()" id="type_recette">
                        <span class="text-sm text-green-700 font-medium">Bordereau de versement (recette)</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="type" value="depense" onchange="toggleType()" id="type_depense">
                        <span class="text-sm text-red-700 font-medium">Bon de sortie (dépense)</span>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">N° du document</label>
                    <input type="text" name="numero_document" placeholder="Ex: 001103" value="{{ old('numero_document') }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date <span class="text-red-500">*</span></label>
                    <input type="date" name="date" required value="{{ old('date', now()->format('Y-m-d')) }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Compte <span class="text-red-500">*</span></label>
                <select name="account_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">— Sélectionner —</option>
                    @foreach($accounts as $acc)
                        <option value="{{ $acc->id }}" @selected(old('account_id') == $acc->id)>{{ $acc->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Champs spécifiques dépense --}}
            <div id="depense_fields" class="hidden space-y-3 border-t pt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Bénéficiaire <span class="text-red-500">*</span></label>
                    <input type="text" name="beneficiaire" value="{{ old('beneficiaire') }}" placeholder="Ex: Comité"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Mode de paiement <span class="text-red-500">*</span></label>
                        <select name="mode_paiement" id="mode_paiement" onchange="toggleCheque()"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="espece">Espèce</option>
                            <option value="cheque">Chèque</option>
                        </select>
                    </div>
                    <div id="cheque_field" class="hidden">
                        <label class="block text-sm font-medium text-gray-700">N° Chèque</label>
                        <input type="text" name="numero_cheque" value="{{ old('numero_cheque') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pièce justificative <span class="text-red-500">*</span></label>
                    <input type="file" name="piece_justificative" accept=".pdf,.jpg,.jpeg,.png" class="mt-1 block w-full text-sm">
                </div>
            </div>

            {{-- Lignes du document --}}
            <div class="border-t pt-4">
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-medium text-gray-700">Lignes <span class="text-red-500">*</span></label>
                    <button type="button" onclick="addLine()" class="text-xs px-2 py-1 bg-indigo-100 text-indigo-700 rounded">+ Ajouter une ligne</button>
                </div>

                <div id="lines_container" class="space-y-2"></div>

                <div class="text-right text-sm font-semibold text-gray-700 mt-2">
                    Total : <span id="total_display">0</span> FCFA
                </div>
            </div>

            {{-- Fléchage projet --}}
            <div class="border-t pt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Lier à un projet (optionnel)</label>
                <div class="grid grid-cols-2 gap-3">
                    <select name="fleche_type" id="fleche_type" onchange="toggleFleche()"
                        class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">— Aucun —</option>
                        <option value="construction_project">Projet construction</option>
                        <option value="dossier_foncier">Dossier foncier</option>
                    </select>
                    <select name="fleche_id" id="fleche_id_construction" class="hidden border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">— Sélectionner —</option>
                        @foreach($projects as $project)<option value="{{ $project->id }}">{{ $project->libelle }}</option>@endforeach
                    </select>
                    <select name="fleche_id" id="fleche_id_foncier" class="hidden border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">— Sélectionner —</option>
                        @foreach($dossiers as $dossier)<option value="{{ $dossier->id }}">{{ $dossier->libelle }}</option>@endforeach
                    </select>
                </div>
            </div>

            {{-- Signataires --}}
            <div class="border-t pt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Signataires</label>
                <div class="grid grid-cols-3 gap-3" id="signataires_recette">
                    <input type="text" name="emetteur" placeholder="Émetteur" value="{{ old('emetteur') }}" class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <input type="text" name="verificateur" placeholder="Vérificateur" value="{{ old('verificateur') }}" class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <input type="text" name="recepteur" placeholder="Récepteur" value="{{ old('recepteur') }}" class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <div class="flex gap-2 pt-2">
                <button type="submit" class="px-6 py-2 text-white text-sm font-medium rounded-md" style="background:#3A9BDC">
                    Enregistrer le document
                </button>
                <a href="{{ route('finances.transactions.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<template id="line_template">
    <div class="flex gap-2 items-center line-row">
        <select name="lines[__INDEX__][category_id]" required
            class="flex-1 min-w-0 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500 line-category">
            <option value="">— Catégorie —</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" data-type="{{ $cat->type }}">
                    {{ \App\Models\FinanceCategory::GROUPES_DEPENSE[$cat->groupe] ?? \App\Models\FinanceCategory::GROUPES_RECETTE[$cat->groupe] ?? $cat->groupe }} — {{ $cat->name }}
                </option>
            @endforeach
        </select>
        <input type="text" name="lines[__INDEX__][motif]" placeholder="Motif (optionnel)"
            class="flex-1 min-w-0 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
        <input type="number" step="0.01" name="lines[__INDEX__][montant]" placeholder="Montant" required
            class="w-28 flex-shrink-0 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500 line-montant" oninput="updateTotal()">
        <button type="button" onclick="removeLine(this)" class="text-red-500 hover:text-red-700 px-2 flex-shrink-0">✕</button>
    </div>
</template>

<script>
    let lineIndex = 0;

    function addLine() {
        const template = document.getElementById('line_template').innerHTML.replaceAll('__INDEX__', lineIndex);
        const div = document.createElement('div');
        div.innerHTML = template;
        document.getElementById('lines_container').appendChild(div.firstElementChild);
        filterLineCategory(div.firstElementChild.querySelector('.line-category'));
        lineIndex++;
    }

    function removeLine(btn) {
        btn.closest('.line-row').remove();
        updateTotal();
    }

    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.line-montant').forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        document.getElementById('total_display').textContent = total.toLocaleString('fr-FR');
    }

    function filterLineCategory(select) {
        const isDepense = document.getElementById('type_depense').checked;
        const type = isDepense ? 'depense' : 'recette';
        select.querySelectorAll('option[data-type]').forEach(opt => {
            opt.hidden = opt.dataset.type !== type;
        });
    }

    function toggleType() {
        const isDepense = document.getElementById('type_depense').checked;
        document.getElementById('depense_fields').classList.toggle('hidden', !isDepense);
        document.querySelectorAll('.line-category').forEach(filterLineCategory);
    }

    function toggleCheque() {
        document.getElementById('cheque_field').classList.toggle('hidden', document.getElementById('mode_paiement').value !== 'cheque');
    }

    function toggleFleche() {
        const type = document.getElementById('fleche_type').value;
        document.getElementById('fleche_id_construction').classList.toggle('hidden', type !== 'construction_project');
        document.getElementById('fleche_id_foncier').classList.toggle('hidden', type !== 'dossier_foncier');
    }

    document.addEventListener('DOMContentLoaded', function () {
        toggleType();
        addLine(); // une première ligne par défaut
    });
</script>
@endsection