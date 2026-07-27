@php $church = \App\Models\Church::instance(); @endphp

@extends('layouts.pdf')

@section('doc-title', 'FICHE DU FIDÈLE')

@section('doc-body')
<style>
    /* Styles spécifiques à la fiche fidèle */
    .identity-bar {
        background: #1F4E79;
        color: white;
        padding: 9px 12px;
        border-radius: 4px;
        margin-bottom: 10px;
    }
    .identity-bar h2   { font-size: 13px; font-weight: bold; letter-spacing: 0.5px; }
    .identity-bar-row  { display: table; width: 100%; margin-top: 5px; }
    .identity-bar-left { display: table-cell; font-size: 8px; color: #bfdbfe; }
    .identity-bar-right{ display: table-cell; text-align: right; font-size: 8px; color: #bfdbfe; }

    .id-tag {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 7px;
        font-weight: bold;
        margin-right: 3px;
    }
    .id-tag-white  { background: rgba(255,255,255,.2); color: white; }
    .id-tag-jeunes { background: #d1fae5; color: #065f46; }
    .id-tag-adultes{ background: #fef3c7; color: #92400e; }
    .id-tag-ecodim { background: #dbeafe; color: #1e40af; }
    .id-tag-pre    { background: #ede9fe; color: #5b21b6; }
    .id-tag-nourr  { background: #fce7f3; color: #9d174d; }

    .badge-id {
        display: inline-block;
        background: #C9A635;
        color: #0F1E33;
        font-weight: bold;
        font-size: 9px;
        padding: 3px 9px;
        border-radius: 4px;
    }

    .sanction-alert {
        background: #fff5f5;
        border: 1px solid #fca5a5;
        border-radius: 4px;
        padding: 6px 10px;
        margin-bottom: 10px;
    }
    .sanction-alert-title { color: #dc2626; font-weight: bold; font-size: 8px; margin-bottom: 2px; }
    .sanction-alert-text  { color: #7f1d1d; font-size: 8px; }

    .baptise-yes { color: #16a34a; }
    .baptise-no  { color: #9ca3af; font-weight: normal; font-style: italic; }

    .tag-equipe  { background: #e0e7ff; color: #3730a3; }
    .tag-groupe  { background: #ede9fe; color: #6d28d9; }
    .tag-cellule { background: #d1fae5; color: #065f46; }

    .identity-bar-top {
        display: table;
        width: 100%;
    }
    .id-photo {
        display: table-cell;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(255,255,255,0.4);
        vertical-align: middle;
    }
    .id-photo-placeholder {
        display: table-cell;
        text-align: center;
        vertical-align: middle;
        background: rgba(255,255,255,0.15);
        color: #fff;
        font-weight: bold;
        font-size: 18px;
    }
    .identity-bar-main {
        display: table-cell;
        vertical-align: middle;
        padding-left: 14px;
    }
</style>

{{-- Numéro de fidèle --}}
<div style="text-align:right; margin-bottom:6px;">
    <span class="badge-id">Fidèle N° {{ str_pad($believer->id, 4, '0', STR_PAD_LEFT) }}</span>
</div>

{{-- Bandeau identité --}}
<div class="identity-bar">
    <div class="identity-bar-top">

        @if($believer->profile_picture && file_exists(storage_path('app/public/' . $believer->profile_picture)))
            <img src="{{ storage_path('app/public/' . $believer->profile_picture) }}" alt="{{ $believer->full_name }}" class="id-photo">
        @else
            <div class="id-photo id-photo-placeholder">...</div>
        @endif

        <div class="identity-bar-main">
            <h2>{{ strtoupper($believer->lastname) }} {{ $believer->firstname }}</h2>
            <div class="id-matricule">Matricule : {{ $believer->register_number }}</div>
            <div class="identity-bar-row">
                <div class="identity-bar-left">
                    @php
                        $tagClass = match($believer->age_group) {
                            'Jeunes'       => 'id-tag-jeunes',
                            'Adultes'      => 'id-tag-adultes',
                            'ECODIM'       => 'id-tag-ecodim',
                            'Pré-scolaire' => 'id-tag-pre',
                            default        => 'id-tag-nourr',
                        };
                    @endphp
                    <span class="id-tag {{ $tagClass }}">{{ $believer->age_group }}</span>
                    <span class="id-tag id-tag-white">{{ $believer->gender_label }}</span>
                    <span class="id-tag id-tag-white">{{ $believer->marital_status }}</span>
                    @if($believer->age)
                        <span class="id-tag id-tag-white">{{ $believer->age }} ans</span>
                    @endif
                </div>
                <div class="identity-bar-right">
                    Statut :
                    <strong style="color:{{ $believer->status === 'actif' ? '#86efac' : '#fca5a5' }}">
                        {{ ucfirst($believer->status) }}
                    </strong>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Alerte sanction active --}}
@if($believer->status === 'sanctionne')
@php $sanction = $believer->sanctions()->where('is_active', true)->latest()->first(); @endphp
@if($sanction)
<div class="sanction-alert">
    <div class="sanction-alert-title">⚠ Sanction disciplinaire active</div>
    <div class="sanction-alert-text">
        Depuis le {{ $sanction->start_date?->format('d/m/Y') }}
        @if($sanction->end_date) — Jusqu'au {{ $sanction->end_date->format('d/m/Y') }} @endif
        | Décidé par : {{ $sanction->decided_by ?? '—' }}<br>
        Motif : {{ $sanction->reason }}
    </div>
</div>
@endif
@endif

{{-- LIGNE 1 : Infos générales + Adresse --}}
<div class="row-table" style="margin-bottom:8px;">
    <div class="cell-half" style="padding-right:4px;">
        <div class="box">
            <div class="box-header">Informations générales</div>
            <div class="box-body">
                <div class="info-row"><div class="info-label">Nom</div><div class="info-value">{{ $believer->lastname }}</div></div>
                <div class="info-row"><div class="info-label">Prénom(s)</div><div class="info-value">{{ $believer->firstname }}</div></div>
                <div class="info-row"><div class="info-label">Date de naissance</div><div class="info-value">{{ $believer->birth_date?->format('d/m/Y') ?? '—' }}</div></div>
                <div class="info-row"><div class="info-label">Lieu de naissance</div><div class="info-value">{{ $believer->birth_place ?? '—' }}</div></div>
                <div class="info-row"><div class="info-label">Nationalité</div><div class="info-value">{{ $believer->nationality ?? '—' }}</div></div>
                <div class="info-row"><div class="info-label">N° CNI</div><div class="info-value">{{ $believer->cni_number ?? '—' }}</div></div>
                <div class="info-row"><div class="info-label">Situation maritale</div><div class="info-value">{{ $believer->marital_status ?? '—' }}</div></div>
                <div class="info-row"><div class="info-label">Nombre d'enfants</div><div class="info-value">{{ $believer->number_of_children }}</div></div>
            </div>
        </div>
    </div>
    <div class="cell-half" style="padding-left:4px;">
        <div class="box">
            <div class="box-header">Adresse & Contact</div>
            <div class="box-body">
                @if($believer->address)
                <div class="info-row"><div class="info-label">Commune</div><div class="info-value">{{ $believer->address->commune ?? '—' }}</div></div>
                <div class="info-row"><div class="info-label">Quartier</div><div class="info-value">{{ $believer->address->quartier ?? '—' }}</div></div>
                <div class="info-row"><div class="info-label">Sous-quartier</div><div class="info-value">{{ $believer->address->sous_quartier ?? '—' }}</div></div>
                <div class="info-row"><div class="info-label">Téléphone</div><div class="info-value">{{ $believer->address->phone ?? '—' }}</div></div>
                <div class="info-row"><div class="info-label">WhatsApp</div><div class="info-value">{{ $believer->address->whatsapp ?? '—' }}</div></div>
                <div class="info-row"><div class="info-label">Email</div><div class="info-value">{{ $believer->address->email ?? '—' }}</div></div>
                @else
                <p class="empty-val">Aucune adresse enregistrée.</p>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- LIGNE 2 : Vie spirituelle + Éducation & Profession --}}
<div class="row-table" style="margin-bottom:8px;">
    <div class="cell-half" style="padding-right:4px;">
        <div class="box">
            <div class="box-header">Vie spirituelle</div>
            <div class="box-body">
                @if($believer->churchInformation)
                @php $ci = $believer->churchInformation; @endphp
                <div class="info-row"><div class="info-label">Connaissance église</div><div class="info-value">{{ $ci->connaissance_eglise ?? '—' }}</div></div>
                <div class="info-row"><div class="info-label">Église d'origine</div><div class="info-value">{{ $ci->original_church ?? '—' }}</div></div>
                <div class="info-row"><div class="info-label">Année d'arrivée</div><div class="info-value">{{ $ci->arrival_year ?? '—' }}</div></div>
                <div class="info-row"><div class="info-label">Date de conversion</div><div class="info-value">{{ $ci->conversion_date?->format('d/m/Y') ?? '—' }}</div></div>
                <div class="info-row"><div class="info-label">Lieu de conversion</div><div class="info-value">{{ $ci->conversion_place ?? '—' }}</div></div>
                <div class="info-row">
                    <div class="info-label">Baptisé(e)</div>
                    <div class="info-value {{ $ci->baptised ? 'baptise-yes' : 'baptise-no' }}">
                        {{ $ci->baptised ? '✓ Oui' : 'Non' }}
                    </div>
                </div>
                @if($ci->baptised)
                <div class="info-row"><div class="info-label">Date de baptême</div><div class="info-value">{{ $ci->baptism_date?->format('d/m/Y') ?? '—' }}</div></div>
                <div class="info-row"><div class="info-label">Lieu de baptême</div><div class="info-value">{{ $ci->baptism_place ?? '—' }}</div></div>
                <div class="info-row"><div class="info-label">Pasteur officiant</div><div class="info-value">{{ $ci->baptism_pastor ?? '—' }}</div></div>
                <div class="info-row"><div class="info-label">N° carte baptême</div><div class="info-value">{{ $ci->baptism_card_number ?? '—' }}</div></div>
                @endif
                @else
                <p class="empty-val">Aucune information enregistrée.</p>
                @endif
            </div>
        </div>
    </div>
    <div class="cell-half" style="padding-left:4px;">
        <div class="box" style="margin-bottom:8px;">
            <div class="box-header">Éducation</div>
            <div class="box-body">
                @if($believer->education)
                <div class="info-row"><div class="info-label">Niveau d'étude</div><div class="info-value">{{ $believer->education->niveau_etude ?? '—' }}</div></div>
                <div class="info-row"><div class="info-label">Diplôme</div><div class="info-value">{{ $believer->education->diploma ?? '—' }}</div></div>
                <div class="info-row"><div class="info-label">Qualification</div><div class="info-value">{{ $believer->education->qualification ?? '—' }}</div></div>
                @else <p class="empty-val">Non renseigné.</p> @endif
            </div>
        </div>
        <div class="box">
            <div class="box-header">Profession</div>
            <div class="box-body">
                @if($believer->profession)
                <div class="info-row"><div class="info-label">Profession</div><div class="info-value">{{ $believer->profession->profession ?? '—' }}</div></div>
                <div class="info-row"><div class="info-label">Fonction</div><div class="info-value">{{ $believer->profession->function ?? '—' }}</div></div>
                <div class="info-row"><div class="info-label">Entreprise</div><div class="info-value">{{ $believer->profession->company ?? '—' }}</div></div>
                <div class="info-row"><div class="info-label">Contact pro.</div><div class="info-value">{{ $believer->profession->professional_contact ?? '—' }}</div></div>
                @else <p class="empty-val">Non renseigné.</p> @endif
            </div>
        </div>
    </div>
</div>

{{-- LIGNE 3 : Responsabilités + Appartenance --}}
<div class="row-table">
    <div class="cell-half" style="padding-right:4px;">
        <div class="box">
            <div class="box-header">Responsabilités</div>
            <div class="box-body">
                @if($believer->responsibility)
                <div class="info-row"><div class="info-label">Anciennes</div><div class="info-value">{{ $believer->responsibility->old ?? '—' }}</div></div>
                <div class="info-row"><div class="info-label">Actuelles</div><div class="info-value">{{ $believer->responsibility->current ?? '—' }}</div></div>
                <div class="info-row"><div class="info-label">Souhait de service</div><div class="info-value">{{ $believer->responsibility->desire ?? '—' }}</div></div>
                @else <p class="empty-val">Non renseigné.</p> @endif
            </div>
        </div>
    </div>
    <div class="cell-half" style="padding-left:4px;">
        <div class="box">
            <div class="box-header">Appartenance</div>
            <div class="box-body">
                <div class="info-row">
                    <div class="info-label">Équipes</div>
                    <div class="info-value">
                        @forelse($believer->teams as $team)
                            <span class="tag tag-equipe">{{ $team->name }}</span>
                        @empty <span class="empty-val">Aucune</span> @endforelse
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Groupes de louange</div>
                    <div class="info-value">
                        @forelse($believer->worshipGroups as $group)
                            <span class="tag tag-groupe">{{ $group->name }}</span>
                        @empty <span class="empty-val">Aucun</span> @endforelse
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Cellule</div>
                    <div class="info-value">
                        @forelse($believer->cells as $cell)
                            <span class="tag tag-cellule">{{ $cell->name }}</span>
                        @empty <span class="empty-val">Aucune</span> @endforelse
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Famille</div>
                    <div class="info-value">{{ $believer->family?->name ?? '—' }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection