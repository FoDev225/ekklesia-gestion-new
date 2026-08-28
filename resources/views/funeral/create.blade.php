@extends('layouts.dashboard')

@section('title', 'Nouvel enregistrement funéraire')
@section('page-title', 'Registre funéraire')

@section('content')
<div class="max-w-3xl mx-auto space-y-4">

    {{-- Navigation --}}
    <div class="flex items-center gap-3">
        <a href="{{ route(auth()->user()->dashboardRoute()) }}"
           class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
        <span class="text-gray-300">/</span>
        <a href="{{ route('funeral.index') }}"
           class="text-sm text-gray-500 hover:text-gray-700">Registre funéraire</a>
        <span class="text-gray-300">/</span>
        <span class="text-sm text-gray-700 font-medium">Nouvel enregistrement</span>
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

    <form method="POST" action="{{ route('funeral.store') }}">
        @csrf

        {{-- Fidèle concerné --}}
        <div class="bg-white shadow-sm rounded-lg p-6 space-y-4">
            <h3 class="font-semibold text-gray-700 border-b pb-3">Fidèle concerné</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Fidèle <span class="text-red-500">*</span>
                    </label>
                    <select name="believer_id" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 js-believer-select @error('believer_id') border-red-300 @enderror">
                        <option value="">-- Sélectionner un fidèle --</option>
                        @foreach($believers as $believer)
                            <option value="{{ $believer->id }}"
                                @selected(old('believer_id') == $believer->id)>
                                {{ $believer->lastname }} {{ $believer->firstname }}
                            </option>
                        @endforeach
                    </select>
                    @error('believer_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Lien de parenté <span class="text-red-500">*</span>
                    </label>
                    <select name="family_relationship" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Choisir --</option>
                        <option value="pere"   @selected(old('family_relationship') === 'pere')>Père</option>
                        <option value="mere"   @selected(old('family_relationship') === 'mere')>Mère</option>
                        <option value="enfant" @selected(old('family_relationship') === 'enfant')>Enfant biologique</option>
                    </select>
                    @error('family_relationship') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Informations du défunt --}}
        <div class="bg-white shadow-sm rounded-lg p-6 space-y-4">
            <h3 class="font-semibold text-gray-700 border-b pb-3">Informations du défunt</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nom <span class="text-red-500">*</span></label>
                    <input type="text" name="parent_lastname" value="{{ old('parent_lastname') }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 @error('parent_lastname') border-red-300 @enderror">
                    @error('parent_lastname') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Prénom(s) <span class="text-red-500">*</span></label>
                    <input type="text" name="parent_firstname" value="{{ old('parent_firstname') }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 @error('parent_firstname') border-red-300 @enderror">
                    @error('parent_firstname') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Date de décès <span class="text-red-500">*</span></label>
                    <input type="date" name="death_date" value="{{ old('death_date') }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('death_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Cause du décès</label>
                    <input type="text" name="cause_of_death" value="{{ old('cause_of_death') }}"
                        placeholder="Optionnel..."
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Lieu d'inhumation <span class="text-red-500">*</span></label>
                    <input type="text" name="burial_place" value="{{ old('burial_place') }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('burial_place') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Date des funérailles <span class="text-red-500">*</span></label>
                    <input type="date" name="funeral_date" value="{{ old('funeral_date') }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('funeral_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Lieu des funérailles <span class="text-red-500">*</span></label>
                    <input type="text" name="funeral_place" value="{{ old('funeral_place') }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('funeral_place') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Assistance de l'église --}}
        <div class="bg-white shadow-sm rounded-lg p-6 space-y-4">
            <h3 class="font-semibold text-gray-700 border-b pb-3">Assistance de l'église</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Nombre de pagnes (église) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="loincloths_number" value="{{ old('loincloths_number', '0') }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('loincloths_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Montant versé (FCFA) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="amount_paid" value="{{ old('amount_paid', '0') }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('amount_paid') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Assistance des fidèles --}}
        <div class="bg-white shadow-sm rounded-lg p-6 space-y-4">
            <h3 class="font-semibold text-gray-700 border-b pb-3">Assistance des fidèles <span class="text-gray-400 text-xs font-normal">(optionnel)</span></h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nombre de pagnes (fidèles)</label>
                    <input type="text" name="nbre_pagne" value="{{ old('nbre_pagne') }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Montant collecté (FCFA)</label>
                    <input type="text" name="cash_amount" value="{{ old('cash_amount') }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
        </div>

        {{-- Boutons --}}
        <div class="flex justify-between mt-3">
            <a href="{{ route('funeral.index') }}"
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
@endsection