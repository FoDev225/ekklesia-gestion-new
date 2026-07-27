<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FuneralRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'believer_id'         => 'required|exists:believers,id',
            'parent_firstname'    => 'required|string|max:100',
            'parent_lastname'     => 'required|string|max:100',
            'family_relationship' => 'required|in:pere,mere,enfant',
            'death_date'          => 'required|date',
            'burial_place'        => 'required|string|max:200',
            'cause_of_death'      => 'nullable|string|max:200',
            'funeral_date'        => 'required|date|after_or_equal:death_date',
            'funeral_place'       => 'required|string|max:200',
            'loincloths_number'   => 'required|string|max:50',
            'amount_paid'         => 'required|string|max:50',
            'nbre_pagne'          => 'nullable|string|max:50',
            'cash_amount'         => 'nullable|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'believer_id.required'         => 'Veuillez sélectionner un fidèle.',
            'parent_firstname.required'    => 'Le prénom du défunt est obligatoire.',
            'parent_lastname.required'     => 'Le nom du défunt est obligatoire.',
            'family_relationship.required' => 'Le lien de parenté est obligatoire.',
            'death_date.required'          => 'La date de décès est obligatoire.',
            'burial_place.required'        => 'Le lieu d\'inhumation est obligatoire.',
            'funeral_date.required'        => 'La date des funérailles est obligatoire.',
            'funeral_date.after_or_equal'  => 'La date des funérailles doit être après ou égale à la date de décès.',
            'funeral_place.required'       => 'Le lieu des funérailles est obligatoire.',
            'loincloths_number.required'   => 'Le nombre de pagnes est obligatoire.',
            'amount_paid.required'         => 'Le montant versé est obligatoire.',
        ];
    }
}