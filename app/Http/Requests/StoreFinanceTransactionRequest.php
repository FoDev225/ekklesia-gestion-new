<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFinanceTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type'                     => ['required', 'in:recette,depense'],
            'category_id'              => ['required', 'exists:finance_categories,id'],
            'account_id'               => ['required', 'exists:finance_accounts,id'],
            'montant'                  => ['required', 'numeric', 'min:0.01'],
            'date'                     => ['required', 'date'],
            'description'              => ['nullable', 'string', 'max:1000'],
            'piece_justificative'      => ['required_if:type,depense', 'nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'fleche_type'              => ['nullable', 'in:construction_project,dossier_foncier'],
            'fleche_id'                => ['nullable', 'integer', 'required_with:fleche_type'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required'         => 'Veuillez choisir une catégorie.',
            'account_id.required'          => 'Veuillez choisir un compte.',
            'montant.required'             => 'Le montant est obligatoire.',
            'piece_justificative.required_if' => 'La pièce justificative est obligatoire pour une dépense.',
        ];
    }
}