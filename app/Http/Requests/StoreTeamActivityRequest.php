<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeamActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'         => ['required', 'string', 'max:255'],
            'date'          => ['required', 'date'],
            'theme'         => ['nullable', 'string', 'max:255'],
            'location'      => ['nullable', 'string', 'max:255'],
            'moderator'  => ['nullable', 'string', 'max:225'],
            'preacher'   => ['nullable', 'string', 'max:225'],
            'budget'        => ['nullable', 'numeric', 'min:0'],
        ];
    }
}