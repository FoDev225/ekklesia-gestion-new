<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ConseilAgRequest extends FormRequest
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
            'ag_type'          => ['required', 'in:ordinaire,extraordinaire'],
            'ag_date'          => ['required', 'date'],
            'ag_time'          => ['required', 'date_format:H:i'],
            'ag_objective' => ['required', 'string', 'max:2000'],
            'rapport_path'       => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ];
    }
}
