<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'leader_id'   => ['nullable', 'integer', 'exists:believers,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => "Le nom du groupe est obligatoire.",
        ];
    }
}