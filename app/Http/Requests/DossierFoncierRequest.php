<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DossierFoncierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'libelle'          => ['required', 'string', 'max:255'],
            'localisation'     => ['nullable', 'string', 'max:255'],
            'superficie'       => ['nullable', 'numeric', 'min:0'],
            'statut'           => ['required', 'in:recherche,negociation,acquis,titre_obtenu,abandonne'],
            'cout'             => ['nullable', 'numeric', 'min:0'],
            'date_debut'       => ['required', 'date'],
            'date_acquisition' => ['nullable', 'date', 'after_or_equal:date_debut'],
            'notes'            => ['nullable', 'string', 'max:2000'],
            'document'         => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'libelle.required'    => "Le libellé du dossier est obligatoire.",
            'date_debut.required' => "La date de début est obligatoire.",
            'document.mimes'      => "Le document doit être un fichier PDF.",
        ];
    }
}