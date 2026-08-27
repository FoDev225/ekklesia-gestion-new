@extends('layouts.dashboard')

@section('title', 'Journal d\'activité')
@section('page-title', 'Journal d\'activité')

@section('content')
<div class="space-y-4">

    <div class="flex items-center gap-3">
        <a href="{{ route(auth()->user()->dashboardRoute()) }}" class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
        <span class="text-gray-300">/</span>
        <span class="text-sm text-gray-700 font-medium">Journal d'activité</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3FA46A">
            <p class="text-xs text-gray-500 uppercase font-medium">Connexions (total)</p>
            <p class="text-2xl font-bold mt-1" style="color:#3FA46A">{{ $stats['total_logins'] }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#C9A635">
            <p class="text-xs text-gray-500 uppercase font-medium">Échecs aujourd'hui</p>
            <p class="text-2xl font-bold mt-1" style="color:#C9A635">{{ $stats['failed_today'] }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#e53e3e">
            <p class="text-xs text-gray-500 uppercase font-medium">Blocages aujourd'hui</p>
            <p class="text-2xl font-bold mt-1" style="color:#e53e3e">{{ $stats['lockouts_today'] }}</p>
        </div>
    </div>

    <div class="bg-white shadow-sm rounded-lg p-4">
        <form method="GET" class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <select name="type" class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Tous les types</option>
                <option value="login" @selected(request('type')==='login')>Connexion</option>
                <option value="logout" @selected(request('type')==='logout')>Déconnexion</option>
                <option value="failed_login" @selected(request('type')==='failed_login')>Échec de connexion</option>
                <option value="lockout" @selected(request('type')==='lockout')>Compte bloqué</option>
                <option value="action" @selected(request('type')==='action')>Action</option>
            </select>
            <select name="user_id" class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Tous les utilisateurs</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}" @selected(request('user_id') == $u->id)>{{ $u->name }}</option>
                @endforeach
            </select>
            <input type="text" name="username" value="{{ request('username') }}" placeholder="Nom d'utilisateur tenté..."
                   class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700">Filtrer</button>
        </form>
    </div>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase whitespace-nowrap">Date/Heure</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase whitespace-nowrap">Utilisateur</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase whitespace-nowrap">Type</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Description</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase whitespace-nowrap">IP</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($logs as $log)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                        <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                            {{ $log->user?->name ?? $log->username ?? '—' }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="px-2 py-0.5 text-xs font-medium rounded {{ $log->type_color }}">
                                {{ $log->type_label }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $log->description }}</td>
                        <td class="px-4 py-3 text-gray-400 text-xs whitespace-nowrap">{{ $log->ip_address }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Aucune activité enregistrée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
        <div class="px-4 py-3 border-t border-gray-200">{{ $logs->links() }}</div>
        @endif
    </div>

</div>
@endsection