<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewcomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'lastname'         => 'required|string|max:100',
            'firstname'        => 'required|string|max:100',
            'gender'           => 'nullable|in:M,F',
            'birth_date'       => 'nullable|date|before:today',
            'phone'            => 'nullable|string|max:20',
            'whatsapp'         => 'nullable|string|max:20',
            'category'         => 'required|in:passage,court_sejour,demeurant,nouveau_converti',
            'is_recommended'   => 'nullable|boolean',
            'recommended_by'   => 'nullable|string|max:150',
            'first_visit_date' => 'required|date',
            'notes'            => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'lastname.required'         => 'Le nom est obligatoire.',
            'firstname.required'        => 'Le prénom est obligatoire.',
            'category.required'         => 'La catégorie est obligatoire.',
            'first_visit_date.required' => 'La date de première visite est obligatoire.',
        ];
    }

    // is_recommended n'est applicable qu'aux non-convertis
    protected function prepareForValidation(): void
    {
        if ($this->category === 'nouveau_converti') {
            $this->merge([
                'is_recommended' => null,
                'recommended_by' => null,
            ]);
        }
    }
}