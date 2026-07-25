<?php

namespace App\Http\Controllers;

use App\Enums\TrainingStatus;
use App\Http\Requests\AnnualBudgetRequest;
use App\Models\AnnualBudget;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class AnnualBudgetController extends Controller
{
    public function index(): Response
    {
        $budgets = AnnualBudget::query()
            ->with('owner:id,name')
            ->orderByDesc('year')
            ->get()
            ->map(function (AnnualBudget $budget) {
                $used = $budget->trainings()
                    ->whereIn('status', [
                        TrainingStatus::Approved->value,
                        TrainingStatus::InProgress->value,
                        TrainingStatus::Completed->value,
                    ])
                    ->get()
                    ->sum('total_cost');

                return [
                    ...$budget->toArray(),
                    'used' => (float) $used,
                    'remaining' => max(0, (float) $budget->amount - (float) $used),
                    'utilization' => (float) $budget->amount > 0 ? round(((float) $used / (float) $budget->amount) * 100, 1) : 0,
                ];
            });

        return Inertia::render('budgets/index', [
            'budgets' => $budgets,
            'owners' => User::query()->where('active', true)->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(AnnualBudgetRequest $request): RedirectResponse
    {
        AnnualBudget::query()->create($request->validated());

        return back()->with('success', 'Orçamento criado.');
    }

    public function update(AnnualBudgetRequest $request, AnnualBudget $budget): RedirectResponse
    {
        $budget->update($request->validated());

        return back()->with('success', 'Orçamento atualizado.');
    }

    public function destroy(AnnualBudget $budget): RedirectResponse
    {
        if ($budget->trainings()->exists()) {
            return back()->withErrors(['budget' => 'Este orçamento possui treinamentos vinculados.']);
        }

        $budget->delete();

        return back()->with('success', 'Orçamento removido.');
    }
}
