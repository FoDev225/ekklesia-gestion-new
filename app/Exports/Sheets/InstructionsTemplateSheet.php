<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class InstructionsTemplateSheet implements FromArray, WithTitle, WithEvents, WithColumnWidths
{
    /**
     * Numéros de ligne (1-indexés) à fusionner sur A:B — titres et séparateurs.
     */
    protected array $mergedRows = [1, 2, 3, 4, 8, 9, 14, 15];

    /**
     * Numéros de ligne correspondant aux en-têtes de section.
     */
    protected array $sectionHeaderRows = [4, 9, 15];

    public function array(): array
    {
        return [
            ["GUIDE D'IMPORTATION DES FIDÈLES", null],
            ['Suivez ces indications pour préparer correctement votre fichier', null],
            [null, null],
            ['CHAMPS OBLIGATOIRES', null],
            ['NOM', 'Le nom de famille du fidèle'],
            ['PRENOMS', 'Les prénoms du fidèle'],
            ['GENRE', 'M pour Homme, F pour Femme'],
            [null, null],
            ['FORMATS ACCEPTÉS', null],
            ['DATE DE NAISSANCE', 'Format JJ/MM/AAAA — Ex: 15/03/1990'],
            ['SITUATION MATRIMONIALE', 'Toute mention de célibataire / marié / veuf / divorcé est acceptée (accents et casse indifférents)'],
            ['BAPTISE', 'Valeurs acceptées : OUI ou NON'],
            ['GENRE', 'Valeurs acceptées : M ou F'],
            [null, null],
            ['RÈGLES IMPORTANTES', null],
            ['Valeurs vides', "Laissez la cellule vide si l'information n'est pas disponible"],
            ['NEANT', "Les valeurs 'NEANT' seront automatiquement ignorées"],
            ['Doublons', 'Si un fidèle avec le même nom et prénom existe déjà, la ligne sera ignorée'],
            ['Casse', 'Les majuscules/minuscules sont acceptées'],
        ];
    }

    public function title(): string
    {
        return 'Instructions';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 28,
            'B' => 65,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // --- Fusions sur les lignes de titre/section/séparateur ---
                foreach ($this->mergedRows as $row) {
                    $sheet->mergeCells("A{$row}:B{$row}");
                }

                // --- Ligne 1 : titre principal ---
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'name' => 'Arial', 'bold' => true, 'size' => 14,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '0F1E33'],
                    ],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(28);

                // --- Ligne 2 : sous-titre ---
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => ['name' => 'Arial', 'italic' => true, 'size' => 10, 'color' => ['rgb' => '595959']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // --- En-têtes de section ---
                foreach ($this->sectionHeaderRows as $row) {
                    $sheet->getStyle("A{$row}")->applyFromArray([
                        'font' => ['name' => 'Arial', 'bold' => true, 'size' => 11, 'color' => ['rgb' => '1F4E79']],
                    ]);
                }

                // --- Colonne A (labels) en gras sur les lignes de contenu ---
                $contentRows = [5, 6, 7, 10, 11, 12, 13, 16, 17, 18, 19];
                foreach ($contentRows as $row) {
                    $sheet->getStyle("A{$row}")->applyFromArray([
                        'font' => ['name' => 'Arial', 'bold' => true, 'size' => 10],
                    ]);
                    $sheet->getStyle("B{$row}")->applyFromArray([
                        'font' => ['name' => 'Arial', 'size' => 10],
                        'alignment' => ['wrapText' => true],
                    ]);
                }
            },
        ];
    }
}