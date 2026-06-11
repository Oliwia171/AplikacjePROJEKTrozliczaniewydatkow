<?php

namespace App\Http\Requests;

use App\Models\Group;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBillRequest extends FormRequest
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
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payer_id' => [
                'required',
                Rule::exists('group_user', 'user_id')->where('group_id', $group->id),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'description.required' => 'Podaj nazwe wydatku. Bez nazwy wydatek nie zostanie zapisany.',
            'amount.required' => 'Podaj kwote wydatku.',
            'amount.numeric' => 'Kwota musi byc liczba.',
            'amount.min' => 'Kwota musi byc dodatnia. Nie mozna wpisac kwoty ujemnej ani zera.',
            'payer_id.required' => 'Wybierz platnika.',
            'payer_id.exists' => 'Platnik musi byc czlonkiem tej grupy.',
        ];
    }
}
