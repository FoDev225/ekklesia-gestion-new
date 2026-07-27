@extends('layouts.dashboard')
@section('title', 'Acteurs de culte')
@section('page-title', 'Gestion des cultes')

@section('content')
<div class="max-w-4xl mx-auto space-y-4">

    <div class="flex items-center gap-3">
        <a href="{{ route(auth()->user()->dashboardRoute()) }}" class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
        <span class="text-gray-300">/</span>
        <a href="{{ route('cultes.periodes') }}" class="text-sm text-gray-500 hover:text-gray-700">Cultes</a>
        <span class="text-gray-300">/</span>
        <span class="text-sm font-medium text-gray-700">Acteurs de culte</span>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
    @endif
    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        <ul class="list-disc list-inside text-sm">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        {{-- Formulaire ajout --}}
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h4 class="font-semibold text-gray-700 border-b pb-3 mb-4">Ajouter un acteur</h4>
            <form method="POST" action="{{ route('cultes.acteurs.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Fidèle <span class="text-red-500">*</span></label>
                    <select name="believer_id" required
                        class="mt-1 block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Sélectionner --</option>
                        @foreach($believers as $b)
                        <option value="{{ $b->id }}">{{ $b->lastname }} {{ $b->firstname }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Rôle <span class="text-red-500">*</span></label>
                    <select name="service_role_id" required
                        class="mt-1 block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Choisir un rôle --</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit"
                    class="w-full px-4 py-2 text-white text-sm rounded-md font-medium" style="background:#3FA46A">
                    + Ajouter
                </button>
            </form>
        </div>

        {{-- Liste acteurs par rôle --}}
        <div class="md:col-span-2 space-y-4">
            @forelse($acteurs as $roleName => $roleActeurs)
            <div class="bg-white shadow-sm rounded-lg p-5">
                <h4 class="font-semibold border-b pb-2 mb-3 text-sm uppercase tracking-wide" style="color:#1F4E79">
                    {{ $roleName }}
                    <span class="ml-2 px-2 py-0.5 rounded-full text-xs font-normal" style="background:#3A9BDC; color:white">
                        {{ $roleActeurs->count() }}
                    </span>
                </h4>
                <div class="space-y-1">
                    @foreach($roleActeurs as $acteur)
                    <div class="flex items-center justify-between py-1.5 border-b border-gray-50 last:border-0">
                        <span class="text-sm text-gray-800">{{ $acteur->believer->full_name }}</span>
                        <form method="POST" action="{{ route('cultes.acteurs.destroy', $acteur) }}" class="inline"
                              onsubmit="return confirm('Retirer cet acteur ?')">
                            @csrf @method('DELETE')
                            <button class="text-red-400 hover:text-red-600 text-xs px-2 py-1 rounded hover:bg-red-50">
                                Retirer
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
            @empty
            <div class="bg-white shadow-sm rounded-lg p-8 text-center text-gray-400">
                Aucun acteur de culte enregistré.
            </div>
            @endforelse
        </div>

    </div>
</div>
@endsection