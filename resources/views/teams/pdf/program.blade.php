@php $church = \App\Models\Church::instance(); @endphp

@extends('layouts.pdf')

@section('doc-title', 'PROGRAMME D\'ACTIVITE - ' . strtoupper($team->name))

@section('doc-body')
<style>
    .meta-line {
        font-size: 9px;
        color: #555;
        margin-bottom: 14px;
        text-align: right;
    }

    .intro-box {
        border: 1px solid #d1d5db;
        border-radius: 4px;
        padding: 12px 14px;
        margin-bottom: 12px;
        font-size: 10px;
        line-height: 1.8;
        color: #222;
    }
    .intro-box .highlight { font-weight: bold; color: #1F4E79; }

    .section-title {
        background: #1F4E79;
        color: white;
        font-size: 9px;
        font-weight: bold;
        padding: 5px 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0;
    }

    .program-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 8.5px;
        margin-bottom: 10px;
    }
    .program-table th {
        background: #1F4E79;
        color: white;
        padding: 5px 8px;
        text-align: left;
        font-size: 8px;
        text-transform: uppercase;
    }
    .program-table td {
        padding: 6px 8px;
        border-bottom: 1px solid #e5e7eb;
        font-size: 8.5px;
    }
    .program-table tr:nth-child(even) td { background: #f9fafb; }
    .program-table .amount { font-weight: bold; color: #3FA46A; }

    .status-badge {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 10px;
        font-size: 7.5px;
        font-weight: bold;
    }
    .status-en-cours      { background: #fef3c7; color: #92400e; }
    .status-realisee      { background: #d1fae5; color: #065f46; }
    .status-non-realisee  { background: #fee2e2; color: #991b1b; }

    .signatures {
        display: table;
        width: 100%;
        margin-top: 30px;
    }
    .sig-cell {
        display: table-cell;
        text-align: center;
        font-size: 9px;
        font-weight: bold;
        color: #374151;
        width: 50%;
    }
    .sig-line {
        border-top: 1px solid #333;
        margin: 28px 20px 0 20px;
    }
</style>

<div class="meta-line">
    Abidjan, le {{ now()->format('d/m/Y') }} &nbsp;|&nbsp;
    Réf. Équipe N° {{ str_pad($team->id, 4, '0', STR_PAD_LEFT) }}
</div>

<div class="intro-box">
    Programme d'activité de l'équipe <span class="highlight">{{ $team->name }}</span>
    de l'<span class="highlight">Eglise Locale AEBECI {{ $church->church_name }}</span>,
    comprenant <span class="highlight">{{ $activities->count() }} activité(s)</span> programmée(s) à ce jour.
</div>

<div class="section-title">Détail du programme</div>
<table class="program-table">
    <thead>
        <tr>
            <th>Activité</th>
            <th>Date</th>
            <th>Thème</th>
            <th>Modérateur</th>
            <th>Prédicateur</th>
            <th>Lieu</th>
            <th>Budget</th>
            <th>Statut</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($activities as $activity)
        <tr>
            <td>{{ $activity->title }}</td>
            <td>{{ $activity->date->format('d/m/Y') }}</td>
            <td>{{ $activity->theme ?? '—' }}</td>
            <td>{{ $activity->moderator ?? '—' }}</td>
            <td>{{ $activity->preacher ?? '—' }}</td>
            <td>{{ $activity->location ?? '—' }}</td>
            <td class="amount">{{ $activity->budget ? number_format($activity->budget, 0, ',', ' ') . ' FCFA' : '—' }}</td>
            <td>
                @switch($activity->status)
                    @case('realisee')
                        <span class="status-badge status-realisee">Réalisée</span>
                        @break
                    @case('non_realisee')
                        <span class="status-badge status-non-realisee">Non réalisée</span>
                        @break
                    @default
                        <span class="status-badge status-en-cours">En cours</span>
                @endswitch
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" style="text-align:center; color:#9ca3af; font-style:italic;">
                Aucune activité programmée.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="signatures">
    <div class="sig-cell">
        <div class="sig-line"></div>
        Responsable de l'équipe
    </div>
    <div class="sig-cell">
        <div class="sig-line"></div>
        Secrétariat général
    </div>
</div>

@endsection