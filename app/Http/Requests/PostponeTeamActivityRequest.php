<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostponeTeamActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'new_date' => ['required', 'date', 'after_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'new_date.required'      => "La nouvelle date est obligatoire.",
            'new_date.after_or_equal' => "La nouvelle date doit être aujourd'hui ou une date future.",
        ];
    }
}