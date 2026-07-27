<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            size: 85.6mm 54mm;
            margin: 0;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
        }

        .card {
            width: 85.6mm;
            height: 54mm;
            position: relative;
            overflow: hidden;
            page-break-after: always;
        }
        .card:last-child {
            page-break-after: avoid;
        }

        /* ============ RECTO ============ */
        .card-front {
            background: #FFFFFF;
        }

        .front-header {
            background: #3A9BDC;
            height: 12mm;
            display: table;
            width: 100%;
        }
        .front-header-logo {
            display: table-cell;
            width: 10mm;
            vertical-align: middle;
            text-align: center;
        }
        .front-header-logo img {
            width: 8mm;
            height: 8mm;
            object-fit: contain;
        }
        .front-header-logo .placeholder {
            width: 8mm;
            height: 8mm;
            border-radius: 50%;
            background: #FFFFFF;
            color: #3A9BDC;
            font-size: 5px;
            font-weight: bold;
            text-align: center;
            line-height: 8mm;
            margin: 0 auto;
        }
        .front-header-text {
            display: table-cell;
            vertical-align: middle;
            color: #FFFFFF;
            padding-left: 1mm;
        }
        .front-header-text .org-title {
            font-size: 7px;
            font-weight: bold;
            letter-spacing: 0.4px;
        }
        .front-header-text .org-sub {
            font-size: 4.8px;
            line-height: 1.3;
        }
        .front-header-text .org-contact {
            font-size: 4.3px;
            line-height: 1.3;
            opacity: 0.9;
        }

        .front-body {
            display: table;
            width: 100%;
            padding: 1.8mm 2.5mm 0 2.5mm;
        }
        .front-fields-cell {
            display: table-cell;
            width: 56mm;
            vertical-align: top;
        }
        .field-row {
            display: table;
            width: 100%;
            font-size: 5.2px;
            line-height: 1.5;
        }
        .field-label {
            display: table-cell;
            width: 19mm;
            color: #374151;
            font-weight: bold;
        }
        .field-colon {
            display: table-cell;
            width: 1.5mm;
            color: #374151;
        }
        .field-value {
            display: table-cell;
            color: #0F1E33;
            font-weight: bold;
        }

        .front-photo-cell {
            display: table-cell;
            width: 20mm;
            vertical-align: top;
            text-align: center;
        }
        .front-photo {
            width: 15mm;
            height: 17mm;
            object-fit: cover;
            border: 0.5mm solid #C9A635;
            border-radius: 1mm;
        }
        .front-photo-placeholder {
            width: 15mm;
            height: 17mm;
            background: #f0f5ff;
            border: 0.5mm solid #C9A635;
            border-radius: 1mm;
            color: #3A9BDC;
            font-size: 11px;
            font-weight: bold;
            text-align: center;
            line-height: 17mm;
            margin: 0 auto;
        }
        .front-seal {
            margin-top: 1.5mm;
            width: 9mm;
            height: 9mm;
            border-radius: 50%;
            border: 0.4mm dashed #3FA46A;
            color: #3FA46A;
            font-size: 3.2px;
            font-weight: bold;
            text-align: center;
            line-height: 1.2;
            display: table;
            margin-left: auto;
            margin-right: auto;
        }
        .front-seal span {
            display: table-cell;
            vertical-align: middle;
        }

        .front-banner {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 6mm;
            background: #C9A635;
            color: #FFFFFF;
            font-size: 7px;
            font-weight: bold;
            letter-spacing: 1px;
            text-align: center;
            line-height: 6mm;
            text-transform: uppercase;
        }

        /* ============ VERSO ============ */
        .card-back {
            background: #FFFFFF;
        }
        .back-header {
            background: #0F1E33;
            height: 8mm;
            color: #FFFFFF;
            text-align: center;
            line-height: 8mm;
            font-size: 6.5px;
            font-weight: bold;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }
        .back-body {
            padding: 2.5mm 4mm;
        }
        .back-assoc-title {
            font-size: 6.5px;
            font-weight: bold;
            color: #3A9BDC;
            text-align: center;
            line-height: 1.4;
        }
        .back-district {
            font-size: 5.5px;
            font-weight: bold;
            color: #0F1E33;
            text-align: center;
            margin-top: 0.8mm;
        }
        .back-divider {
            border-top: 0.4mm solid #C9A635;
            margin: 1.8mm 0;
        }
        .back-contact {
            font-size: 5px;
            color: #374151;
            line-height: 1.7;
            text-align: center;
        }
        .back-legal {
            font-size: 4.6px;
            color: #6b7280;
            text-align: center;
            margin-top: 1.5mm;
        }
        .back-verse {
            font-size: 5px;
            font-style: italic;
            color: #3FA46A;
            text-align: center;
            margin-top: 2.5mm;
            line-height: 1.5;
            padding: 0 2mm;
        }
        .back-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2mm;
            display: table;
        }
        .back-footer div {
            display: table-cell;
            width: 33.33%;
        }
        .footer-bleu  { background: #3A9BDC; }
        .footer-dore  { background: #C9A635; }
        .footer-vert  { background: #3FA46A; }
    </style>
</head>
<body>

    {{-- ============ RECTO ============ --}}
    <div class="card card-front">
        <div class="front-header">
            <div class="front-header-logo">
                @if($church->photo_path && file_exists(public_path('storage/' . $church->photo_path)))
                    <img src="{{ public_path('storage/' . $church->photo_path) }}" alt="Logo">
                @else
                    <div class="placeholder">AEBECI</div>
                @endif
            </div>
            <div class="front-header-text">
                <div class="org-title">AEBECI</div>
                <div class="org-sub">{{ $church->organisation_name }}</div>
                <div class="org-contact">{{ $church->contact_line }}</div>
            </div>
        </div>

        <div class="front-body">
            <div class="front-fields-cell">
                <div class="field-row">
                    <div class="field-label">Prénoms</div>
                    <div class="field-colon">:</div>
                    <div class="field-value">{{ strtoupper($believer->firstname) }}</div>
                </div>
                <div class="field-row">
                    <div class="field-label">Nom</div>
                    <div class="field-colon">:</div>
                    <div class="field-value">{{ strtoupper($believer->lastname) }}</div>
                </div>
                <div class="field-row">
                    <div class="field-label">Né(e) le, à</div>
                    <div class="field-colon">:</div>
                    <div class="field-value">
                        {{ $believer->birth_date?->format('d/m/Y') ?? '—' }}
                        @if($believer->birth_place) à {{ $believer->birth_place }} @endif
                    </div>
                </div>
                <div class="field-row">
                    <div class="field-label">Nationalité</div>
                    <div class="field-colon">:</div>
                    <div class="field-value">{{ $believer->nationality ?? '—' }}</div>
                </div>
                <div class="field-row">
                    <div class="field-label">Baptisé(e) le</div>
                    <div class="field-colon">:</div>
                    <div class="field-value">
                        @if($believer->churchInformation?->baptised)
                            {{ $believer->churchInformation?->baptism_date?->format('d/m/Y') ?? '—' }}
                            @if($believer->churchInformation?->baptism_place) à {{ $believer->churchInformation->baptism_place }} @endif
                        @else
                            Non baptisé(e)
                        @endif
                    </div>
                </div>
                <div class="field-row">
                    <div class="field-label">Profession</div>
                    <div class="field-colon">:</div>
                    <div class="field-value">{{ $believer->profession?->profession ?? '—' }}</div>
                </div>
                <div class="field-row">
                    <div class="field-label">District/Eglise</div>
                    <div class="field-colon">:</div>
                    <div class="field-value">{{ $church->district }}/{{ $church->church_name }}</div>
                </div>
                <div class="field-row">
                    <div class="field-label">Matricule</div>
                    <div class="field-colon">:</div>
                    <div class="field-value">{{ $believer->register_number }}</div>
                </div>
                <div class="field-row">
                    <div class="field-label">N°CNI/ATT</div>
                    <div class="field-colon">:</div>
                    <div class="field-value">{{ $believer->cni_number ?? '—' }}</div>
                </div>
            </div>

            <div class="front-photo-cell">
                @if($believer->profile_picture && file_exists(public_path('storage/' . $believer->profile_picture)))
                    <img src="{{ public_path('storage/' . $believer->profile_picture) }}" alt="Photo" class="front-photo">
                @else
                    <div class="front-photo-placeholder">
                        {{ strtoupper(substr($believer->firstname, 0, 1) . substr($believer->lastname, 0, 1)) }}
                    </div>
                @endif
                <div class="front-seal"><span>AEBECI<br>{{ $church->church_name }}</span></div>
            </div>
        </div>

        <div class="front-banner">Carte de membre</div>
    </div>

    {{-- ============ VERSO ============ --}}
    <div class="card card-back">
        <div class="back-header">République de Côte d'Ivoire</div>

        <div class="back-body">
            <div class="back-assoc-title">
                A.E.B.E.C.I<br>
                Association des Eglises Baptistes Evangéliques de Côte d'Ivoire
            </div>
            <div class="back-district">
                Eglise Locale {{ $church->district }} — {{ $church->church_name }}
            </div>

            <div class="back-divider"></div>

            <div class="back-contact">
                {{ $church->contact_line }}
                @if($church->localisation)
                    <br>{{ $church->localisation }}
                @endif
            </div>

            <div class="back-legal">
                Autorisation N° : {{ $church->authorization }}
            </div>

            <div class="back-verse">
                « Allez, faites de toutes les nations des disciples… »<br>
                Matthieu 28:19
            </div>
        </div>

        <div class="back-footer">
            <div class="footer-bleu"></div>
            <div class="footer-dore"></div>
            <div class="footer-vert"></div>
        </div>
    </div>

</body>
</html>