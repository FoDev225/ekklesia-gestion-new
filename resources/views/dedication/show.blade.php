@extends('layouts.dashboard')

@section('title', 'Fiche présentation d\'enfant')
@section('page-title', 'Présentations d\'enfants')

@section('content')
<div class="max-w-3xl mx-auto space-y-4">

    {{-- Navigation --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route(auth()->user()->dashboardRoute()) }}"
               class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
            <span class="text-gray-300">/</span>
            <a href="{{ route('dedication.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700">Présentations</a>
            <span class="text-gray-300">/</span>
            <span class="text-sm text-gray-700 font-medium">{{ $dedication->child_full_name }}</span>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('dedication.fiche', $dedication) }}"
               class="px-3 py-1.5 text-white text-sm rounded-md flex items-center gap-1"
               style="background:#1a2e4a">
                📄 Fiche PDF
            </a>
            @can('believers.edit')
            <a href="{{ route('dedication.edit', $dedication) }}"
               class="px-3 py-1.5 text-white text-sm rounded-md" style="background:#C9A635">
                Modifier
            </a>
            @endcan
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
    @endif

    {{-- En-tête enfant --}}
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="flex items-start justify-between">
            <div>
                <h3 class="text-xl font-bold text-gray-900">{{ $dedication->child_full_name }}</h3>
                <div class="flex items-center gap-3 mt-2">
                    <span class="px-2 py-1 rounded-full text-xs font-medium
                        {{ $dedication->gender === 'Masculin' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
                        {{ $dedication->gender }}
                    </span>
                    <span class="text-sm text-gray-500">
                        Né(e) le {{ $dedication->child_birthdate?->format('d/m/Y') }}
                        à {{ $dedication->child_birthplace }}
                    </span>
                </div>
            </div>
            <div class="text-right text-xs text-gray-400">
                <div>Demande : {{ $dedication->demande_date?->format('d/m/Y') }}</div>
                <div>Présentation : {{ $dedication->dedication_date?->format('d/m/Y') }}</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        {{-- Père --}}
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h4 class="font-semibold border-b pb-2 mb-4 text-blue-700">Le père</h4>
            @include('believers._info-row', ['label' => 'Nom & Prénom', 'value' => $dedication->father_display_name])
            @if($dedication->father_name && $dedication->father_id)
                @include('believers._info-row', ['label' => 'Nom sur fiche', 'value' => $dedication->father_name])
            @endif
            @include('believers._info-row', ['label' => 'Date de conversion', 'value' => $dedication->father?->churchInformation?->conversion_date?->format('d/m/Y')])
            @include('believers._info-row', ['label' => 'Baptisé le', 'value' => $dedication->father?->churchInformation?->baptism_date?->format('d/m/Y')])
            @include('believers._info-row', ['label' => 'Lieu de baptême', 'value' => $dedication->father?->churchInformation?->baptism_place])
            @include('believers._info-row', ['label' => 'N° carte membre', 'value' => $dedication->father?->churchInformation?->baptism_card_number])
            @if($dedication->father_id)
            <div class="mt-3">
                <a href="{{ route('believers.show', $dedication->father) }}"
                   class="text-xs hover:underline" style="color:#3A9BDC">Voir fiche fidèle →</a>
            </div>
            @endif
        </div>

        {{-- Mère --}}
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h4 class="font-semibold border-b pb-2 mb-4" style="color:#C9A635">La mère</h4>
            @include('believers._info-row', ['label' => 'Nom & Prénom', 'value' => $dedication->mother_display_name])
            @if($dedication->mother_name && $dedication->mother_id)
                @include('believers._info-row', ['label' => 'Nom sur fiche', 'value' => $dedication->mother_name])
            @endif
            @include('believers._info-row', ['label' => 'Date de conversion', 'value' => $dedication->mother?->churchInformation?->conversion_date?->format('d/m/Y')])
            @include('believers._info-row', ['label' => 'Baptisée le', 'value' => $dedication->mother?->churchInformation?->baptism_date?->format('d/m/Y')])
            @include('believers._info-row', ['label' => 'Lieu de baptême', 'value' => $dedication->mother?->churchInformation?->baptism_place])
            @include('believers._info-row', ['label' => 'N° carte membre', 'value' => $dedication->mother?->churchInformation?->baptism_card_number])
            @if($dedication->mother_id)
            <div class="mt-3">
                <a href="{{ route('believers.show', $dedication->mother) }}"
                   class="text-xs hover:underline" style="color:#3A9BDC">Voir fiche fidèle →</a>
            </div>
            @endif
        </div>

    </div>

    {{-- Infos enfant --}}
    <div class="bg-white shadow-sm rounded-lg p-6">
        <h4 class="font-semibold text-gray-700 border-b pb-2 mb-4">Informations de l'enfant</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8">
            @include('believers._info-row', ['label' => 'Nom', 'value' => $dedication->child_lastname])
            @include('believers._info-row', ['label' => 'Prénom(s)', 'value' => $dedication->child_firstname])
            @include('believers._info-row', ['label' => 'Sexe', 'value' => $dedication->gender])
            @include('believers._info-row', ['label' => 'Date de naissance', 'value' => $dedication->child_birthdate?->format('d/m/Y')])
            @include('believers._info-row', ['label' => 'Lieu de naissance', 'value' => $dedication->child_birthplace])
        </div>
    </div>

</div>
@endsection