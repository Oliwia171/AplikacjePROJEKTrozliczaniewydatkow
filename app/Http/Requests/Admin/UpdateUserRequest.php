<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'role' => ['required', 'in:user,admin'],
            'name' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Podaj imie uzytkownika.',
            'role.required' => 'Wybierz role uzytkownika.',
            'role.in' => 'Dostepne sa tylko dwa typy kont: user oraz admin.',
        ];
    }
}
