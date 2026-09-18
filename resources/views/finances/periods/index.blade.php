@extends('layouts.dashboard')

@section('title', 'Périodes comptables')
@section('page-title', 'Périodes comptables')

@section('content')
<div class="space-y-4">

    <div class="flex items-center gap-3">
        <a href="{{ route('finances.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Finances</a>
        <span class="text-gray-300">/</span>
        <span class="text-sm text-gray-700 font-medium">Périodes</span>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <div class="md:col-span-2">
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Période</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Du</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Au</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">AG prévue</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($periods as $p)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $p->label }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $p->date_debut->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $p->date_fin->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $p->date_ag_prevue?->format('d/m/Y') ?? '—' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">Aucune période créée.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="md:col-span-1">
            <div class="bg-white shadow-sm rounded-lg p-4">
                <h3 class="text-sm font-semibold text-gray-700 uppercase mb-3">Nouvelle période</h3>
                <form action="{{ route('finances.periods.store') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Semestre <span class="text-red-500">*</span></label>
                        <select name="type" required class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="S1">S1 (Janvier - Juin)</option>
                            <option value="S2">S2 (Juillet - Décembre)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Année <span class="text-red-500">*</span></label>
                        <input type="number" name="annee" required value="{{ old('annee', now()->year) }}"
                            class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Date de début <span class="text-red-500">*</span></label>
                        <input type="date" name="date_debut" required value="{{ old('date_debut') }}"
                            class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Date de fin <span class="text-red-500">*</span></label>
                        <input type="date" name="date_fin" required value="{{ old('date_fin') }}"
                            class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Date AG prévue</label>
                        <input type="date" name="date_ag_prevue" value="{{ old('date_ag_prevue') }}"
                            class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <button type="submit"
                        class="w-full inline-flex items-center justify-center px-4 py-2 text-white text-sm font-medium rounded-md" style="background:#3A9BDC">
                        Créer
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection