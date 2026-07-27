<?php

namespace App\Http\Controllers;

use App\Models\Believer;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class BelieverExportController extends Controller
{
    // -------------------------------------------------------
    // Export Excel
    // -------------------------------------------------------
    public function exportExcel(Request $request)
    {
        $filename = 'liste-fideles-' . now()->format('Y-m-d') . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\BelieversExport($request->only(['gender', 'marital_status', 'age_group', 'team_id', 'status'])),
            $filename
        );
    }

    // -------------------------------------------------------
    // Export PDF
    // -------------------------------------------------------
    public function exportPdf(Request $request)
    {
        $believers = Believer::with(['address', 'teams', 'churchInformation'])
            ->when($request->gender,         fn($q) => $q->byGender($request->gender))
            ->when($request->marital_status, fn($q) => $q->byMaritalStatus($request->marital_status))
            ->when($request->age_group,      fn($q) => $q->byAgeGroup($request->age_group))
            ->when($request->team_id,        fn($q) => $q->byTeam((int) $request->team_id))
            ->when($request->status,         fn($q) => $q->where('status', $request->status))
            ->orderBy('lastname')
            ->get();

        $pdf = Pdf::loadView('believers.exports.pdf', [
            'believers' => $believers,
            'filters'   => $request->only(['gender', 'marital_status', 'age_group', 'status']),
            'date'      => now()->format('d/m/Y'),
            'total'     => $believers->count(),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('liste-fideles-' . now()->format('Y-m-d') . '.pdf');
    }

    // -------------------------------------------------------
    // Import Excel
    // -------------------------------------------------------
    public function importForm()
    {
        return view('believers.imports.form');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
        ], [
            'file.required' => 'Veuillez sélectionner un fichier.',
            'file.mimes'    => 'Le fichier doit être au format Excel (.xlsx ou .xls).',
            'file.max'      => 'Le fichier ne doit pas dépasser 10 Mo.',
        ]);

        $import = new \App\Imports\BelieversImport();

        \Maatwebsite\Excel\Facades\Excel::import($import, $request->file('file'));

        return redirect()
            ->route('believers.index')
            ->with('import_result', [
                'imported' => $import->imported,
                'skipped'  => $import->skipped,
                'errors'   => $import->errors,
            ]);
    }
}