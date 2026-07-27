@extends('layouts.dashboard')

@section('title', 'Nouvelle équipe')
@section('page-title', 'Nouvelle équipe')

@section('content')
<div class="space-y-4">

    {{-- Navigation --}}
    <div class="flex items-center gap-3">
        <a href="{{ route(auth()->user()->dashboardRoute()) }}"
           class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
        <span class="text-gray-300">/</span>
        <a href="{{ route('teams.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Équipes</a>
        <span class="text-gray-300">/</span>
        <span class="text-sm text-gray-700 font-medium">Nouvelle équipe</span>
    </div>

    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        Veuillez corriger les erreurs ci-dessous.
    </div>
    @endif

    <div class="bg-white shadow-sm rounded-lg p-6 max-w-2xl">
        <form action="{{ route('teams.store') }}" method="POST" class="space-y-4">
            @csrf
            @include('teams._form', ['team' => null])

            <div class="flex gap-2 pt-2">
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-md"
                    style="background:#3A9BDC">
                    Créer
                </button>
                <a href="{{ route('teams.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300">
                    Annuler
                </a>
            </div>
        </form>
    </div>

</div>
@endsection