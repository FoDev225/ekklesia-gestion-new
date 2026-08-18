<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRapportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titre'       => ['required', 'string', 'max:255'],
            'date'        => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:2000'],
            'fichier'     => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'titre.required' => "Le titre du rapport est obligatoire.",
            'date.required'   => "La date du rapport est obligatoire.",
            'fichier.mimes'   => "Le fichier doit être un PDF, Word ou une image.",
            'fichier.max'     => "Le fichier ne doit pas dépasser 10 Mo.",
        ];
    }
}