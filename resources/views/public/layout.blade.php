<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — @yield('title')</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="max-w-xl mx-auto px-4 py-8">

        {{-- En-tête église --}}
        <div class="text-center mb-6">
            <div class="w-16 h-16 rounded-full mx-auto mb-3 flex items-center justify-center"
                 style="background:#3A9BDC">
                @if($church->photo_path)
                    <img src="{{ Storage::url($church->photo_path) }}" alt="Logo" class="w-14 h-14 rounded-full object-cover">
                @else
                    <span class="text-white font-bold text-xl">✝</span>
                @endif
            </div>
            <h1 class="text-lg font-bold text-gray-800">{{ $church->organisation_name }}</h1>
            <p class="text-sm text-gray-500">{{ $church->district }} — {{ $church->church_name }}</p>
        </div>

        @yield('content')

    </div>
</body>
</html>