<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PublicBelieverRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Honeypot anti-spam : ce champ doit rester vide (voir vue)
            'website' => ['prohibited'],

            'lastname'           => ['required', 'string', 'max:255'],
            'firstname'          => ['required', 'string', 'max:255'],
            'gender'              => ['required', 'in:M,F'],
            'birth_date'          => ['nullable', 'date', 'before:today'],
            'birth_place'         => ['nullable', 'string', 'max:255'],
            'nationality'         => ['nullable', 'string', 'max:255'],
            'marital_status'      => ['nullable', 'in:celibataire,marie,veuf,divorce'],
            'number_of_children'  => ['nullable', 'integer', 'min:0'],

            'address.commune'       => ['nullable', 'string', 'max:255'],
            'address.quartier'      => ['nullable', 'string', 'max:255'],
            'address.sous_quartier' => ['nullable', 'string', 'max:255'],
            'address.phone'         => ['required', 'string', 'max:30'],
            'address.whatsapp'      => ['nullable', 'string', 'max:30'],
            'address.email'         => ['nullable', 'email', 'max:255'],

            'church.connaissance_eglise' => ['nullable', 'string', 'max:255'],
            'church.original_church'     => ['nullable', 'string', 'max:255'],
            'church.baptised'            => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'lastname.required'      => 'Le nom est obligatoire.',
            'firstname.required'     => 'Le prénom est obligatoire.',
            'gender.required'        => 'Le sexe est obligatoire.',
            'address.phone.required' => 'Le numéro de téléphone est obligatoire.',
        ];
    }
}