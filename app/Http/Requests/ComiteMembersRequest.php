<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ComiteMembersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'believer_id' => [
                'required',
                'exists:believers,id',
                Rule::unique('comites', 'believer_id')->where(fn ($q) => $q->where('is_active', true)),
            ],
            'role'           => ['required', 'string', 'max:255'],
            'contact'        => ['nullable', 'digits:10'],
            'joined_at'      => ['nullable', 'date'],
            'left_at'      => ['nullable', 'date'],
            'is_active'      => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'believer_id.required' => "Veuillez sélectionner un fidèle.",
            'believer_id.unique'   => "Ce fidèle est déjà membre actif du comité.",
            'role.required'        => "La fonction est obligatoire.",
            'contact.required'     => "Le numéro de téléphone est obligatoire.",
            'contact.digits'       => "Le numéro de téléphone doit contenir 10 chiffres.",
            'joined_at.date'       => "La date d'adhésion doit être une date valide.",
            'left_at.date'         => "La date de sortie doit être une date valide.",
        ];
    }
}
