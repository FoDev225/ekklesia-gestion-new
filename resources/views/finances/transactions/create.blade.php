@extends('layouts.dashboard')

@section('title', 'Nouvelle transaction')
@section('page-title', 'Nouvelle transaction')

@section('content')
<div class="max-w-2xl mx-auto space-y-4">

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
        <form action="{{ route('finances.transactions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type <span class="text-red-500">*</span></label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2">
                        <input type="radio" name="type" value="recette" checked onchange="toggleType()" id="type_recette">
                        <span class="text-sm text-green-700 font-medium">Recette</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="type" value="depense" onchange="toggleType()" id="type_depense">
                        <span class="text-sm text-red-700 font-medium">Dépense</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Catégorie <span class="text-red-500">*</span></label>
                <select name="category_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">— Sélectionner —</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" data-type="{{ $cat->type }}" @selected(old('category_id') == $cat->id)>
                            {{ $cat->groupe_label ?? $cat->groupe }} @if($cat->sous_groupe) / {{ \App\Models\FinanceCategory::GROUPES_DEPENSE[$cat->sous_groupe] ?? $cat->sous_groupe }} @endif — {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
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

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Montant (FCFA) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="montant" required value="{{ old('montant') }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date <span class="text-red-500">*</span></label>
                    <input type="date" name="date" required value="{{ old('date', now()->format('Y-m-d')) }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('description') }}</textarea>
            </div>

            <div id="piece_field">
                <label class="block text-sm font-medium text-gray-700">Pièce justificative <span id="piece_required" class="text-red-500 hidden">*</span></label>
                <input type="file" name="piece_justificative" accept=".pdf,.jpg,.jpeg,.png" class="mt-1 block w-full text-sm">
                <p class="text-xs text-gray-400 mt-1">Obligatoire pour une dépense. Max 10 Mo.</p>
            </div>

            <div class="border-t pt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Lier à un projet (optionnel)</label>
                <div class="grid grid-cols-2 gap-3">
                    <select name="fleche_type" id="fleche_type" onchange="toggleFlecheId()"
                        class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">— Aucun —</option>
                        <option value="construction_project">Projet construction</option>
                        <option value="dossier_foncier">Dossier foncier</option>
                    </select>
                    <select name="fleche_id" id="fleche_id_construction" class="hidden border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">— Sélectionner —</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->libelle }}</option>
                        @endforeach
                    </select>
                    <select name="fleche_id" id="fleche_id_foncier" class="hidden border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">— Sélectionner —</option>
                        @foreach($dossiers as $dossier)
                            <option value="{{ $dossier->id }}">{{ $dossier->libelle }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex gap-2 pt-2">
                <button type="submit" class="px-6 py-2 text-white text-sm font-medium rounded-md" style="background:#3A9BDC">
                    Enregistrer
                </button>
                <a href="{{ route('finances.transactions.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300">
                    Annuler
                </a>
            </div>
        </form>
    </div>

</div>

<script>
    function toggleType() {
        const isDepense = document.getElementById('type_depense').checked;
        document.getElementById('piece_required').classList.toggle('hidden', !isDepense);

        document.querySelectorAll('select[name="category_id"] option[data-type]').forEach(opt => {
            const type = isDepense ? 'depense' : 'recette';
            opt.hidden = opt.dataset.type !== type;
        });
        document.querySelector('select[name="category_id"]').value = '';
    }

    function toggleFlecheId() {
        const type = document.getElementById('fleche_type').value;
        document.getElementById('fleche_id_construction').classList.toggle('hidden', type !== 'construction_project');
        document.getElementById('fleche_id_foncier').classList.toggle('hidden', type !== 'dossier_foncier');
    }

    document.addEventListener('DOMContentLoaded', toggleType);
</script>
@endsection