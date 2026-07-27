<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class FideleTemplateSheet implements FromArray, WithTitle, WithEvents, WithColumnWidths
{
    /**
     * Les 35 colonnes attendues par BelieversImport, dans l'ordre exact.
     */
    public function headings(): array
    {
        return [
            'NOM *',
            'PRENOMS *',
            'GENRE * (M/F)',
            'DATE DE NAISSANCE (JJ/MM/AAAA)',
            'LIEU DE NAISSANCE',
            'NATIONALITE',
            'N° CNI',
            "SITUATION MATRIMONIALE\n(celibataire/marie/veuf/divorce)",
            "NOMBRE D'ENFANTS",
            'COMMUNE',
            'QUARTIER',
            'SOUS QUARTIER',
            'TELEPHONE',
            'WHATSAPP',
            'EMAIL',
            "CONNAISSANCE DE L'EGLISE",
            "EGLISE D'ORIGINE",
            "ANNEE D'ARRIVEE",
            'DATE DE CONVERSION (JJ/MM/AAAA)',
            'LIEU DE CONVERSION',
            'BAPTISE (OUI/NON)',
            'ANNEE DE BAPTEME',
            'LIEU DE BAPTEME',
            'NOM DU PASTEUR',
            'N° CARTE DE BAPTEME',
            "NIVEAU D'ETUDE",
            'DIPLOME',
            'QUALIFICATION',
            'PROFESSION',
            'FONCTION',
            'ENTREPRISE/SERVICE',
            'TEL PROFESSIONNEL',
            'RESPONSABILITE ANTERIEURE',
            'RESPONSABILITE ACTUELLE',
            'SOUHAIT DE SERVICE',
        ];
    }

    /**
     * Ligne d'exemple illustrant le format attendu pour chaque colonne.
     */
    protected function exampleRow(): array
    {
        return [
            'COULIBALY', 'AMIDOU', 'M', '15/03/1990', 'KORHOGO', 'Ivoirienne',
            'C0012345678', 'celibataire', '0', 'Yopougon', 'Niangon', 'Laurier 5',
            '07 01 23 45 67', '07 01 23 45 67', 'example@mail.com', 'Par un ami',
            'AEBECI ADJAME', '2015', '12/05/2010', 'ABIDJAN', 'OUI', '2015',
            'AEBECI Yopougon', 'PST YOCOLE ALAIN', 'BAP-0001', 'Licence',
            'Licence Informatique', 'Développeur', 'Ingénieur', 'Développeur',
            'ENTREPRISE X', '0700000000', 'Choriste', 'Président de culte',
            'Évangélisation',
        ];
    }

    /**
     * Construit toutes les lignes brutes de la feuille :
     * L1 = titre, L2 = sous-titre, L3 = en-têtes, L4 = exemple.
     */
    public function array(): array
    {
        $columnCount = count($this->headings());

        $titleRow    = array_pad(["LISTE DES FIDÈLES — TEMPLATE D'IMPORT"], $columnCount, null);
        $subtitleRow = array_pad(['* Champs obligatoires | Laissez vide si non applicable | Ne pas modifier les noms de colonnes'], $columnCount, null);

        return [
            $titleRow,
            $subtitleRow,
            $this->headings(),
            $this->exampleRow(),
        ];
    }

    public function title(): string
    {
        return 'Fidèles';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 18, 'B' => 18, 'C' => 12, 'D' => 20, 'E' => 16,
            'F' => 14, 'G' => 16, 'H' => 22, 'I' => 14, 'J' => 16,
            'K' => 16, 'L' => 16, 'M' => 16, 'N' => 16, 'O' => 22,
            'P' => 20, 'Q' => 18, 'R' => 14, 'S' => 20, 'T' => 16,
            'U' => 16, 'V' => 16, 'W' => 16, 'X' => 18, 'Y' => 18,
            'Z' => 16, 'AA' => 20, 'AB' => 18, 'AC' => 18, 'AD' => 16,
            'AE' => 20, 'AF' => 18, 'AG' => 22, 'AH' => 22, 'AI' => 22,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastColumn = 'AI'; // 35e colonne

                // --- Fusions ---
                $sheet->mergeCells("A1:{$lastColumn}1");
                $sheet->mergeCells("A2:{$lastColumn}2");

                // --- Ligne 1 : titre principal ---
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'name' => 'Arial', 'bold' => true, 'size' => 13,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '0F1E33'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(30);

                // --- Ligne 2 : sous-titre / consignes ---
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => [
                        'name' => 'Arial', 'italic' => true, 'size' => 9,
                        'color' => ['rgb' => '595959'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F2F2F2'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $sheet->getRowDimension(2)->setRowHeight(18);

                // --- Ligne 3 : en-têtes de colonnes ---
                $sheet->getStyle("A3:{$lastColumn}3")->applyFromArray([
                    'font' => [
                        'name' => 'Arial', 'bold' => true, 'size' => 10,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '0F1E33'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                        'wrapText'   => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'FFFFFF'],
                        ],
                    ],
                ]);
                $sheet->getRowDimension(3)->setRowHeight(45);

                // --- Ligne 4 : ligne d'exemple ---
                $sheet->getStyle("A4:{$lastColumn}4")->applyFromArray([
                    'font' => [
                        'name' => 'Arial', 'italic' => true, 'size' => 9,
                        'color' => ['rgb' => '595959'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'EBF3FB'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // --- Volets figés : les 3 premières lignes restent visibles au scroll ---
                $sheet->freezePane('A4');
            },
        ];
    }
}