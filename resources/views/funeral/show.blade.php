@extends('layouts.dashboard')

@section('title', 'Fiche funéraire')
@section('page-title', 'Registre funéraire')

@section('content')
<div class="max-w-3xl mx-auto space-y-4">

    {{-- Navigation --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route(auth()->user()->dashboardRoute()) }}"
               class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
            <span class="text-gray-300">/</span>
            <a href="{{ route('funeral.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700">Registre funéraire</a>
            <span class="text-gray-300">/</span>
            <span class="text-sm text-gray-700 font-medium">{{ $funeral->deceased_full_name }}</span>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('funeral.fiche', $funeral) }}"
               class="px-3 py-1.5 text-white text-sm rounded-md flex items-center gap-1"
               style="background:#1a2e4a">
                📄 Fiche PDF
            </a>
            @can('believers.edit')
            <a href="{{ route('funeral.edit', $funeral) }}"
               class="px-3 py-1.5 text-white text-sm rounded-md" style="background:#C9A635">
                Modifier
            </a>
            @endcan
        </div>
    </div>

    {{-- En-tête identité --}}
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="flex items-start justify-between">
            <div>
                <h3 class="text-xl font-bold text-gray-900">{{ $funeral->deceased_full_name }}</h3>
                <div class="flex items-center gap-3 mt-2">
                    @php
                        $colors = ['pere' => 'bg-blue-100 text-blue-700', 'mere' => 'bg-yellow-100 text-yellow-700', 'enfant' => 'bg-green-100 text-green-700'];
                    @endphp
                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $colors[$funeral->family_relationship] ?? 'bg-gray-100' }}">
                        {{ $funeral->family_relationship_label }}
                    </span>
                    <span class="text-sm text-gray-500">de</span>
                    <a href="{{ route('believers.show', $funeral->believer) }}"
                       class="text-sm font-medium hover:underline" style="color:#3A9BDC">
                        {{ $funeral->believer->full_name }}
                    </a>
                </div>
            </div>
            <div class="text-right text-xs text-gray-400">
                <div>Enregistré le {{ $funeral->created_at->format('d/m/Y') }}</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        {{-- Informations du défunt --}}
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h4 class="font-semibold text-gray-700 border-b pb-2 mb-4">Informations du défunt</h4>
            @include('believers._info-row', ['label' => 'Nom', 'value' => $funeral->parent_lastname])
            @include('believers._info-row', ['label' => 'Prénom(s)', 'value' => $funeral->parent_firstname])
            @include('believers._info-row', ['label' => 'Lien de parenté', 'value' => $funeral->family_relationship_label])
            @include('believers._info-row', ['label' => 'Date de décès', 'value' => $funeral->death_date?->format('d/m/Y')])
            @include('believers._info-row', ['label' => 'Cause du décès', 'value' => $funeral->cause_of_death])
            @include('believers._info-row', ['label' => 'Lieu d\'inhumation', 'value' => $funeral->burial_place])
            @include('believers._info-row', ['label' => 'Date des funérailles', 'value' => $funeral->funeral_date?->format('d/m/Y')])
            @include('believers._info-row', ['label' => 'Lieu des funérailles', 'value' => $funeral->funeral_place])
        </div>

        {{-- Assistance --}}
        <div class="space-y-4">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h4 class="font-semibold text-gray-700 border-b pb-2 mb-4">
                    Assistance de l'église
                </h4>
                @include('believers._info-row', ['label' => 'Nombre de pagnes', 'value' => $funeral->loincloths_number])
                @include('believers._info-row', ['label' => 'Montant versé', 'value' => $funeral->amount_paid . ' FCFA'])
            </div>

            <div class="bg-white shadow-sm rounded-lg p-6">
                <h4 class="font-semibold text-gray-700 border-b pb-2 mb-4">
                    Assistance des fidèles
                </h4>
                @include('believers._info-row', ['label' => 'Nombre de pagnes', 'value' => $funeral->nbre_pagne ?? '—'])
                @include('believers._info-row', ['label' => 'Montant collecté', 'value' => $funeral->cash_amount ? $funeral->cash_amount . ' FCFA' : '—'])
            </div>

            {{-- Fidèle concerné --}}
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h4 class="font-semibold text-gray-700 border-b pb-2 mb-4">Fidèle concerné</h4>
                @include('believers._info-row', ['label' => 'Nom', 'value' => $funeral->believer->full_name])
                @include('believers._info-row', ['label' => 'Téléphone', 'value' => $funeral->believer->address?->phone ?? '—'])
                @include('believers._info-row', ['label' => 'Commune', 'value' => $funeral->believer->address?->commune ?? '—'])
            </div>
        </div>

    </div>

</div>
@endsection