<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChildDedicationRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'father_id'       => 'required|exists:believers,id',
            'mother_id'       => 'required|exists:believers,id',
            'father_name'     => 'nullable|string|max:150',
            'mother_name'     => 'nullable|string|max:150',
            'demande_date'    => 'required|date',
            'dedication_date' => 'required|date|after_or_equal:demande_date',
            'child_lastname'  => 'required|string|max:100',
            'child_firstname' => 'required|string|max:150',
            'gender'          => 'required|in:Féminin,Masculin',
            'child_birthdate' => 'required|date|before_or_equal:today',
            'child_birthplace'=> 'required|string|max:150',
        ];
    }

    public function messages(): array
    {
        return [
            'father_id.required'       => 'Veuillez sélectionner le père.',
            'mother_id.required'       => 'Veuillez sélectionner la mère.',
            'demande_date.required'    => 'La date de demande est obligatoire.',
            'dedication_date.required' => 'La date de présentation est obligatoire.',
            'dedication_date.after_or_equal' => 'La date de présentation doit être après ou égale à la date de demande.',
            'child_lastname.required'  => 'Le nom de l\'enfant est obligatoire.',
            'child_firstname.required' => 'Le prénom de l\'enfant est obligatoire.',
            'gender.required'          => 'Le sexe de l\'enfant est obligatoire.',
            'child_birthdate.required' => 'La date de naissance est obligatoire.',
            'child_birthdate.before_or_equal' => 'La date de naissance doit être dans le passé.',
            'child_birthplace.required'=> 'Le lieu de naissance est obligatoire.',
        ];
    }
}