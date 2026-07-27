@extends('layouts.dashboard')
@section('title', isset($periode) ? 'Modifier période' : 'Nouvelle période')
@section('page-title', 'Gestion des cultes')

@section('content')
<div class="max-w-2xl mx-auto space-y-4">

    <div class="flex items-center gap-3">
        <a href="{{ route(auth()->user()->dashboardRoute()) }}" class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
        <span class="text-gray-300">/</span>
        <a href="{{ route('cultes.periodes') }}" class="text-sm text-gray-500 hover:text-gray-700">Périodes</a>
        <span class="text-gray-300">/</span>
        <span class="text-sm font-medium text-gray-700">{{ isset($periode) ? 'Modifier' : 'Nouvelle' }}</span>
    </div>

    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        <ul class="list-disc list-inside text-sm">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form method="POST"
          action="{{ isset($periode) ? route('cultes.periodes.update', $periode) : route('cultes.periodes.store') }}">
        @csrf
        @if(isset($periode)) @method('PUT') @endif

        <div class="bg-white shadow-sm rounded-lg p-6 space-y-4">
            <h3 class="font-semibold text-gray-700 border-b pb-3">
                {{ isset($periode) ? 'Modifier la période' : 'Nouvelle période de cultes' }}
            </h3>

            <div>
                <label class="block text-sm font-medium text-gray-700">Nom de la période <span class="text-red-500">*</span></label>
                <input type="text" name="name"
                    value="{{ old('name', $periode->name ?? '') }}"
                    placeholder="Ex: Mars à Juillet 2026"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Thème général</label>
                <input type="text" name="general_theme"
                    value="{{ old('general_theme', $periode->general_theme ?? '') }}"
                    placeholder="Ex: La Mission"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date de début <span class="text-red-500">*</span></label>
                    <input type="date" name="start_date"
                        value="{{ old('start_date', isset($periode) ? $periode->start_date?->format('Y-m-d') : '') }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date de fin <span class="text-red-500">*</span></label>
                    <input type="date" name="end_date"
                        value="{{ old('end_date', isset($periode) ? $periode->end_date?->format('Y-m-d') : '') }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <div>
                <label class="flex items-center gap-2 cursor-pointer text-sm">
                    <input type="checkbox" name="is_active" value="1"
                        @checked(old('is_active', $periode->is_active ?? false))
                        class="rounded border-gray-300 text-indigo-600">
                    Activer cette période (désactive les autres)
                </label>
            </div>
        </div>

        <div class="flex justify-between mt-4">
            <a href="{{ route('cultes.periodes') }}"
               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm hover:bg-gray-300">Annuler</a>
            <button type="submit"
                class="px-6 py-2 text-white rounded-md text-sm font-medium" style="background:#3FA46A">
                ✓ {{ isset($periode) ? 'Enregistrer' : 'Créer la période' }}
            </button>
        </div>
    </form>
</div>
@endsection