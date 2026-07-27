<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTeamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $teamId = $this->route('team')->id;

        return [
            'name'        => ['required', 'string', 'max:255'],
            'slug'        => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('teams', 'slug')->ignore($teamId)],
            'description' => ['nullable', 'string', 'max:1000'],
            'leader_id'   => ['nullable', 'integer', 'exists:believers,id'],
        ];
    }
}