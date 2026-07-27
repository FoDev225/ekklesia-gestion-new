@extends('layouts.dashboard')
@section('title', 'QR Codes d\'inscription')
@section('page-title', 'Liens et QR codes publics')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="bg-white shadow-sm rounded-lg p-6 text-center">
            <h3 class="font-semibold text-gray-700 mb-3">Inscription fidèle</h3>
            {!! QrCode::size(220)->generate(route('public.believer.form')) !!}
            <p class="text-xs text-gray-400 mt-3 break-all">{{ route('public.believer.form') }}</p>
        </div>

        <div class="bg-white shadow-sm rounded-lg p-6 text-center">
            <h3 class="font-semibold text-gray-700 mb-3">Nouvelle personne (service d'ordre)</h3>
            {!! QrCode::size(220)->generate(route('public.newcomer.form')) !!}
            <p class="text-xs text-gray-400 mt-3 break-all">{{ route('public.newcomer.form') }}</p>
        </div>
    </div>
@endsection