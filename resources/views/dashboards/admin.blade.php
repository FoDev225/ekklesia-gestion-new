@extends('layouts.dashboard')

@section('title', 'Tableau de bord')
@section('page-title', 'Vue d\'ensemble')

@section('content')

{{-- ================================================================
     CARTES STATISTIQUES PRINCIPALES
================================================================ --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">

    <div class="stat-card stat-bleu bg-white rounded-lg p-4 shadow-sm">
        <p class="text-xs text-gray-500 uppercase font-medium">Total fidèles</p>
        <p class="text-3xl font-bold mt-1" style="color:#3A9BDC">{{ $total }}</p>
        <p class="text-xs text-gray-400 mt-1">Communauté totale</p>
    </div>

    <div class="stat-card stat-vert bg-white rounded-lg p-4 shadow-sm">
        <p class="text-xs text-gray-500 uppercase font-medium">Fidèles actifs</p>
        <p class="text-3xl font-bold mt-1" style="color:#3FA46A">{{ $actifs }}</p>
        <p class="text-xs text-gray-400 mt-1">
            {{ $total > 0 ? round(($actifs/$total)*100,1) : 0 }}% du total
        </p>
    </div>

    <div class="stat-card stat-dore bg-white rounded-lg p-4 shadow-sm">
        <p class="text-xs text-gray-500 uppercase font-medium">Fidèles inactifs</p>
        <p class="text-3xl font-bold mt-1" style="color:#C9A635">{{ $inactifs }}</p>
        <p class="text-xs text-gray-400 mt-1">
            {{ $total > 0 ? round(($inactifs/$total)*100,1) : 0 }}% du total
        </p>
    </div>

    <div class="stat-card stat-rouge bg-white rounded-lg p-4 shadow-sm">
        <p class="text-xs text-gray-500 uppercase font-medium">Sanctions disciplinaires</p>
        <p class="text-3xl font-bold mt-1 text-red-500">{{ $sanctionsActives }}</p>
        <p class="text-xs text-gray-400 mt-1">
            {{ $total > 0 ? round(($sanctionsActives/$total)*100,1) : 0 }}% du total
        </p>
    </div>

</div>

{{-- ================================================================
     CARTES DÉPARTS / DÉCÈS / ARRIVÉES
================================================================ --}}
<div class="grid grid-cols-3 gap-4 mb-6">

    <div class="bg-white rounded-lg p-4 shadow-sm border-t-4" style="border-color:#3FA46A">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 uppercase font-medium">Arrivées {{ now()->year }}</p>
                <p class="text-2xl font-bold mt-1" style="color:#3FA46A">{{ $nouveauxAnnee }}</p>
            </div>
            <span class="text-3xl opacity-20">🟢</span>
        </div>
    </div>

    <div class="bg-white rounded-lg p-4 shadow-sm border-t-4" style="border-color:#C9A635">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 uppercase font-medium">Départs</p>
                <p class="text-2xl font-bold mt-1" style="color:#C9A635">{{ $partis }}</p>
            </div>
            <span class="text-3xl opacity-20">🚶</span>
        </div>
    </div>

    <div class="bg-white rounded-lg p-4 shadow-sm border-t-4 border-gray-400">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 uppercase font-medium">Décès</p>
                <p class="text-2xl font-bold mt-1 text-gray-600">{{ $decedes }}</p>
            </div>
            <span class="text-3xl opacity-20">🕊️</span>
        </div>
    </div>

</div>

{{-- ================================================================
     GRAPHIQUES LIGNE 1 : Genre + Situation matrimoniale + Âge
================================================================ --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

    {{-- Camembert Genre --}}
    <div class="bg-white rounded-lg p-5 shadow-sm">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Répartition par genre</h3>
        <div class="flex items-center justify-center">
            <canvas id="chartGenre" width="180" height="180"></canvas>
        </div>
        <div class="flex justify-center gap-4 mt-3 text-xs text-gray-600">
            <span class="flex items-center gap-1">
                <span class="w-3 h-3 rounded-full inline-block" style="background:#3A9BDC"></span>
                Hommes : {{ $hommes }}
            </span>
            <span class="flex items-center gap-1">
                <span class="w-3 h-3 rounded-full inline-block" style="background:#C9A635"></span>
                Femmes : {{ $femmes }}
            </span>
        </div>
    </div>

    {{-- Camembert Situation matrimoniale --}}
    <div class="bg-white rounded-lg p-5 shadow-sm">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Situation matrimoniale</h3>
        <div class="flex items-center justify-center">
            <canvas id="chartMatrimonial" width="180" height="180"></canvas>
        </div>
        <div class="flex flex-wrap justify-center gap-2 mt-3 text-xs text-gray-600">
            @foreach(['Célibataire'=>'Célibataire','Marié(e)'=>'Marié(e)','Veuf(ve)'=>'Veuf(ve)','Divorcé'=>'Divorcé'] as $key => $label)
            <span class="flex items-center gap-1">
                <span class="w-2.5 h-2.5 rounded-full inline-block" style="background:{{ ['Célibataire'=>'#3A9BDC','Marié(e)'=>'#3FA46A','Veuf(ve)'=>'#C9A635','Divorcé'=>'#e53e3e'][$key] }}"></span>
                {{ $label }} : {{ $parSituation[$key] ?? 0 }}
            </span>
            @endforeach
        </div>
    </div>

    {{-- Barres Tranche d'âge --}}
    <div class="bg-white rounded-lg p-5 shadow-sm">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Catégories d'âge</h3>
        <canvas id="chartAge" width="280" height="200"></canvas>
    </div>

</div>

{{-- ================================================================
     GRAPHIQUES LIGNE 2 : Baptêmes + Sanctions + Évolution mensuelle
================================================================ --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

    {{-- Camembert Baptêmes --}}
    <div class="bg-white rounded-lg p-5 shadow-sm">
        <h3 class="text-sm font-semibold text-gray-700 mb-1">Baptêmes</h3>
        <p class="text-xs text-gray-400 mb-4">{{ $pctBaptises }}% de la communauté baptisée</p>
        <div class="flex items-center justify-center">
            <canvas id="chartBapteme" width="180" height="180"></canvas>
        </div>
        <div class="flex justify-center gap-4 mt-3 text-xs text-gray-600">
            <span class="flex items-center gap-1">
                <span class="w-3 h-3 rounded-full inline-block" style="background:#3A9BDC"></span>
                Baptisés : {{ $baptises }}
            </span>
            <span class="flex items-center gap-1">
                <span class="w-3 h-3 rounded-full inline-block" style="background:#e2e8f0"></span>
                Non baptisés : {{ $nonBaptises }}
            </span>
        </div>
    </div>

    {{-- Camembert Sanctions --}}
    {{-- Situation disciplinaire --}}
    <div class="bg-white rounded-lg p-5 shadow-sm">
        <h3 class="text-sm font-semibold text-gray-700 mb-1">Situation disciplinaire</h3>
        <p class="text-xs text-gray-400 mb-4">Sanctions actives</p>
        <p class="text-5xl font-bold text-center mt-6" style="color:#e53e3e">
            {{ $sanctionsActives }}
        </p>
        <p class="text-center text-xs text-gray-400 mt-2">
            {{ $total > 0 ? round(($sanctionsActives / $total) * 100, 1) : 0 }}% de la communauté
        </p>
    </div>

    {{-- Nouveaux par mois --}}
    <div class="bg-white rounded-lg p-5 shadow-sm">
        <h3 class="text-sm font-semibold text-gray-700 mb-1">Nouvelles arrivées</h3>
        <p class="text-xs text-gray-400 mb-4">12 derniers mois</p>
        <canvas id="chartMensuel" height="200"></canvas>
    </div>

</div>

{{-- ================================================================
     LIGNE 3 : Nouvelles personnes cette année
================================================================ --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

    <div class="bg-white rounded-lg p-5 shadow-sm">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Résumé {{ now()->year }}</h3>
        <div class="space-y-3">
            @php
                $items = [
                    ['label' => 'Nouveaux fidèles enregistrés', 'value' => $nouveauxAnnee, 'color' => '#3FA46A'],
                    ['label' => 'Nouvelles personnes accueillies', 'value' => $nouveauxPersonnes, 'color' => '#3A9BDC'],
                    ['label' => 'Départs enregistrés', 'value' => $partis, 'color' => '#C9A635'],
                    ['label' => 'Décès enregistrés', 'value' => $decedes, 'color' => '#6b7280'],
                    ['label' => 'Sanctions disciplinaires actives', 'value' => $sanctionnes, 'color' => '#e53e3e'],
                ];
            @endphp
            @foreach($items as $item)
            <div class="flex items-center justify-between py-2 border-b border-gray-50">
                <span class="text-sm text-gray-600">{{ $item['label'] }}</span>
                <span class="font-bold text-base" style="color:{{ $item['color'] }}">
                    {{ $item['value'] }}
                </span>
            </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-lg p-5 shadow-sm">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Catégories d'âge — détail</h3>
        <div class="space-y-2">
            @php
                $ageColors = [
                    'Nourrisson'   => '#f9a8d4',
                    'Pré-scolaire' => '#c4b5fd',
                    'ECODIM'       => '#3A9BDC',
                    'Jeunes'       => '#3FA46A',
                    'Adultes'      => '#C9A635',
                ];
            @endphp
            @foreach($parAgeTrie as $label => $nb)
            @php $pct = $total > 0 ? round(($nb / $total) * 100, 1) : 0; @endphp
            <div>
                <div class="flex justify-between text-xs text-gray-600 mb-1">
                    <span>{{ $label }}</span>
                    <span class="font-medium">{{ $nb }} <span class="text-gray-400">({{ $pct }}%)</span></span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="h-2 rounded-full" style="width:{{ $pct }}%; background:{{ $ageColors[$label] ?? '#3A9BDC' }}"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
const bleu = '#3A9BDC';
const vert  = '#3FA46A';
const dore  = '#C9A635';
const rouge = '#e53e3e';

// ── Camembert Genre ──
new Chart(document.getElementById('chartGenre'), {
    type: 'doughnut',
    data: {
        labels: ['Hommes', 'Femmes'],
        datasets: [{ data: [{{ $hommes }}, {{ $femmes }}], backgroundColor: [bleu, dore], borderWidth: 2 }]
    },
    options: { plugins: { legend: { display: false } }, cutout: '65%' }
});

// ── Camembert Situation matrimoniale ──
new Chart(document.getElementById('chartMatrimonial'), {
    type: 'doughnut',
    data: {
        labels: ['Célibataire', 'Marié(e)', 'Veuf/Veuve', 'Divorcé(e)'],
        datasets: [{
            data: [
                {{ $parSituation['celibataire'] ?? 0 }},
                {{ $parSituation['marie'] ?? 0 }},
                {{ $parSituation['veuf'] ?? 0 }},
                {{ $parSituation['divorce'] ?? 0 }}
            ],
            backgroundColor: [bleu, vert, dore, rouge],
            borderWidth: 2
        }]
    },
    options: { plugins: { legend: { display: false } }, cutout: '60%' }
});

// ── Barres Tranches d'âge ──
new Chart(document.getElementById('chartAge'), {
    type: 'bar',
    data: {
        labels: {!! json_encode(array_keys($parAgeTrie)) !!},
        datasets: [{
            data: {!! json_encode(array_values($parAgeTrie)) !!},
            backgroundColor: ['#f9a8d4','#c4b5fd', bleu, vert, dore],
            borderRadius: 4,
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});

// ── Camembert Baptêmes ──
new Chart(document.getElementById('chartBapteme'), {
    type: 'doughnut',
    data: {
        labels: ['Baptisés', 'Non baptisés'],
        datasets: [{ data: [{{ $baptises }}, {{ $nonBaptises }}], backgroundColor: [bleu, '#e2e8f0'], borderWidth: 2 }]
    },
    options: { plugins: { legend: { display: false } }, cutout: '65%' }
});

// ── Courbe Nouveaux par mois ──
new Chart(document.getElementById('chartMensuel'), {
    type: 'line',
    data: {
        labels: {!! json_encode($nouveauxParMois->keys()) !!},
        datasets: [{
            label: 'Nouveaux fidèles',
            data: {!! json_encode($nouveauxParMois->values()) !!},
            borderColor: vert,
            backgroundColor: 'rgba(63,164,106,.1)',
            borderWidth: 2,
            tension: 0.4,
            fill: true,
            pointBackgroundColor: vert,
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});
</script>
@endpush