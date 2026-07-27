@extends('layouts.dashboard')

@section('title', 'Fiche mariage')
@section('page-title', 'Registre des mariages')

@section('content')
<div class="max-w-4xl mx-auto space-y-4">

    {{-- Navigation --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route(auth()->user()->dashboardRoute()) }}"
               class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
            <span class="text-gray-300">/</span>
            <a href="{{ route('mariage.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700">Mariages</a>
            <span class="text-gray-300">/</span>
            <span class="text-sm text-gray-700 font-medium">
                {{ $mariage->groom_display_name }} & {{ $mariage->bride_display_name }}
            </span>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('mariage.fiche', $mariage) }}"
               class="px-3 py-1.5 text-white text-sm rounded-md flex items-center gap-1"
               style="background:#1a2e4a">
                📄 Fiche PDF
            </a>
            @can('believers.edit')
            <a href="{{ route('mariage.edit', $mariage) }}"
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

    {{-- En-tête couple --}}
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="flex items-center justify-center gap-8">
            {{-- Photo époux --}}
            <div class="text-center">
                @if($mariage->groom_photo)
                    <img src="{{ Storage::url($mariage->groom_photo) }}"
                         alt="Photo époux"
                         class="w-24 h-24 object-cover rounded-lg border-2 border-blue-200 mx-auto">
                @else
                    <div class="w-24 h-24 bg-blue-50 border-2 border-blue-200 rounded-lg flex items-center justify-center mx-auto">
                        <span class="text-blue-300 text-3xl">👤</span>
                    </div>
                @endif
                <p class="mt-2 font-bold text-gray-800 text-sm">{{ $mariage->groom_display_name }}</p>
                @if($mariage->groom_id)
                    <a href="{{ route('believers.show', $mariage->groom) }}"
                       class="text-xs hover:underline" style="color:#3A9BDC">Voir fiche fidèle</a>
                @endif
            </div>

            <div class="text-center">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-lg"
                     style="background:#C9A635; color:white">&</div>
                <p class="text-xs text-gray-400 mt-1">
                    {{ $mariage->religious_marriage_date?->format('d/m/Y') }}
                </p>
            </div>

            {{-- Photo épouse --}}
            <div class="text-center">
                @if($mariage->bride_photo)
                    <img src="{{ Storage::url($mariage->bride_photo) }}"
                         alt="Photo épouse"
                         class="w-24 h-24 object-cover rounded-lg border-2 border-yellow-200 mx-auto">
                @else
                    <div class="w-24 h-24 bg-yellow-50 border-2 border-yellow-200 rounded-lg flex items-center justify-center mx-auto">
                        <span class="text-yellow-300 text-3xl">👤</span>
                    </div>
                @endif
                <p class="mt-2 font-bold text-gray-800 text-sm">{{ $mariage->bride_display_name }}</p>
                @if($mariage->bride_id)
                    <a href="{{ route('believers.show', $mariage->bride) }}"
                       class="text-xs hover:underline" style="color:#3A9BDC">Voir fiche fidèle</a>
                @endif
            </div>
        </div>
    </div>

    {{-- Infos époux & épouse --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div class="bg-white shadow-sm rounded-lg p-6">
            <h4 class="font-semibold border-b pb-2 mb-4 flex items-center gap-2"
                style="color:#3A9BDC">
                <span class="px-2 py-0.5 rounded text-xs font-bold text-white" style="background:#3A9BDC">ÉPOUX</span>
            </h4>
            @include('believers._info-row', ['label' => 'Nom & Prénom', 'value' => $mariage->groom_display_name])
            @include('believers._info-row', ['label' => 'Date de naissance', 'value' => $mariage->groom_birthdate_display])
            @include('believers._info-row', ['label' => 'Lieu de naissance', 'value' => $mariage->groom_birth_place_display])
            @include('believers._info-row', ['label' => 'Date de baptême', 'value' => $mariage->groom_bapistism_date?->format('d/m/Y')])
            @include('believers._info-row', ['label' => 'Lieu de baptême', 'value' => $mariage->groom_bapistism_place])
            @include('believers._info-row', ['label' => 'Pasteur du baptême', 'value' => $mariage->baptism_officer_groom])
            @include('believers._info-row', ['label' => 'Profession', 'value' => $mariage->groom_profession])
        </div>

        <div class="bg-white shadow-sm rounded-lg p-6">
            <h4 class="font-semibold border-b pb-2 mb-4 flex items-center gap-2"
                style="color:#C9A635">
                <span class="px-2 py-0.5 rounded text-xs font-bold text-white" style="background:#C9A635">ÉPOUSE</span>
            </h4>
            @include('believers._info-row', ['label' => 'Nom & Prénom', 'value' => $mariage->bride_display_name])
            @include('believers._info-row', ['label' => 'Date de naissance', 'value' => $mariage->bride_birthdate_display])
            @include('believers._info-row', ['label' => 'Lieu de naissance', 'value' => $mariage->bride_birth_place_display])
            @include('believers._info-row', ['label' => 'Date de baptême', 'value' => $mariage->bride_bapistism_date?->format('d/m/Y')])
            @include('believers._info-row', ['label' => 'Lieu de baptême', 'value' => $mariage->bride_bapistism_place])
            @include('believers._info-row', ['label' => 'Pasteur du baptême', 'value' => $mariage->baptism_officer_bride])
            @include('believers._info-row', ['label' => 'Profession', 'value' => $mariage->bride_profession])
        </div>

    </div>

    {{-- Cérémonies --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div class="bg-white shadow-sm rounded-lg p-6">
            <h4 class="font-semibold text-gray-700 border-b pb-2 mb-4">Cérémonie civile</h4>
            @include('believers._info-row', ['label' => 'Date', 'value' => $mariage->civil_marriage_date?->format('d/m/Y')])
            @include('believers._info-row', ['label' => 'Lieu', 'value' => $mariage->civil_marriage_place])
        </div>

        <div class="bg-white shadow-sm rounded-lg p-6">
            <h4 class="font-semibold text-gray-700 border-b pb-2 mb-4">Cérémonie religieuse</h4>
            @include('believers._info-row', ['label' => 'Date', 'value' => $mariage->religious_marriage_date?->format('d/m/Y')])
            @include('believers._info-row', ['label' => 'Lieu', 'value' => $mariage->religious_marriage_place])
            @include('believers._info-row', ['label' => 'Maître de cérémonie', 'value' => $mariage->wedding_mc])
            @include('believers._info-row', ['label' => 'Prédicateur', 'value' => $mariage->wedding_preacher])
            @include('believers._info-row', ['label' => 'La Bible remise par', 'value' => $mariage->hand_bible])
            @include('believers._info-row', ['label' => 'Pasteur officiant', 'value' => $mariage->officiant])
        </div>

    </div>

    {{-- Témoins --}}
    <div class="bg-white shadow-sm rounded-lg p-6">
        <h4 class="font-semibold text-gray-700 border-b pb-2 mb-4">Témoins</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-xs font-semibold text-blue-700 uppercase mb-2">Témoin de l'époux</p>
                @include('believers._info-row', ['label' => 'Nom', 'value' => $mariage->groom_witness])
                @include('believers._info-row', ['label' => 'Profession', 'value' => $mariage->groom_witness_profession])
            </div>
            <div>
                <p class="text-xs font-semibold text-yellow-700 uppercase mb-2">Témoin de l'épouse</p>
                @include('believers._info-row', ['label' => 'Nom', 'value' => $mariage->bride_witness])
                @include('believers._info-row', ['label' => 'Profession', 'value' => $mariage->bride_witness_profession])
            </div>
        </div>
    </div>

</div>
@endsection