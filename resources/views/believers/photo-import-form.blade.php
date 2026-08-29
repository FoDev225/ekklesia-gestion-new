@extends('layouts.dashboard')

@section('title', 'Import des photos')
@section('page-title', 'Import des photos de profil')

@section('content')
<div class="max-w-2xl mx-auto space-y-4">

    <div class="flex items-center gap-3">
        <a href="{{ route('believers.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Fidèles</a>
    </div>

    @if(session('not_found') && count(session('not_found')) > 0)
    <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded">
        <p class="font-semibold mb-2">⚠ {{ count(session('not_found')) }} photo(s) sans fidèle correspondant :</p>
        <ul class="list-disc list-inside text-sm">
            @foreach(session('not_found') as $file)
                <li>{{ $file }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white shadow-sm rounded-lg p-6">
        <h3 class="text-sm font-semibold text-gray-700 uppercase mb-2">Comment ça marche</h3>
        <ol class="text-sm text-gray-600 list-decimal list-inside space-y-1 mb-6">
            <li>Téléchargez la <a href="{{ route('believers.export.matricules') }}" class="text-indigo-600 underline">liste des matricules</a></li>
            <li>Renommez chaque photo avec le matricule exact du fidèle (ex: <code class="bg-gray-100 px-1 rounded">26-YOPNB01BS.jpg</code>)</li>
            <li>Sélectionnez toutes les photos renommées ci-dessous</li>
        </ol>

        <form action="{{ route('believers.photo-import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Photos (sélection multiple)</label>
                <input type="file" name="photos[]" multiple accept="image/*" required
                       class="w-full border-gray-300 rounded-md text-sm">
                @error('photos.*')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit"
                class="mt-4 w-full py-3 text-white text-sm font-semibold rounded-md" style="background:#3A9BDC">
                Importer les photos
            </button>
        </form>
    </div>

</div>
@endsection