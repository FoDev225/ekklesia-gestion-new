<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFinanceBudgetLineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id'    => ['required', 'exists:finance_categories,id'],
            'montant_alloue' => ['required', 'numeric', 'min:0'],
        ];
    }
}