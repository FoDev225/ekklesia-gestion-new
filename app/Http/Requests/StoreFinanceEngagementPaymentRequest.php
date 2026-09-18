<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFinanceEngagementPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'numero_recu' => ['nullable', 'string', 'max:50'],
            'montant'     => ['required', 'numeric', 'min:1'],
            'date'        => ['required', 'date'],
            'encaisseur'  => ['nullable', 'string', 'max:255'],
            'account_id'  => ['required', 'exists:finance_accounts,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'montant.required'    => 'Le montant du versement est obligatoire.',
            'account_id.required' => 'Veuillez indiquer sur quel compte le versement a été encaissé.',
        ];
    }
}