@extends('layouts.dashboard')

@section('title', 'Nouvelle présentation d\'enfant')
@section('page-title', 'Présentations d\'enfants')

@section('content')
<div class="max-w-3xl mx-auto space-y-4">

    {{-- Navigation --}}
    <div class="flex items-center gap-3">
        <a href="{{ route(auth()->user()->dashboardRoute()) }}" class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
        <span class="text-gray-300">/</span>
        <a href="{{ route('dedication.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Présentations</a>
        <span class="text-gray-300">/</span>
        <span class="text-sm text-gray-700 font-medium">Nouvelle</span>
    </div>

    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        <strong>Erreurs :</strong>
        <ul class="mt-1 list-disc list-inside text-sm">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('dedication.store') }}">
        @csrf

        {{-- Dates --}}
        <div class="bg-white shadow-sm rounded-lg p-6 space-y-4">
            <h3 class="font-semibold text-gray-700 border-b pb-3">Dates</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Date de la demande <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="demande_date"
                        value="{{ old('demande_date', date('Y-m-d')) }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('demande_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Date de présentation <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="dedication_date" value="{{ old('dedication_date') }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('dedication_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Parents --}}
        <div class="bg-white shadow-sm rounded-lg p-6 space-y-4">
            <h3 class="font-semibold text-gray-700 border-b pb-3">Parents</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- Père --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Père <span class="text-red-500">*</span>
                    </label>
                    <select name="father_id" id="father_id" required
                        onchange="fillParent('father', this.value)"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 js-believer-select @error('father_id') border-red-300 @enderror">
                        <option value="">-- Sélectionner le père --</option>
                        @foreach($believers->whereIn('gender', ['M']) as $b)
                            <option value="{{ $b->id }}" @selected(old('father_id') == $b->id)>
                                {{ $b->lastname }} {{ $b->firstname }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Mère --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Mère <span class="text-red-500">*</span>
                    </label>
                    <select name="mother_id" id="mother_id" required
                        onchange="fillParent('mother', this.value)"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 js-believer-select @error('mother_id') border-red-300 @enderror">
                        <option value="">-- Sélectionner la mère --</option>
                        @foreach($believers->whereIn('gender', ['F']) as $b)
                            <option value="{{ $b->id }}" @selected(old('mother_id') == $b->id)>
                                {{ $b->lastname }} {{ $b->firstname }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>

        {{-- Enfant --}}
        <div class="bg-white shadow-sm rounded-lg p-6 space-y-4">
            <h3 class="font-semibold text-gray-700 border-b pb-3">Informations de l'enfant</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Nom <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="child_lastname" value="{{ old('child_lastname') }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 @error('child_lastname') border-red-300 @enderror">
                    @error('child_lastname') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Prénom(s) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="child_firstname" value="{{ old('child_firstname') }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 @error('child_firstname') border-red-300 @enderror">
                    @error('child_firstname') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Sexe <span class="text-red-500">*</span>
                    </label>
                    <select name="gender" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Choisir --</option>
                        <option value="Masculin" @selected(old('gender') === 'Masculin')>Masculin</option>
                        <option value="Féminin"  @selected(old('gender') === 'Féminin')>Féminin</option>
                    </select>
                    @error('gender') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Date de naissance <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="child_birthdate" value="{{ old('child_birthdate') }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('child_birthdate') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Lieu de naissance <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="child_birthplace" value="{{ old('child_birthplace') }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('child_birthplace') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

            </div>
        </div>

        <div class="flex justify-between mt-3">
            <a href="{{ route('dedication.index') }}"
               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm hover:bg-gray-300">Annuler</a>
            <button type="submit"
                class="px-6 py-2 text-white rounded-md text-sm font-medium" style="background:#3FA46A">
                ✓ Enregistrer
            </button>
        </div>
    </form>
</div>

<script>
const believers = @json($believers->keyBy('id'));

function fillParent(role, believerId) {
    if (!believerId) return;
    const b = believers[believerId];
    if (!b) return;
    // Pré-remplir le nom affiché avec le nom du fidèle
    const nameField = document.getElementById(role + '_name');
    if (nameField && !nameField.value) {
        nameField.value = b.lastname + ' ' + b.firstname;
    }
}
</script>
@endsection