<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssignGroupBelieverRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $groupId = $this->route('group')->id;

        return [
            'believer_id' => [
                'required',
                'integer',
                'exists:believers,id',
                Rule::unique('believer_group', 'believer_id')->where(fn ($q) => $q->where('group_id', $groupId)),
            ],
            'joined_at' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'believer_id.unique' => "Ce fidèle est déjà membre de ce groupe.",
        ];
    }
}