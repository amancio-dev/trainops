<?php

namespace App\Http\Requests;

use App\Enums\TrainingStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TrainingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canManage() ?? false;
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'integer', 'exists:users,id'],
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'annual_budget_id' => ['nullable', 'integer', 'exists:annual_budgets,id'],
            'institution' => ['required', 'string', 'max:160'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after_or_equal:starts_at'],
            'status' => ['required', Rule::enum(TrainingStatus::class)],
            'registration_cost' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'lodging_cost' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'transport_cost' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'transfer_cost' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'daily_allowance_cost' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'cancellation_reason' => [
                Rule::requiredIf($this->input('status') === TrainingStatus::Cancelled->value),
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    public function after(): array
    {
        return [
            function ($validator): void {
                if (! $this->filled('annual_budget_id') || $validator->errors()->hasAny(['annual_budget_id', 'starts_at'])) {
                    return;
                }

                $budgetYear = \App\Models\AnnualBudget::query()->find($this->integer('annual_budget_id'))?->year;
                $startYear = $this->date('starts_at')?->year;

                if ($budgetYear && $startYear && $budgetYear !== $startYear) {
                    $validator->errors()->add('annual_budget_id', 'O orçamento deve ser do mesmo ano do início do treinamento.');
                }
            },
        ];
    }
}
