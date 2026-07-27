@extends('layouts.dashboard')

@section('title', 'Nouvelle personne')
@section('page-title', 'Nouvelles personnes')

@section('content')
<div class="max-w-2xl mx-auto space-y-4">

    {{-- Navigation --}}
    <div class="flex items-center gap-3">
        <a href="{{ route(auth()->user()->dashboardRoute()) }}"
           class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
        <span class="text-gray-300">/</span>
        <a href="{{ route('newcomers.index') }}"
           class="text-sm text-gray-500 hover:text-gray-700">Nouvelles personnes</a>
        <span class="text-gray-300">/</span>
        <span class="text-sm text-gray-700 font-medium">Enregistrer</span>
    </div>

    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        <strong>Erreurs :</strong>
        <ul class="mt-1 list-disc list-inside text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('newcomers.store') }}">
        @csrf

        <div class="bg-white shadow-sm rounded-lg p-6 space-y-5">
            <h3 class="font-semibold text-gray-700 border-b pb-3">Informations personnelles</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Nom <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="lastname" value="{{ old('lastname') }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 @error('lastname') border-red-300 @enderror">
                    @error('lastname') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Prénom <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="firstname" value="{{ old('firstname') }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 @error('firstname') border-red-300 @enderror">
                    @error('firstname') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Genre</label>
                    <select name="gender"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Choisir --</option>
                        <option value="M" @selected(old('gender') === 'M')>Homme</option>
                        <option value="F" @selected(old('gender') === 'F')>Femme</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Date de naissance</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date') }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Téléphone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">WhatsApp</label>
                    <input type="text" name="whatsapp" value="{{ old('whatsapp') }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

            </div>

            <h3 class="font-semibold text-gray-700 border-b pb-3 mt-2">Informations de visite</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Catégorie <span class="text-red-500">*</span>
                    </label>
                    <select name="category" id="category"
                        onchange="toggleRecommendation(this.value)"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Choisir --</option>
                        <option value="passage"          @selected(old('category') === 'passage')>De passage</option>
                        <option value="court_sejour"     @selected(old('category') === 'court_sejour')>Court séjour</option>
                        <option value="demeurant"        @selected(old('category') === 'demeurant')>Demeurant</option>
                        <option value="nouveau_converti" @selected(old('category') === 'nouveau_converti')>Nouveau converti</option>
                    </select>
                    @error('category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Date de 1ère visite <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="first_visit_date"
                        value="{{ old('first_visit_date', date('Y-m-d')) }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('first_visit_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

            </div>

            {{-- Recommandation (masqué si nouveau_converti) --}}
            <div id="recommendation-fields" class="{{ old('category') === 'nouveau_converti' ? 'hidden' : '' }}">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700 cursor-pointer mt-2">
                            <input type="checkbox" name="is_recommended" value="1"
                                @checked(old('is_recommended'))
                                onchange="toggleRecommendedBy(this)"
                                class="rounded border-gray-300 text-indigo-600">
                            Recommandé(e) par un membre
                        </label>
                    </div>
                    <div id="recommended-by-field" class="{{ old('is_recommended') ? '' : 'hidden' }}">
                        <label class="block text-sm font-medium text-gray-700">Recommandé par</label>
                        <input type="text" name="recommended_by" value="{{ old('recommended_by') }}"
                            placeholder="Nom du membre..."
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Notes / Observations</label>
                <textarea name="notes" rows="3"
                    placeholder="Informations complémentaires..."
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('notes') }}</textarea>
            </div>

        </div>

        {{-- Boutons --}}
        <div class="flex justify-between mt-4">
            <a href="{{ route('newcomers.index') }}"
               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm hover:bg-gray-300">
                Annuler
            </a>
            <button type="submit"
                class="px-6 py-2 text-white rounded-md text-sm font-medium"
                style="background:#3FA46A">
                ✓ Enregistrer
            </button>
        </div>
    </form>

</div>

<script>
    function toggleRecommendation(category) {
        const fields = document.getElementById('recommendation-fields');
        fields.classList.toggle('hidden', category === 'nouveau_converti');
    }

    function toggleRecommendedBy(checkbox) {
        document.getElementById('recommended-by-field').classList.toggle('hidden', !checkbox.checked);
    }

    // Init au chargement (old values)
    toggleRecommendation(document.getElementById('category').value);
</script>
@endsection