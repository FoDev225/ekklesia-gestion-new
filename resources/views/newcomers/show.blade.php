@extends('layouts.dashboard')

@section('title', 'Fiche nouvelle personne')
@section('page-title', 'Nouvelles personnes')

@section('content')
<div class="max-w-3xl mx-auto space-y-4">

    {{-- Navigation --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route(auth()->user()->dashboardRoute()) }}"
               class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
            <span class="text-gray-300">/</span>
            <a href="{{ route('newcomers.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700">Nouvelles personnes</a>
            <span class="text-gray-300">/</span>
            <span class="text-sm text-gray-700 font-medium">{{ $newcomer->full_name }}</span>
        </div>
        <div class="flex gap-2">
            @can('newcomers.edit')
            <a href="{{ route('newcomers.edit', $newcomer) }}"
               class="px-3 py-1.5 text-white text-sm rounded-md" style="background:#C9A635">
                Modifier
            </a>
            @endcan
            @if($newcomer->category === 'demeurant' && !$newcomer->is_converted)
            @can('believers.create')
            <form method="POST" action="{{ route('newcomers.convert', $newcomer) }}"
                  onsubmit="return confirm('Convertir {{ addslashes($newcomer->full_name) }} en fidèle ?')">
                @csrf
                <button type="submit"
                    class="px-3 py-1.5 text-white text-sm rounded-md font-medium"
                    style="background:#3FA46A">
                    → Convertir en fidèle
                </button>
            </form>
            @endcan
            @endif
        </div>
    </div>

    {{-- Flash --}}
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
    @endif

    {{-- Entête identité --}}
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="flex items-start justify-between">
            <div>
                <h3 class="text-2xl font-bold text-gray-900">{{ $newcomer->full_name }}</h3>
                <div class="flex items-center gap-3 mt-2">
                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $newcomer->category_color }}">
                        {{ $newcomer->category }}
                    </span>
                    @if($newcomer->gender)
                        <span class="text-sm text-gray-500">{{ $newcomer->gender_label }}</span>
                    @endif
                    @if($newcomer->is_converted)
                        <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded-full font-medium">
                            Converti en fidèle ✓
                        </span>
                    @endif
                </div>
            </div>
            <div class="text-right text-xs text-gray-400">
                <div>Enregistré le {{ $newcomer->created_at->format('d/m/Y') }}</div>
                <div>1ère visite : {{ $newcomer->first_visit_date?->format('d/m/Y') }}</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        {{-- Infos personnelles --}}
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h4 class="font-semibold text-gray-700 border-b pb-2 mb-4">Informations personnelles</h4>
            @include('believers._info-row', ['label' => 'Nom', 'value' => $newcomer->lastname])
            @include('believers._info-row', ['label' => 'Prénom', 'value' => $newcomer->firstname])
            @include('believers._info-row', ['label' => 'Genre', 'value' => $newcomer->gender_label])
            @include('believers._info-row', ['label' => 'Date de naissance', 'value' => $newcomer->birth_date?->format('d/m/Y')])
            @include('believers._info-row', ['label' => 'Téléphone', 'value' => $newcomer->phone])
            @include('believers._info-row', ['label' => 'WhatsApp', 'value' => $newcomer->whatsapp])
        </div>

        {{-- Infos visite --}}
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h4 class="font-semibold text-gray-700 border-b pb-2 mb-4">Informations de visite</h4>
            @include('believers._info-row', ['label' => 'Catégorie', 'value' => $newcomer->category])
            @include('believers._info-row', ['label' => '1ère visite', 'value' => $newcomer->first_visit_date?->format('d/m/Y')])

            @if($newcomer->category !== 'nouveau_converti')
                <div class="flex justify-between py-1 border-b border-gray-50">
                    <span class="text-sm text-gray-500">Recommandé(e)</span>
                    <span class="text-sm font-medium">
                        @if($newcomer->is_recommended)
                            <span class="text-green-600">✓ Oui</span>
                        @else
                            <span class="text-red-400">✗ Non</span>
                        @endif
                    </span>
                </div>
                @if($newcomer->is_recommended && $newcomer->recommended_by)
                    @include('believers._info-row', ['label' => 'Recommandé par', 'value' => $newcomer->recommended_by])
                @endif
            @endif

            @if($newcomer->is_converted)
                @include('believers._info-row', ['label' => 'Converti le', 'value' => $newcomer->converted_to_believer_at?->format('d/m/Y')])
                <div class="mt-2">
                    <a href="{{ route('believers.show', $newcomer->believer) }}"
                       class="text-sm font-medium underline" style="color:#3A9BDC">
                        Voir sa fiche fidèle →
                    </a>
                </div>
            @endif
        </div>

    </div>

    {{-- Notes --}}
    @if($newcomer->notes)
    <div class="bg-white shadow-sm rounded-lg p-6">
        <h4 class="font-semibold text-gray-700 border-b pb-2 mb-4">Notes / Observations</h4>
        <p class="text-sm text-gray-700 whitespace-pre-line">{{ $newcomer->notes }}</p>
    </div>
    @endif

</div>
@endsection