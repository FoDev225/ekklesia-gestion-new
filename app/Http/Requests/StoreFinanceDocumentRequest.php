<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFinanceDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type'                => ['required', 'in:recette,depense'],
            'numero_document'     => ['nullable', 'string', 'max:50'],
            'date'                => ['required', 'date'],
            'account_id'          => ['required', 'exists:finance_accounts,id'],
            'mode_paiement'       => ['required_if:type,depense', 'nullable', 'in:espece,cheque'],
            'numero_cheque'       => ['required_if:mode_paiement,cheque', 'nullable', 'string', 'max:50'],
            'beneficiaire'        => ['required_if:type,depense', 'nullable', 'string', 'max:255'],
            'emetteur'            => ['nullable', 'string', 'max:255'],
            'verificateur'        => ['nullable', 'string', 'max:255'],
            'recepteur'           => ['nullable', 'string', 'max:255'],
            'piece_justificative' => ['required_if:type,depense', 'nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'fleche_type'         => ['nullable', 'in:construction_project,dossier_foncier'],
            'fleche_id'           => ['nullable', 'integer', 'required_with:fleche_type'],

            'lines'               => ['required', 'array', 'min:1'],
            'lines.*.category_id' => ['required', 'exists:finance_categories,id'],
            'lines.*.montant'     => ['required', 'numeric', 'min:0.01'],
            'lines.*.motif'       => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'account_id.required'               => 'Veuillez choisir un compte.',
            'beneficiaire.required_if'           => 'Le bénéficiaire est obligatoire pour un bon de sortie.',
            'mode_paiement.required_if'          => 'Le mode de paiement est obligatoire pour une dépense.',
            'numero_cheque.required_if'          => 'Le numéro de chèque est obligatoire.',
            'piece_justificative.required_if'    => 'La pièce justificative est obligatoire pour une dépense.',
            'lines.required'                     => 'Ajoutez au moins une ligne.',
            'lines.*.category_id.required'       => 'Chaque ligne doit avoir une catégorie.',
            'lines.*.montant.required'           => 'Chaque ligne doit avoir un montant.',
        ];
    }
}