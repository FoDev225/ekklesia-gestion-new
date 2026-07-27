<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 8px; color: #333; }

        .header {
            background: #0F1E33;
            color: white;
            padding: 10px 15px;
            margin-bottom: 8px;
        }
        .header h1 { font-size: 14px; font-weight: bold; }
        .header p  { font-size: 9px; color: #C9A635; margin-top: 3px; }

        .meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 8px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7.5px;
        }
        thead tr {
            background: #1F4E79;
            color: white;
        }
        thead th {
            padding: 5px 4px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #ddd;
            white-space: nowrap;
        }
        tbody tr:nth-child(even) { background: #EBF3FB; }
        tbody tr:nth-child(odd)  { background: #FFFFFF; }
        tbody td {
            padding: 4px;
            border: 1px solid #e0e0e0;
            vertical-align: top;
        }

        .badge {
            display: inline-block;
            padding: 1px 5px;
            border-radius: 8px;
            font-size: 7px;
            font-weight: bold;
        }
        .badge-jeunes   { background: #d1fae5; color: #065f46; }
        .badge-adultes  { background: #fef3c7; color: #92400e; }
        .badge-ecodim   { background: #dbeafe; color: #1e40af; }
        .badge-nourr    { background: #fce7f3; color: #9d174d; }

        .baptise-yes { color: #16a34a; font-weight: bold; }
        .baptise-no  { color: #9ca3af; }

        .footer {
            margin-top: 10px;
            text-align: center;
            font-size: 7px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
        .total-badge {
            background: #3A9BDC;
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 9px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>LISTE DES FIDÈLES</h1>
        <p>{{ config('app.name') }} — Édité le {{ $date }}</p>
    </div>

    <div class="meta">
        <span>
            <span class="total-badge">{{ $total }} fidèle(s)</span>
            @if(!empty(array_filter($filters)))
                &nbsp;| Filtres appliqués :
                @if($filters['gender'] ?? null) Genre : {{ $filters['gender'] === 'M' ? 'Homme' : 'Femme' }} @endif
                @if($filters['status'] ?? null) Statut : {{ $filters['status'] }} @endif
                @if($filters['age_group'] ?? null) Tranche : {{ $filters['age_group'] }} @endif
            @endif
        </span>
        <span>Page <span class="pagenum"></span></span>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:3%">#</th>
                <th style="width:12%">NOM</th>
                <th style="width:13%">PRÉNOMS</th>
                <th style="width:5%">SEXE</th>
                <th style="width:8%">NAISSANCE</th>
                <th style="width:7%">ÂGE / CAT.</th>
                <th style="width:9%">SIT. MAT.</th>
                <th style="width:10%">CONTACT</th>
                <th style="width:9%">COMMUNE</th>
                <th style="width:6%">BAPTISÉ</th>
                <th style="width:10%">PROFESSION</th>
                <th style="width:8%">ÉQUIPE(S)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($believers as $i => $believer)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td><strong>{{ $believer->lastname }}</strong></td>
                <td>{{ $believer->firstname }}</td>
                <td>{{ $believer->gender }}</td>
                <td>{{ $believer->birth_date?->format('d/m/Y') ?? '—' }}</td>
                <td>
                    {{ $believer->age ? $believer->age . ' ans' : '—' }}<br>
                    @php
                        $colorClass = match($believer->age_group) {
                            'Jeunes'  => 'badge-jeunes',
                            'Adultes' => 'badge-adultes',
                            'ECODIM'  => 'badge-ecodim',
                            default   => 'badge-nourr',
                        };
                    @endphp
                    <span class="badge {{ $colorClass }}">{{ $believer->age_group }}</span>
                </td>
                <td>{{ $believer->marital_status }}</td>
                <td>
                    {{ $believer->address?->phone ?? '' }}
                    @if($believer->address?->whatsapp && $believer->address?->whatsapp !== $believer->address?->phone)
                        <br>{{ $believer->address->whatsapp }}
                    @endif
                </td>
                <td>{{ $believer->address?->commune ?? '—' }}</td>
                <td>
                    @if($believer->churchInformation?->baptised)
                        <span class="baptise-yes">✓ Oui</span>
                    @else
                        <span class="baptise-no">Non</span>
                    @endif
                </td>
                <td>{{ $believer->profession?->profession ?? '—' }}</td>
                <td>{{ $believer->teams->pluck('name')->join(', ') ?: '—' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="12" style="text-align:center; padding:20px; color:#999;">
                    Aucun fidèle trouvé.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Document généré automatiquement par {{ config('app.name') }} le {{ $date }} — Confidentiel
    </div>

</body>
</html>