<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssignBelieverRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $teamId = $this->route('team')->id;

        return [
            'believer_id' => [
                'required',
                'integer',
                'exists:believers,id',
                // Empêche d'affecter deux fois le même fidèle à la même équipe
                Rule::unique('believer_team', 'believer_id')->where(fn ($q) => $q->where('team_id', $teamId)),
            ],
            'joined_at' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'believer_id.unique' => "Ce fidèle est déjà membre de cette équipe.",
        ];
    }
}