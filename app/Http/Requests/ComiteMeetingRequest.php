<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ComiteMeetingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'meeting_type'          => ['required', 'in:ordinaire,extraordinaire'],
            'meeting_date'          => ['required', 'date'],
            'meeting_time'          => ['required', 'date_format:H:i'],
            'meeting_objective' => ['required', 'string', 'max:2000'],
            'rapport_path'       => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'meeting_type.required'          => "Le type de réunion est obligatoire.",
            'meeting_date.required'          => "La date est obligatoire.",
            'meeting_objective.required' => "L'ordre du jour est obligatoire.",
            'rapport_path.mimes'          => "Le rapport doit être un fichier PDF.",
        ];
    }
}
