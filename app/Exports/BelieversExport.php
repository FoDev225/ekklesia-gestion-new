<?php

namespace App\Exports;

use App\Models\Believer;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class BelieversExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithTitle
{
    private array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function title(): string
    {
        return 'Liste des fidèles';
    }

    public function query()
    {
        return Believer::with(['address', 'churchInformation', 'education', 'profession', 'responsibility', 'teams'])
            ->when($this->filters['gender'] ?? null,         fn($q, $v) => $q->where('gender', $v))
            ->when($this->filters['marital_status'] ?? null, fn($q, $v) => $q->where('marital_status', $v))
            ->when($this->filters['age_group'] ?? null,      fn($q, $v) => $q->byAgeGroup($v))
            ->when($this->filters['team_id'] ?? null,        fn($q, $v) => $q->byTeam($v))
            ->when($this->filters['status'] ?? null,         fn($q, $v) => $q->where('status', $v))
            ->orderBy('lastname');
    }

    public function headings(): array
    {
        return [
            'N°', 'NOM', 'PRENOMS', 'GENRE', 'DATE DE NAISSANCE', 'ÂGE',
            'TRANCHE D\'ÂGE', 'LIEU DE NAISSANCE', 'NATIONALITE', 'N° CNI',
            'SITUATION MATRIMONIALE', 'NOMBRE D\'ENFANTS',
            'COMMUNE', 'QUARTIER', 'SOUS-QUARTIER', 'TELEPHONE', 'WHATSAPP', 'EMAIL',
            'EGLISE D\'ORIGINE', 'ANNEE D\'ARRIVEE', 'BAPTISE',
            'NIVEAU D\'ETUDE', 'PROFESSION', 'ENTREPRISE',
            'RESPONSABILITE ACTUELLE', 'EQUIPES', 'STATUT',
        ];
    }

    public function map($believer): array
    {
        static $counter = 0;
        $counter++;

        return [
            $counter,
            $believer->lastname,
            $believer->firstname,
            $believer->gender === 'M' ? 'Homme' : 'Femme',
            $believer->birth_date?->format('d/m/Y') ?? '',
            $believer->age ?? '',
            $believer->age_group,
            $believer->birth_place ?? '',
            $believer->nationality ?? '',
            $believer->cni_number ?? '',
            $believer->marital_status ?? '',
            $believer->number_of_children,
            $believer->address?->commune ?? '',
            $believer->address?->quartier ?? '',
            $believer->address?->sous_quartier ?? '',
            $believer->address?->phone ?? '',
            $believer->address?->whatsapp ?? '',
            $believer->address?->email ?? '',
            $believer->churchInformation?->original_church ?? '',
            $believer->churchInformation?->arrival_year ?? '',
            $believer->churchInformation?->baptised ? 'Oui' : 'Non',
            $believer->education?->niveau_etude ?? '',
            $believer->profession?->profession ?? '',
            $believer->profession?->company ?? '',
            $believer->responsibility?->current ?? '',
            $believer->teams->pluck('name')->join(', '),
            ucfirst($believer->status ?? 'actif'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $lastCol = $sheet->getHighestColumn();

        // En-tête principal
        $sheet->insertNewRowBefore(1, 2);
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->setCellValue('A1', 'LISTE DES FIDÈLES — ' . now()->format('d/m/Y'));
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 14, 'name' => 'Arial'],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '0F1E33']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(35);

        // Sous-titre
        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->setCellValue('A2', 'Total : ' . ($lastRow - 3) . ' fidèles');
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['italic' => true, 'color' => ['rgb' => '595959'], 'size' => 10],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'F2F2F2']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Ligne d'en-tête colonnes
        $sheet->getStyle("A3:{$lastCol}3")->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10, 'name' => 'Arial'],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '1F4E79']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
        ]);
        $sheet->getRowDimension(3)->setRowHeight(40);

        // Alternance de couleurs sur les lignes de données
        for ($row = 4; $row <= $lastRow; $row++) {
            $color = ($row % 2 === 0) ? 'EBF3FB' : 'FFFFFF';
            $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => $color]],
                'font' => ['name' => 'Arial', 'size' => 9],
            ]);
        }

        // Bordures globales
        $sheet->getStyle("A3:{$lastCol}{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => 'thin', 'color' => ['rgb' => 'CCCCCC']],
            ],
        ]);

        // Largeurs colonnes
        $widths = [5, 18, 20, 8, 14, 5, 14, 16, 14, 16, 16, 8, 14, 14, 14, 14, 14, 22, 18, 10, 8, 16, 18, 20, 22, 20, 10];
        foreach ($widths as $i => $w) {
            $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 1);
            $sheet->getColumnDimension($col)->setWidth($w);
        }

        // Figer les 3 premières lignes
        $sheet->freezePane('A4');

        return [];
    }
}