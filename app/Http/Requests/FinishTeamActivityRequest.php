<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FinishTeamActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'attendance_list_path' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            'report_path'          => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'attendance_list_path.required' => "La liste de présence est obligatoire.",
            'attendance_list_path.mimes'    => "La liste de présence doit être un fichier PDF.",
            'report_path.required'          => "Le rapport est obligatoire.",
            'report_path.mimes'             => "Le rapport doit être un fichier PDF.",
        ];
    }
}