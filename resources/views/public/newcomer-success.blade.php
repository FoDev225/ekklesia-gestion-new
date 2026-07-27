@extends('public.layout')
@section('title', 'Enregistrement réussi')

@section('content')
    <div class="bg-white shadow-sm rounded-lg p-8 text-center">
        <div class="w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center" style="background:#3FA46A">
            <span class="text-white text-2xl">✓</span>
        </div>
        <h2 class="text-lg font-bold text-gray-800 mb-2">Personne enregistrée !</h2>
        <p class="text-sm text-gray-500">Merci pour cet accueil.</p>
        <a href="{{ route('public.newcomer.form') }}"
        class="inline-block mt-4 text-sm font-medium" style="color:#3A9BDC">
            + Enregistrer une autre personne
        </a>
    </div>
@endsection