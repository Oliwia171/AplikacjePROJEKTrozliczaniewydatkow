<?php

namespace App\Http\Requests;

use App\Models\Group;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BillFiltersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        /** @var Group $group */
        $group = $this->route('group');

        return [
            'bill_search' => ['nullable', 'string', 'max:255'],
            'payer_id' => [
                'nullable',
                'integer',
                Rule::exists('group_user', 'user_id')->where('group_id', $group->id),
            ],
            'amount_from' => ['nullable', 'numeric', 'min:0'],
            'amount_to' => ['nullable', 'numeric', 'min:0'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'amount_from.min' => 'Minimalna kwota nie moze byc ujemna.',
            'amount_to.min' => 'Maksymalna kwota nie moze byc ujemna.',
            'payer_id.exists' => 'Wybrany platnik nie nalezy do tej grupy.',
            'date_from.date' => 'Data poczatkowa ma niepoprawny format.',
            'date_to.date' => 'Data koncowa ma niepoprawny format.',
            'date_to.after_or_equal' => 'Data koncowa nie moze byc wczesniejsza niz data poczatkowa.',
        ];
    }
}
