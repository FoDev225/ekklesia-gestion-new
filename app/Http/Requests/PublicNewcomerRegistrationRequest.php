<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PublicNewcomerRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'website' => ['prohibited'], // honeypot anti-spam

            'lastname'         => ['required', 'string', 'max:255'],
            'firstname'        => ['required', 'string', 'max:255'],
            'gender'           => ['nullable', 'in:M,F'],
            'birth_date'       => ['nullable', 'date', 'before:today'],
            'phone'            => ['required', 'string', 'max:30'],
            'whatsapp'         => ['nullable', 'string', 'max:30'],
            'category'         => ['required', 'in:Passage,Court_sejour,Demeurant,Nouveau_converti'],
            'is_recommended'   => ['nullable', 'boolean'],
            'recommended_by'   => ['nullable', 'required_if:is_recommended,1', 'string', 'max:255'],
            'notes'            => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'lastname.required'  => 'Le nom est obligatoire.',
            'firstname.required' => 'Le prénom est obligatoire.',
            'phone.required'     => 'Le numéro de téléphone est obligatoire.',
            'category.required'  => 'Veuillez préciser la catégorie de la personne.',
            'recommended_by.required_if' => 'Veuillez indiquer le nom du fidèle qui recommande cette personne.',
        ];
    }
}