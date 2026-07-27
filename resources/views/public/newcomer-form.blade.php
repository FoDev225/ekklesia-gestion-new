@extends('public.layout')
@section('title', 'Nouvelle personne')

@section('content')
<div class="bg-white shadow-sm rounded-lg p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-1">Accueil d'une nouvelle personne</h2>
    <p class="text-sm text-gray-500 mb-6">
        Formulaire réservé au service d'ordre et d'accueil.
    </p>

    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('public.newcomer.store') }}" class="space-y-4" id="newcomer-form">
        @csrf

        <div style="position:absolute; left:-9999px;" aria-hidden="true">
            <input type="text" name="website" tabindex="-1" autocomplete="off">
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nom <span class="text-red-500">*</span></label>
                <input type="text" name="lastname" value="{{ old('lastname') }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Prénoms <span class="text-red-500">*</span></label>
                <input type="text" name="firstname" value="{{ old('firstname') }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-medium text-gray-700">Sexe</label>
                <select name="gender" class="mt-1 block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">-- Choisir --</option>
                    <option value="M" @selected(old('gender') === 'M')>Homme</option>
                    <option value="F" @selected(old('gender') === 'F')>Femme</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Date de naissance</label>
                <input type="date" name="birth_date" value="{{ old('birth_date') }}"
                       class="mt-1 block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-medium text-gray-700">Téléphone <span class="text-red-500">*</span></label>
                <input type="tel" name="phone" value="{{ old('phone') }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">WhatsApp</label>
                <input type="tel" name="whatsapp" value="{{ old('whatsapp') }}"
                       class="mt-1 block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Catégorie <span class="text-red-500">*</span></label>
            <select name="category" required
                    class="mt-1 block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">-- Choisir --</option>
                <option value="Passage" @selected(old('category') === 'Passage')>De passage</option>
                <option value="Court_sejour" @selected(old('category') === 'Court_sejour')>Court séjour</option>
                <option value="Demeurant" @selected(old('category') === 'Demeurant')>Demeurant (résident du quartier)</option>
                <option value="Nouveau_converti" @selected(old('category') === 'Nouveau_converti')>Nouveau converti</option>
            </select>
        </div>

        <div id="recommendation-block">
            <label class="flex items-center gap-2 text-sm mb-2">
                <input type="checkbox" name="is_recommended" value="1" id="is_recommended_checkbox"
                       @checked(old('is_recommended'))
                       class="rounded border-gray-300 text-indigo-600">
                Recommandé(e) par un fidèle
            </label>

            <div id="recommended_by_field" class="{{ old('is_recommended') ? '' : 'hidden' }}">
                <label class="block text-sm font-medium text-gray-700">Nom du fidèle qui recommande</label>
                <input type="text" name="recommended_by" value="{{ old('recommended_by') }}"
                       class="mt-1 block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Notes</label>
            <textarea name="notes" rows="3"
                      class="mt-1 block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('notes') }}</textarea>
        </div>

        <button type="submit"
            class="w-full py-3 text-white text-sm font-semibold rounded-md" style="background:#3FA46A">
            Enregistrer
        </button>
    </form>
</div>

<script>
    document.getElementById('is_recommended_checkbox').addEventListener('change', function() {
        document.getElementById('recommended_by_field').classList.toggle('hidden', !this.checked);
    });
</script>
@endsection