@php $church = \App\Models\Church::instance(); @endphp

@extends('layouts.pdf')

@section('doc-title', 'LISTE DES MEMBRES - ' . strtoupper($group->name))

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

    .members-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 9px;
        margin-bottom: 10px;
    }
    .members-table th {
        background: #1F4E79;
        color: white;
        padding: 5px 10px;
        text-align: left;
        font-size: 8.5px;
        text-transform: uppercase;
    }
    .members-table td {
        padding: 6px 10px;
        border-bottom: 1px solid #e5e7eb;
        font-size: 9px;
    }
    .members-table tr:nth-child(even) td { background: #f9fafb; }
    .members-table .num-cell { width: 6%; color: #6b7280; }

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
    Réf. Groupe N° {{ str_pad($group->id, 4, '0', STR_PAD_LEFT) }}
</div>

<div class="intro-box">
    Liste nominative des membres inscrits au sein du groupe
    <span class="highlight">{{ $group->name }}</span>
    de l'<span class="highlight">Eglise Locale AEBECI {{ $church->church_name }}</span>,
    comptant <span class="highlight">{{ $group->believers->count() }} membre(s)</span> à ce jour.
    @if($group->leader)
        Responsable du groupe : <span class="highlight">{{ $group->leader->full_name ?? $group->leader->name }}</span>.
    @endif
</div>

<div class="section-title">Membres du groupe</div>
<table class="members-table">
    <thead>
        <tr>
            <th class="num-cell">N°</th>
            <th>Nom & Prénom</th>
            <th>Membre depuis</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($group->believers as $i => $believer)
        <tr>
            <td class="num-cell">{{ $i + 1 }}</td>
            <td>{{ $believer->full_name ?? $believer->name }}</td>
            <td>
                {{ $believer->pivot->joined_at
                    ? \Carbon\Carbon::parse($believer->pivot->joined_at)->format('d/m/Y')
                    : '—' }}
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="3" style="text-align:center; color:#9ca3af; font-style:italic;">
                Aucun membre enregistré.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="signatures">
    <div class="sig-cell">
        <div class="sig-line"></div>
        Responsable du groupe
    </div>
    <div class="sig-cell">
        <div class="sig-line"></div>
        Secrétariat général
    </div>
</div>

@endsection