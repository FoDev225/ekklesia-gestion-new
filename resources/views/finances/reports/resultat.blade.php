@extends('layouts.dashboard')

@section('title', 'Compte de résultat')
@section('page-title', 'Compte de résultat')

@section('content')
<div class="space-y-4">

    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('finances.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Finances</a>
            <span class="text-gray-300">/</span>
            <span class="text-sm text-gray-700 font-medium">Compte de résultat</span>
        </div>
        <form method="GET" class="flex gap-2">
            <select name="annee" onchange="this.form.submit()"
                class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                @foreach($availableYears as $y)
                    <option value="{{ $y }}" @selected($annee == $y)>{{ $y }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Libellé</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-500 uppercase">Report</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-500 uppercase">S1 {{ $annee }}</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-500 uppercase">S2 {{ $annee }}</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-500 uppercase">Année {{ $annee }}</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr class="bg-gray-100"><td colspan="5" class="px-4 py-2 font-bold text-gray-700">RECETTES</td></tr>
                <tr>
                    <td class="px-4 py-2 text-gray-700">Dîmes et Offrandes</td>
                    <td class="px-4 py-2 text-right text-gray-400">—</td>
                    <td class="px-4 py-2 text-right">{{ number_format($dataS1['recettes_dimes_offrandes'], 0, ',', ' ') }}</td>
                    <td class="px-4 py-2 text-right">{{ number_format($dataS2['recettes_dimes_offrandes'], 0, ',', ' ') }}</td>
                    <td class="px-4 py-2 text-right font-semibold">{{ number_format($dataS1['recettes_dimes_offrandes'] + $dataS2['recettes_dimes_offrandes'], 0, ',', ' ') }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 text-gray-700">Autres recettes</td>
                    <td class="px-4 py-2 text-right text-gray-400">—</td>
                    <td class="px-4 py-2 text-right">{{ number_format($dataS1['recettes_autres'], 0, ',', ' ') }}</td>
                    <td class="px-4 py-2 text-right">{{ number_format($dataS2['recettes_autres'], 0, ',', ' ') }}</td>
                    <td class="px-4 py-2 text-right font-semibold">{{ number_format($dataS1['recettes_autres'] + $dataS2['recettes_autres'], 0, ',', ' ') }}</td>
                </tr>
                <tr class="font-bold bg-green-50">
                    <td class="px-4 py-2 text-green-800">TOTAL RECETTES</td>
                    <td class="px-4 py-2 text-right text-gray-400">—</td>
                    <td class="px-4 py-2 text-right text-green-800">{{ number_format($dataS1['total_recettes'], 0, ',', ' ') }}</td>
                    <td class="px-4 py-2 text-right text-green-800">{{ number_format($dataS2['total_recettes'], 0, ',', ' ') }}</td>
                    <td class="px-4 py-2 text-right text-green-800">{{ number_format($anneeData['total_recettes'], 0, ',', ' ') }}</td>
                </tr>

                <tr class="bg-gray-100"><td colspan="5" class="px-4 py-2 font-bold text-gray-700">DÉCAISSEMENTS</td></tr>
                <tr>
                    <td class="px-4 py-2 text-gray-700">Charges externes (AEBECI/Vision)</td>
                    <td class="px-4 py-2 text-right text-gray-400">—</td>
                    <td class="px-4 py-2 text-right text-red-600">−{{ number_format($dataS1['depenses_externes'], 0, ',', ' ') }}</td>
                    <td class="px-4 py-2 text-right text-red-600">−{{ number_format($dataS2['depenses_externes'], 0, ',', ' ') }}</td>
                    <td class="px-4 py-2 text-right font-semibold text-red-600">−{{ number_format($dataS1['depenses_externes'] + $dataS2['depenses_externes'], 0, ',', ' ') }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 text-gray-700">Charges internes</td>
                    <td class="px-4 py-2 text-right text-gray-400">—</td>
                    <td class="px-4 py-2 text-right text-red-600">−{{ number_format($dataS1['depenses_internes'], 0, ',', ' ') }}</td>
                    <td class="px-4 py-2 text-right text-red-600">−{{ number_format($dataS2['depenses_internes'], 0, ',', ' ') }}</td>
                    <td class="px-4 py-2 text-right font-semibold text-red-600">−{{ number_format($dataS1['depenses_internes'] + $dataS2['depenses_internes'], 0, ',', ' ') }}</td>
                </tr>
                <tr class="font-bold bg-red-50">
                    <td class="px-4 py-2 text-red-800">TOTAL DÉPENSES</td>
                    <td class="px-4 py-2 text-right text-gray-400">—</td>
                    <td class="px-4 py-2 text-right text-red-800">−{{ number_format($dataS1['total_depenses'], 0, ',', ' ') }}</td>
                    <td class="px-4 py-2 text-right text-red-800">−{{ number_format($dataS2['total_depenses'], 0, ',', ' ') }}</td>
                    <td class="px-4 py-2 text-right text-red-800">−{{ number_format($anneeData['total_depenses'], 0, ',', ' ') }}</td>
                </tr>

                <tr class="bg-gray-100"><td colspan="5" class="px-4 py-2 font-bold text-gray-700">TRÉSORERIE</td></tr>
                <tr>
                    <td class="px-4 py-2 text-gray-700">Marge (Recettes − Dépenses)</td>
                    <td class="px-4 py-2 text-right text-gray-400">—</td>
                    <td class="px-4 py-2 text-right">{{ number_format($dataS1['marge'], 0, ',', ' ') }}</td>
                    <td class="px-4 py-2 text-right">{{ number_format($dataS2['marge'], 0, ',', ' ') }}</td>
                    <td class="px-4 py-2 text-right font-semibold">{{ number_format($anneeData['marge'], 0, ',', ' ') }}</td>
                </tr>
                <tr class="font-bold bg-blue-50">
                    <td class="px-4 py-2 text-blue-800">TRÉSORERIE NETTE (cumulée)</td>
                    <td class="px-4 py-2 text-right text-blue-800">{{ number_format($report, 0, ',', ' ') }}</td>
                    <td class="px-4 py-2 text-right text-blue-800">{{ number_format($report + $dataS1['marge'], 0, ',', ' ') }}</td>
                    <td class="px-4 py-2 text-right text-blue-800">{{ number_format($report + $dataS1['marge'] + $dataS2['marge'], 0, ',', ' ') }}</td>
                    <td class="px-4 py-2 text-right text-blue-800">{{ number_format($report + $anneeData['marge'], 0, ',', ' ') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
@endsection