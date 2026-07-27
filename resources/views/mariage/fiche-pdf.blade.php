@php $church = \App\Models\Church::instance(); @endphp

@extends('layouts.pdf')

@section('doc-title', 'MARIAGE DE')

@section('doc-body')
<style>
    .city-date {
        font-size: 10px;
        color: #555;
        margin-bottom: 10px;
    }

    /* Bloc couple avec photos */
    .couple-block {
        display: table;
        width: 100%;
        margin-bottom: 14px;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        overflow: hidden;
    }
    .couple-groom {
        display: table-cell;
        width: 47%;
        vertical-align: top;
        padding: 10px 12px;
        border-right: 2px solid #C9A635;
    }
    .couple-sep {
        display: table-cell;
        width: 6%;
        vertical-align: middle;
        text-align: center;
        font-size: 18px;
        font-weight: bold;
        color: #C9A635;
    }
    .couple-bride {
        display: table-cell;
        width: 47%;
        vertical-align: top;
        padding: 10px 12px;
    }

    .person-header {
        display: table;
        width: 100%;
        margin-bottom: 8px;
    }
    .person-photo {
        display: table-cell;
        width: 55px;
        vertical-align: top;
    }
    .person-photo img {
        width: 50px;
        height: 55px;
        object-fit: cover;
        border: 1px solid #d1d5db;
        border-radius: 3px;
    }
    .person-photo .photo-placeholder {
        width: 50px;
        height: 55px;
        border: 1px dashed #d1d5db;
        border-radius: 3px;
        text-align: center;
        line-height: 55px;
        font-size: 7px;
        color: #9ca3af;
    }
    .person-info { display: table-cell; vertical-align: top; padding-left: 6px; }
    .person-role {
        font-size: 8px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 2px;
    }
    .role-groom { color: #1e40af; }
    .role-bride { color: #92400e; }
    .person-name { font-size: 11px; font-weight: bold; color: #111; line-height: 1.3; }

    .p-row { display: table; width: 100%; padding: 2.5px 0; border-bottom: 1px solid #f3f4f6; }
    .p-row:last-child { border-bottom: none; }
    .p-label { display: table-cell; width: 45%; font-size: 8px; color: #6b7280; }
    .p-value { display: table-cell; font-size: 8px; font-weight: bold; color: #111; }

    .sig-label {
        font-size: 8px;
        font-weight: bold;
        color: #374151;
        margin-top: 20px;
        text-align: center;
    }
    .sig-line-person {
        border-top: 1px solid #333;
        margin: 18px 10px 0 10px;
    }

    /* Cérémonies */
    .ceremony-block {
        border: 1px solid #d1d5db;
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 10px;
    }
    .ceremony-title {
        background: #1F4E79;
        color: white;
        font-size: 9px;
        font-weight: bold;
        padding: 5px 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .ceremony-body {
        padding: 8px 12px;
    }
    .c-row { display: table; width: 100%; padding: 3px 0; border-bottom: 1px solid #f9fafb; }
    .c-row:last-child { border-bottom: none; }
    .c-label { display: table-cell; width: 40%; font-size: 8.5px; color: #6b7280; }
    .c-value { display: table-cell; font-size: 8.5px; font-weight: bold; color: #111; }

    /* Témoins */
    .witnesses-block {
        display: table;
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 10px;
    }
    .witness-groom {
        display: table-cell;
        width: 50%;
        padding: 8px 12px;
        border-right: 1px solid #e5e7eb;
        vertical-align: top;
    }
    .witness-bride {
        display: table-cell;
        width: 50%;
        padding: 8px 12px;
        vertical-align: top;
    }
    .witness-title {
        font-size: 8px;
        font-weight: bold;
        text-transform: uppercase;
        margin-bottom: 5px;
    }
    .witness-title-groom { color: #1e40af; }
    .witness-title-bride { color: #92400e; }

    /* Signatures finales */
    .signatures-final {
        display: table;
        width: 100%;
        margin-top: 20px;
    }
    .sig-final-cell {
        display: table-cell;
        text-align: center;
        font-size: 8.5px;
        font-weight: bold;
        color: #374151;
        width: 33.33%;
    }
    .sig-final-line {
        border-top: 1px solid #333;
        margin: 25px 10px 0 10px;
    }
</style>

{{-- Ville et date --}}
<div class="city-date">
    A : {{ $church->church_name }} &nbsp;&nbsp;&nbsp; Le : {{ $mariage->religious_marriage_date?->format('d/m/Y') }}
</div>

{{-- Époux & Épouse --}}
<div class="couple-block">

    {{-- ÉPOUX --}}
    <div class="couple-groom">
        <div class="person-header">
            <div class="person-photo">
                @if($mariage->groom_photo && file_exists(public_path('storage/' . $mariage->groom_photo)))
                    <img src="{{ public_path('storage/' . $mariage->groom_photo) }}" alt="Époux">
                @else
                    <div class="photo-placeholder">PHOTO</div>
                @endif
            </div>
            <div class="person-info">
                <div class="person-role role-groom">Époux</div>
                <div class="person-name">{{ strtoupper($mariage->groom_display_name) }}</div>
            </div>
        </div>
        <div class="p-row">
            <div class="p-label">Né le</div>
            <div class="p-value">{{ $mariage->groom_birthdate_display ?? '—' }}</div>
        </div>
        <div class="p-row">
            <div class="p-label">à</div>
            <div class="p-value">{{ $mariage->groom_birth_place_display ?? '—' }}</div>
        </div>
        <div class="p-row">
            <div class="p-label">Baptisé le</div>
            <div class="p-value">{{ $mariage->groom_bapistism_date?->format('d/m/Y') ?? '—' }}</div>
        </div>
        <div class="p-row">
            <div class="p-label">à</div>
            <div class="p-value">{{ $mariage->groom_bapistism_place ?? '—' }}</div>
        </div>
        <div class="p-row">
            <div class="p-label">Par</div>
            <div class="p-value">{{ $mariage->baptism_officer_groom ?? '—' }}</div>
        </div>
        <div class="p-row">
            <div class="p-label">Profession</div>
            <div class="p-value">{{ $mariage->groom_profession ?? '—' }}</div>
        </div>
        @if($mariage->groom?->churchInformation?->baptism_card_number)
        <div class="p-row">
            <div class="p-label">N° carte membre</div>
            <div class="p-value">{{ $mariage->groom->churchInformation->baptism_card_number }}</div>
        </div>
        @endif
        <div class="sig-label">Signature :</div>
        <div class="sig-line-person"></div>
    </div>

    <div class="couple-sep">&amp;</div>

    {{-- ÉPOUSE --}}
    <div class="couple-bride">
        <div class="person-header">
            <div class="person-photo">
                @if($mariage->bride_photo && file_exists(public_path('storage/' . $mariage->bride_photo)))
                    <img src="{{ public_path('storage/' . $mariage->bride_photo) }}" alt="Épouse">
                @else
                    <div class="photo-placeholder">PHOTO</div>
                @endif
            </div>
            <div class="person-info">
                <div class="person-role role-bride">Épouse</div>
                <div class="person-name">{{ strtoupper($mariage->bride_display_name) }}</div>
            </div>
        </div>
        <div class="p-row">
            <div class="p-label">Née le</div>
            <div class="p-value">{{ $mariage->bride_birthdate_display ?? '—' }}</div>
        </div>
        <div class="p-row">
            <div class="p-label">à</div>
            <div class="p-value">{{ $mariage->bride_birth_place_display ?? '—' }}</div>
        </div>
        <div class="p-row">
            <div class="p-label">Baptisée le</div>
            <div class="p-value">{{ $mariage->bride_bapistism_date?->format('d/m/Y') ?? '—' }}</div>
        </div>
        <div class="p-row">
            <div class="p-label">à</div>
            <div class="p-value">{{ $mariage->bride_bapistism_place ?? '—' }}</div>
        </div>
        <div class="p-row">
            <div class="p-label">Par</div>
            <div class="p-value">{{ $mariage->baptism_officer_bride ?? '—' }}</div>
        </div>
        <div class="p-row">
            <div class="p-label">Profession</div>
            <div class="p-value">{{ $mariage->bride_profession ?? '—' }}</div>
        </div>
        @if($mariage->bride?->churchInformation?->baptism_card_number)
        <div class="p-row">
            <div class="p-label">N° carte membre</div>
            <div class="p-value">{{ $mariage->bride->churchInformation->baptism_card_number }}</div>
        </div>
        @endif
        <div class="sig-label">Signature :</div>
        <div class="sig-line-person"></div>
    </div>

</div>

{{-- Cérémonie civile --}}
<div class="ceremony-block">
    <div class="ceremony-title">Cérémonie civile</div>
    <div class="ceremony-body">
        <div class="c-row">
            <div class="c-label">Date</div>
            <div class="c-value">{{ $mariage->civil_marriage_date?->format('d/m/Y') }}</div>
        </div>
        <div class="c-row">
            <div class="c-label">Lieu</div>
            <div class="c-value">{{ $mariage->civil_marriage_place }}</div>
        </div>
    </div>
</div>

{{-- Témoins --}}
<div class="witnesses-block">
    <div class="witness-groom">
        <div class="witness-title witness-title-groom">Témoin Époux</div>
        <div class="c-row"><div class="c-label">Nom</div><div class="c-value">{{ $mariage->groom_witness }}</div></div>
        <div class="c-row"><div class="c-label">Profession</div><div class="c-value">{{ $mariage->groom_witness_profession ?? '—' }}</div></div>
        <div class="sig-label" style="text-align:left; margin-top:15px;">Signature :</div>
        <div style="border-top:1px solid #333; margin-top:18px;"></div>
    </div>
    <div class="witness-bride">
        <div class="witness-title witness-title-bride">Témoin Épouse</div>
        <div class="c-row"><div class="c-label">Nom</div><div class="c-value">{{ $mariage->bride_witness }}</div></div>
        <div class="c-row"><div class="c-label">Profession</div><div class="c-value">{{ $mariage->bride_witness_profession ?? '—' }}</div></div>
        <div class="sig-label" style="text-align:left; margin-top:15px;">Signature :</div>
        <div style="border-top:1px solid #333; margin-top:18px;"></div>
    </div>
</div>

{{-- Cérémonie religieuse --}}
<div class="ceremony-block">
    <div class="ceremony-title">Cérémonie religieuse</div>
    <div class="ceremony-body">
        <div class="c-row">
            <div class="c-label">Date</div>
            <div class="c-value">{{ $mariage->religious_marriage_date?->format('d/m/Y') }}</div>
        </div>
        <div class="c-row">
            <div class="c-label">Lieu</div>
            <div class="c-value">{{ $mariage->religious_marriage_place }}</div>
        </div>
        @if($mariage->wedding_mc)
        <div class="c-row">
            <div class="c-label">Dirigeant</div>
            <div class="c-value">{{ $mariage->wedding_mc }}</div>
        </div>
        @endif
        <div class="c-row">
            <div class="c-label">Prédicateur</div>
            <div class="c-value">{{ $mariage->wedding_preacher }}</div>
        </div>
        @if($mariage->hand_bible)
        <div class="c-row">
            <div class="c-label">La Bible remise par</div>
            <div class="c-value">{{ $mariage->hand_bible }}</div>
        </div>
        @endif
    </div>
</div>

{{-- Pasteur officiant --}}
<div style="font-size:9px; margin-bottom:16px;">
    <strong>PASTEUR OFFICIANT :</strong> {{ $mariage->officiant }}
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <strong>Signature :</strong>
    <span style="display:inline-block; width:120px; border-bottom:1px solid #333; margin-bottom:-3px;"></span>
</div>

{{-- Signatures finales --}}
<div class="signatures-final">
    <div class="sig-final-cell">
        <div class="sig-final-line"></div>
        Signature de l'époux
    </div>
    <div class="sig-final-cell">
        <div class="sig-final-line"></div>
        Signature du Pasteur officiant
    </div>
    <div class="sig-final-cell">
        <div class="sig-final-line"></div>
        Signature de l'épouse
    </div>
</div>

@endsection