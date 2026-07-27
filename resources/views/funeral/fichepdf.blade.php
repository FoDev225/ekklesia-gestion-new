@php $church = \App\Models\Church::instance(); @endphp

@extends('layouts.pdf')

@section('doc-title', 'REGISTRE FUNERAIRE')

@section('doc-body')
<style>
    .meta-line {
        font-size: 11px;
        color: #555;
        margin-bottom: 18px;
        text-align: right;
    }

    .intro-box {
        border: 1px solid #d1d5db;
        border-radius: 5px;
        padding: 16px 18px;
        margin-bottom: 18px;
        font-size: 12.5px;
        line-height: 2;
        color: #222;
    }
    .intro-box .highlight { font-weight: bold; color: #1F4E79; }

    .section-title {
        background: #1F4E79;
        color: white;
        font-size: 11px;
        font-weight: bold;
        padding: 8px 14px;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        margin-bottom: 0;
    }

    .two-col-box {
        display: table;
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 5px;
        overflow: hidden;
        margin-bottom: 16px;
    }
    .two-col-left {
        display: table-cell;
        width: 50%;
        padding: 14px 16px;
        vertical-align: top;
        border-right: 1px solid #e5e7eb;
    }
    .two-col-right {
        display: table-cell;
        width: 50%;
        padding: 14px 16px;
        vertical-align: top;
    }
    .col-title {
        font-size: 11px;
        font-weight: bold;
        color: #1F4E79;
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 6px;
        margin-bottom: 10px;
        text-transform: uppercase;
    }

    .field-row {
        display: table;
        width: 100%;
        padding: 5px 0;
        border-bottom: 1px solid #f3f4f6;
    }
    .field-row:last-child { border-bottom: none; }
    .field-label {
        display: table-cell;
        width: 50%;
        font-size: 11px;
        color: #6b7280;
    }
    .field-value {
        display: table-cell;
        width: 50%;
        font-size: 11px;
        font-weight: bold;
        color: #111;
    }

    .assistance-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 11px;
        margin-bottom: 16px;
    }
    .assistance-table th {
        background: #1F4E79;
        color: white;
        padding: 8px 14px;
        text-align: left;
        font-size: 10.5px;
        text-transform: uppercase;
    }
    .assistance-table td {
        padding: 9px 14px;
        border-bottom: 1px solid #e5e7eb;
        font-size: 11px;
    }
    .assistance-table tr:nth-child(even) td { background: #f9fafb; }
    .assistance-table .amount { font-weight: bold; color: #3FA46A; }

    .relationship-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 10px;
        font-size: 9.5px;
        font-weight: bold;
    }
    .rel-pere   { background: #dbeafe; color: #1e40af; }
    .rel-mere   { background: #fef3c7; color: #92400e; }
    .rel-enfant { background: #d1fae5; color: #065f46; }

    .signatures {
        display: table;
        width: 100%;
        margin-top: 50px;
    }
    .sig-cell {
        display: table-cell;
        text-align: center;
        font-size: 10.5px;
        font-weight: bold;
        color: #374151;
        width: 33.33%;
    }
    .sig-line {
        border-top: 1px solid #333;
        margin: 45px 18px 0 18px;
    }
</style>

{{-- Numéro et date --}}
<div class="meta-line">
    Abidjan, le {{ now()->format('d/m/Y') }} &nbsp;|&nbsp;
    Réf. N° {{ str_pad($funeral->id, 4, '0', STR_PAD_LEFT) }}
</div>

{{-- Bloc introductif --}}
<div class="intro-box">
    Nous, membres de l'<span class="highlight">Eglise Locale AEBECI {{ $church->church_name }}</span>,
    attestons avoir apporté notre soutien fraternel à
    <span class="highlight">{{ $funeral->believer->full_name }}</span>,
    fidèle de notre assemblée, à l'occasion du décès de
    @php
        $article = match($funeral->family_relationship) {
            'pere'   => 'son père',
            'mere'   => 'sa mère',
            'enfant' => 'son enfant biologique',
            default  => 'son proche',
        };
    @endphp
    <span class="highlight">{{ $article }},
    {{ strtoupper($funeral->parent_lastname) }} {{ $funeral->parent_firstname }}</span>,
    survenu le <span class="highlight">{{ $funeral->death_date?->format('d/m/Y') }}</span>.
    <br><br>
    Les funérailles ont eu lieu le
    <span class="highlight">{{ $funeral->funeral_date?->format('d/m/Y') }}</span>
    à <span class="highlight">{{ $funeral->funeral_place }}</span>.
    Le corps a été inhumé à <span class="highlight">{{ $funeral->burial_place }}</span>.
    @if($funeral->cause_of_death)
    Cause du décès : <span class="highlight">{{ $funeral->cause_of_death }}</span>.
    @endif
</div>

{{-- Infos fidèle + défunt --}}
<div class="two-col-box">
    <div class="two-col-left">
        <div class="col-title">Le fidèle</div>
        <div class="field-row">
            <div class="field-label">Nom & Prénom</div>
            <div class="field-value">{{ $funeral->believer->full_name }}</div>
        </div>
        <div class="field-row">
            <div class="field-label">Téléphone</div>
            <div class="field-value">{{ $funeral->believer->address?->phone ?? '—' }}</div>
        </div>
        <div class="field-row">
            <div class="field-label">Commune</div>
            <div class="field-value">{{ $funeral->believer->address?->commune ?? '—' }}</div>
        </div>
        <div class="field-row">
            <div class="field-label">Lien avec le défunt</div>
            <div class="field-value">
                @php
                    $badgeClass = match($funeral->family_relationship) {
                        'pere'   => 'rel-pere',
                        'mere'   => 'rel-mere',
                        'enfant' => 'rel-enfant',
                        default  => '',
                    };
                @endphp
                <span class="relationship-badge {{ $badgeClass }}">
                    {{ $funeral->family_relationship_label }}
                </span>
            </div>
        </div>
        @if($funeral->believer->churchInformation?->baptism_card_number)
        <div class="field-row">
            <div class="field-label">N° carte de membre</div>
            <div class="field-value">{{ $funeral->believer->churchInformation->baptism_card_number }}</div>
        </div>
        @endif
    </div>
    <div class="two-col-right">
        <div class="col-title">Le défunt</div>
        <div class="field-row">
            <div class="field-label">Nom & Prénom</div>
            <div class="field-value">{{ $funeral->deceased_full_name }}</div>
        </div>
        <div class="field-row">
            <div class="field-label">Date de décès</div>
            <div class="field-value">{{ $funeral->death_date?->format('d/m/Y') }}</div>
        </div>
        @if($funeral->cause_of_death)
        <div class="field-row">
            <div class="field-label">Cause du décès</div>
            <div class="field-value">{{ $funeral->cause_of_death }}</div>
        </div>
        @endif
        <div class="field-row">
            <div class="field-label">Date funérailles</div>
            <div class="field-value">{{ $funeral->funeral_date?->format('d/m/Y') }}</div>
        </div>
        <div class="field-row">
            <div class="field-label">Lieu funérailles</div>
            <div class="field-value">{{ $funeral->funeral_place }}</div>
        </div>
        <div class="field-row">
            <div class="field-label">Lieu d'inhumation</div>
            <div class="field-value">{{ $funeral->burial_place }}</div>
        </div>
    </div>
</div>

{{-- Tableau d'assistance --}}
<div class="section-title">Détail de l'assistance</div>
<table class="assistance-table">
    <thead>
        <tr>
            <th>Source</th>
            <th>Nombre de pagnes</th>
            <th>Montant (FCFA)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Assistance de l'église</td>
            <td>{{ $funeral->loincloths_number }}</td>
            <td class="amount">{{ number_format((float) $funeral->amount_paid, 0, ',', ' ') }} FCFA</td>
        </tr>
        @if($funeral->nbre_pagne || $funeral->cash_amount)
        <tr>
            <td>Assistance des fidèles</td>
            <td>{{ $funeral->nbre_pagne ?? '—' }}</td>
            <td class="amount">
                {{ $funeral->cash_amount ? number_format((float) $funeral->cash_amount, 0, ',', ' ') . ' FCFA' : '—' }}
            </td>
        </tr>
        @php
            $totalAmount = (float)($funeral->amount_paid ?? 0) + (float)($funeral->cash_amount ?? 0);
            $totalPagnes = (int)($funeral->loincloths_number ?? 0) + (int)($funeral->nbre_pagne ?? 0);
        @endphp
        <tr>
            <td><strong>Total</strong></td>
            <td><strong>{{ $totalPagnes }} pagne(s)</strong></td>
            <td class="amount"><strong>{{ number_format($totalAmount, 0, ',', ' ') }} FCFA</strong></td>
        </tr>
        @endif
    </tbody>
</table>

{{-- Signatures --}}
<div class="signatures">
    <div class="sig-cell">
        <div class="sig-line"></div>
        Signature du fidèle
    </div>
    <div class="sig-cell">
        <div class="sig-line"></div>
        Signature du secrétaire
    </div>
    <div class="sig-cell">
        <div class="sig-line"></div>
        Signature du pasteur
    </div>
</div>

@endsection