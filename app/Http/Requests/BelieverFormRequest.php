<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BelieverFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Infos générales
            'lastname'           => 'required|string|max:100',
            'firstname'          => 'required|string|max:100',
            'cni_number'         => 'nullable|string|max:20|unique:believers,cni_number,' . ($this->believer?->id ?? 'NULL'),
            'birth_date'         => 'nullable|date|before:today',
            'birth_place'        => 'nullable|string|max:100',
            'nationality'        => 'nullable|string|max:100',
            'gender'             => 'required|in:M,F',
            'marital_status'     => 'nullable|in:celibataire,marie,veuf,divorce',
            'number_of_children' => 'nullable|integer|min:0',
            'family_id'          => 'nullable|exists:families,id',
            'profile_picture' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],

            // Adresse
            'address.commune'      => 'nullable|string|max:100',
            'address.quartier'     => 'nullable|string|max:100',
            'address.sous_quartier'=> 'nullable|string|max:100',
            'address.phone'        => 'nullable|string|max:20',
            'address.whatsapp'     => 'nullable|string|max:20',
            'address.email'        => 'nullable|email|max:150',

            // Infos église
            'church.connaissance_eglise'  => 'nullable|string|max:200',
            'church.original_church'      => 'nullable|string|max:150',
            'church.arrival_year'         => 'nullable|integer|min:1900|max:' . date('Y'),
            'church.conversion_date'      => 'nullable|date',
            'church.conversion_place'     => 'nullable|string|max:150',
            'church.baptised'             => 'nullable|boolean',
            'church.baptism_date'         => 'nullable|date',
            'church.baptism_place'        => 'nullable|string|max:150',
            'church.baptism_pastor'       => 'nullable|string|max:150',
            'church.baptism_card_number'  => 'nullable|string|max:50',

            // Éducation
            'education.niveau_etude' => 'nullable|string|max:100',
            'education.diploma'      => 'nullable|string|max:150',
            'education.qualification'=> 'nullable|string|max:150',

            // Profession
            'profession.profession'          => 'nullable|string|max:150',
            'profession.function'            => 'nullable|string|max:150',
            'profession.company'             => 'nullable|string|max:150',
            'profession.professional_contact'=> 'nullable|string|max:50',

            // Responsabilités
            'responsibility.old'     => 'nullable|string|max:255',
            'responsibility.current' => 'nullable|string|max:255',
            'responsibility.desire'  => 'nullable|string|max:255',

            // Relations
            'teams'   => 'nullable|array',
            'teams.*' => 'exists:teams,id',
            'groups'  => 'nullable|array',
            'groups.*'=> 'exists:groups,id',
            'cell_id' => 'nullable|exists:cells,id',
        ];
    }

    public function messages(): array
    {
        return [
            'lastname.required'  => 'Le nom est obligatoire.',
            'firstname.required' => 'Le prénom est obligatoire.',
            'gender.required'    => 'Le genre est obligatoire.',
            'gender.in'          => 'Le genre doit être Homme ou Femme.',
            'cni_number.unique'  => 'Ce numéro CNI est déjà utilisé.',
            'birth_date.before'  => 'La date de naissance doit être dans le passé.',
        ];
    }
}