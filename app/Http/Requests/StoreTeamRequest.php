<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'slug'        => ['required', 'string', 'max:255', 'alpha_dash', 'unique:teams,slug'],
            'description' => ['nullable', 'string', 'max:1000'],
            'leader_id'   => ['nullable', 'integer', 'exists:believers,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => "Le nom de l'équipe est obligatoire.",
            'slug.required' => "Le slug est obligatoire.",
            'slug.unique'   => "Ce slug est déjà utilisé par une autre équipe.",
            'slug.alpha_dash' => "Le slug ne doit contenir que des lettres, chiffres, tirets ou underscores.",
        ];
    }
}