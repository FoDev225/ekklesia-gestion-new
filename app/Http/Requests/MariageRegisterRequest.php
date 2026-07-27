<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MariageRegisterRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            // Époux — soit fidèle soit saisie libre
            'groom_id'               => 'nullable|exists:believers,id',
            'groom_name'             => 'nullable|required_without:groom_id|string|max:150',
            'groom_birthdate'        => 'nullable|date',
            'groom_birth_place'      => 'nullable|string|max:150',
            'groom_bapistism_date'   => 'nullable|date',
            'groom_bapistism_place'  => 'nullable|string|max:150',
            'baptism_officer_groom'  => 'nullable|string|max:150',
            'groom_profession'       => 'nullable|string|max:150',
            'groom_photo'            => 'nullable|image|max:2048',

            // Épouse — soit fidèle soit saisie libre
            'bride_id'               => 'nullable|exists:believers,id',
            'bride_name'             => 'nullable|required_without:bride_id|string|max:150',
            'bride_birthdate'        => 'nullable|date',
            'bride_birth_place'      => 'nullable|string|max:150',
            'bride_bapistism_date'   => 'nullable|date',
            'bride_bapistism_place'  => 'nullable|string|max:150',
            'baptism_officer_bride'  => 'nullable|string|max:150',
            'bride_profession'       => 'nullable|string|max:150',
            'bride_photo'            => 'nullable|image|max:2048',

            // Mariage civil
            'civil_marriage_date'    => 'required|date',
            'civil_marriage_place'   => 'required|string|max:200',

            // Mariage religieux
            'religious_marriage_date'  => 'required|date|after_or_equal:civil_marriage_date',
            'religious_marriage_place' => 'required|string|max:200',
            'wedding_mc'               => 'nullable|string|max:150',
            'wedding_preacher'         => 'required|string|max:150',
            'hand_bible'               => 'nullable|string|max:150',
            'officiant'                => 'required|string|max:150',

            // Témoins
            'groom_witness'            => 'required|string|max:150',
            'groom_witness_profession' => 'nullable|string|max:150',
            'bride_witness'            => 'required|string|max:150',
            'bride_witness_profession' => 'nullable|string|max:150',
        ];
    }

    public function messages(): array
    {
        return [
            'groom_name.required_without'          => 'Le nom de l\'époux est obligatoire s\'il n\'est pas un fidèle.',
            'bride_name.required_without'          => 'Le nom de l\'épouse est obligatoire si elle n\'est pas une fidèle.',
            'civil_marriage_date.required'         => 'La date du mariage civil est obligatoire.',
            'civil_marriage_place.required'        => 'Le lieu du mariage civil est obligatoire.',
            'religious_marriage_date.required'     => 'La date du mariage religieux est obligatoire.',
            'religious_marriage_date.after_or_equal' => 'Le mariage religieux doit avoir lieu après ou le même jour que le mariage civil.',
            'religious_marriage_place.required'    => 'Le lieu du mariage religieux est obligatoire.',
            'wedding_preacher.required'            => 'Le prédicateur est obligatoire.',
            'officiant.required'                   => 'Le pasteur officiant est obligatoire.',
            'groom_witness.required'               => 'Le témoin de l\'époux est obligatoire.',
            'bride_witness.required'               => 'Le témoin de l\'épouse est obligatoire.',
        ];
    }
}