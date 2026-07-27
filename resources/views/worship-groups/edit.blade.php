@extends('layouts.dashboard')

@section('title', 'Modifier ' . $worshipGroup->name)
@section('page-title', 'Modifier le groupe de louange')

@section('content')
<div class="space-y-4">

    <div class="flex items-center gap-3">
        <a href="{{ route(auth()->user()->dashboardRoute()) }}"
           class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
        <span class="text-gray-300">/</span>
        <a href="{{ route('worship-groups.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Groupes de louange</a>
        <span class="text-gray-300">/</span>
        <a href="{{ route('worship-groups.show', $worshipGroup) }}" class="text-sm text-gray-500 hover:text-gray-700">{{ $worshipGroup->name }}</a>
        <span class="text-gray-300">/</span>
        <span class="text-sm text-gray-700 font-medium">Modifier</span>
    </div>

    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        Veuillez corriger les erreurs ci-dessous.
    </div>
    @endif

    <div class="bg-white shadow-sm rounded-lg p-6 max-w-2xl">
        <form action="{{ route('worship-groups.update', $worshipGroup) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            @include('worship-groups._form')

            <div class="flex gap-2 pt-2">
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-md"
                    style="background:#C9A635">
                    Enregistrer
                </button>
                <a href="{{ route('worship-groups.show', $worshipGroup) }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300">
                    Annuler
                </a>
            </div>
        </form>
    </div>

</div>
@endsection