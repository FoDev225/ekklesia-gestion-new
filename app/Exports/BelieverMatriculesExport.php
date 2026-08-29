<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BelieverMatriculesExport implements FromCollection, WithHeadings
{
    protected $believers;

    public function __construct($believers)
    {
        $this->believers = $believers;
    }

    public function collection()
    {
        return $this->believers->map(fn($b) => [
            'nom'                  => strtoupper($b->lastname),
            'prenom'               => $b->firstname,
            'matricule'            => $b->register_number,
            'nom_fichier_attendu'  => $b->register_number . '.jpg',
        ]);
    }

    public function headings(): array
    {
        return ['NOM', 'PRÉNOM', 'MATRICULE', 'NOM DE FICHIER ATTENDU POUR LA PHOTO'];
    }
}