<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFinanceEngagementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'believer_id'      => ['required', 'exists:believers,id'],
            'motif'            => ['required', 'string', 'max:255'],
            'fleche_type'      => ['nullable', 'in:construction_project,dossier_foncier'],
            'fleche_id'        => ['nullable', 'integer', 'required_with:fleche_type'],
            'montant_engage'   => ['required', 'numeric', 'min:1'],
            'date_engagement'  => ['required', 'date'],
            'observations'     => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'believer_id.required'    => 'Veuillez sélectionner un fidèle.',
            'motif.required'          => 'Le motif est obligatoire (ex: Construction).',
            'montant_engage.required' => "Le montant de l'engagement est obligatoire.",
        ];
    }
}