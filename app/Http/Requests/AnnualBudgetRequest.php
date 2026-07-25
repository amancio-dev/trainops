<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AnnualBudgetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canManage() ?? false;
    }

    public function rules(): array
    {
        return [
            'year' => [
                'required',
                'integer',
                'min:2020',
                'max:'.(now()->year + 5),
                Rule::unique('annual_budgets')->ignore($this->route('budget')),
            ],
            'amount' => ['required', 'numeric', 'min:0', 'max:999999999999.99'],
            'owner_id' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }
}
