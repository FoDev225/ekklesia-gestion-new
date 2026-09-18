<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFinancePeriodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type'           => ['required', 'in:S1,S2'],
            'annee'          => ['required', 'integer', 'min:2020', 'max:2100'],
            'date_debut'     => ['required', 'date'],
            'date_fin'       => ['required', 'date', 'after:date_debut'],
            'date_ag_prevue' => ['nullable', 'date'],
        ];
    }
}