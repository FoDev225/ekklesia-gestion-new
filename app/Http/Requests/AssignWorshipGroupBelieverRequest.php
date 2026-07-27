<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssignWorshipGroupBelieverRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $worshipGroupId = $this->route('worship_group')->id;

        return [
            'believer_id' => [
                'required',
                'integer',
                'exists:believers,id',
                Rule::unique('believer_worship_group', 'believer_id')
                    ->where(fn ($q) => $q->where('worship_group_id', $worshipGroupId)),
            ],
            'joined_at' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'believer_id.unique' => "Ce fidèle est déjà membre de ce groupe de louange.",
        ];
    }
}