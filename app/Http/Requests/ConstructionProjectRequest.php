<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ConstructionProjectRequest extends FormRequest
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
            'libelle'        => ['required', 'string', 'max:255'],
            'date_lancement' => ['required', 'date'],
            'date_fin'       => ['nullable', 'date', 'after_or_equal:date_lancement'],
            'cout'           => ['nullable', 'numeric', 'min:0'],
            'status'         => ['required', 'in:en_cours,realise'],
            'rapport'        => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'libelle.required'        => "Le libellé du projet est obligatoire.",
            'date_lancement.required' => "La date de lancement est obligatoire.",
            'date_fin.after_or_equal' => "La date de fin doit être postérieure ou égale à la date de lancement.",
            'rapport.mimes'           => "Le rapport doit être un fichier PDF.",
        ];
    }
}
