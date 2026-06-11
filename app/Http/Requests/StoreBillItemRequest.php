<?php

namespace App\Http\Requests;

use App\Models\Group;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBillItemRequest extends FormRequest
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
            'bill_item_bill_id' => ['nullable', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0.01'],
            'quantity' => ['required', 'integer', 'min:1'],
            'user_ids' => ['required', 'array', 'min:1'],
            'user_ids.*' => [
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
            'name.required' => 'Podaj nazwe pozycji z paragonu.',
            'price.required' => 'Podaj cene pozycji.',
            'price.numeric' => 'Cena musi byc liczba.',
            'price.min' => 'Cena musi byc dodatnia. Nie mozna wpisac kwoty ujemnej ani zera.',
            'quantity.required' => 'Podaj liczbe sztuk.',
            'quantity.integer' => 'Liczba sztuk musi byc liczba calkowita.',
            'quantity.min' => 'Liczba sztuk musi wynosic co najmniej 1.',
            'user_ids.required' => 'Wybierz co najmniej jedna osobe do tej pozycji.',
            'user_ids.*.exists' => 'Pozycje mozna przypisac tylko czlonkom tej grupy.',
        ];
    }
}
