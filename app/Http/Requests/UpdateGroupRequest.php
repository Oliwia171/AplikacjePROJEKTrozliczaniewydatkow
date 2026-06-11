<?php

namespace App\Http\Requests;

use App\Models\Group;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGroupRequest extends FormRequest
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
        /** @var Group $group */
        $group = $this->route('group');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('groups', 'name')
                    ->where('owner_id', $group->owner_id)
                    ->ignore($group->id),
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
            'name.unique' => 'Ten wlasciciel ma juz grupe o takiej nazwie.',
            'description.max' => 'Opis grupy moze miec maksymalnie 1000 znakow.',
        ];
    }
}
