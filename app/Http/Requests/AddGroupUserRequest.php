<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddGroupUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:users,email'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Podaj adres e-mail uzytkownika.',
            'email.email' => 'Podaj poprawny adres e-mail.',
            'email.exists' => 'Nie znaleziono uzytkownika o takim adresie e-mail.',
        ];
    }
}
