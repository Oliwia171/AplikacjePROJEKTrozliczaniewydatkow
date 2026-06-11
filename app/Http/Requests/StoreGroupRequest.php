<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('groups', 'name')->where('owner_id', $this->user()->id),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Podaj nazwe grupy.',
            'name.unique' => 'Masz juz grupe o takiej nazwie. Wybierz inna nazwe dla swojej grupy.',
            'description.max' => 'Opis grupy moze miec maksymalnie 1000 znakow.',
        ];
    }
}
