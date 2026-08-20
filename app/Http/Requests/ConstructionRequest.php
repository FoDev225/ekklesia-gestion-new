<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConstructionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Adjust this based on your authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'believer_id' => [
                'required',
                'exists:believers,id',
                Rule::unique('equipe_constructions', 'believer_id')->where(fn ($q) => $q->where('is_active', true)),
            ],
            'role'      => ['required', 'string', 'max:255'],
            'contact'   => ['nullable', 'string', 'max:30'],
            'joined_at' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'believer_id.required' => "Veuillez sélectionner un fidèle.",
            'believer_id.unique'   => "Ce fidèle est déjà membre actif de l'équipe.",
            'role.required'        => "La fonction est obligatoire.",
        ];
    }
}
