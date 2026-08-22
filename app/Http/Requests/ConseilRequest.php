<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConseilRequest extends FormRequest
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
            'believer_id' => [
                'required',
                'exists:believers,id',
                Rule::unique('conseils', 'believer_id')->where(fn ($q) => $q->where('is_active', true)),
            ],
            'role'           => ['required', 'string', 'max:255'],
            'contact'        => ['nullable', 'digits:10'],
            'joined_at'      => ['nullable', 'date'],
            'left_at'      => ['nullable', 'date'],
            'is_active'      => ['nullable', 'boolean'],
        ];
    }
}
