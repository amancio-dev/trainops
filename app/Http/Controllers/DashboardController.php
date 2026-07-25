<?php

namespace App\Http\Controllers;

use App\Enums\TrainingStatus;
use App\Models\AnnualBudget;
use App\Models\Training;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        $year = now()->year;
        $budget = AnnualBudget::query()->where('year', $year)->first();
        $costExpression = 'registration_cost + lodging_cost + transport_cost + transfer_cost + daily_allowance_cost';
        $budgetStatuses = [
            TrainingStatus::Approved->value,
            TrainingStatus::InProgress->value,
            TrainingStatus::Completed->value,
        ];

        $used = (float) Training::query()
            ->whereIn('status', $budgetStatuses)
            ->when($budget, fn ($query) => $query->where('annual_budget_id', $budget->id))
            ->when(! $budget, fn ($query) => $query->whereRaw('1 = 0'))
            ->sum(DB::raw($costExpression));

        $monthly = Training::query()
            ->whereYear('starts_at', $year)
            ->where('status', '!=', TrainingStatus::Cancelled->value)
            ->get()
            ->groupBy(fn (Training $training) => $training->starts_at->month);

        return Inertia::render('dashboard', [
            'metrics' => [
                'employees' => User::query()->where('active', true)->count(),
                'trainings' => Training::query()->whereYear('starts_at', $year)->count(),
                'completed' => Training::query()
                    ->whereYear('starts_at', $year)
                    ->where('status', TrainingStatus::Completed->value)
                    ->count(),
                'budget' => (float) ($budget?->amount ?? 0),
                'used' => $used,
                'remaining' => max(0, (float) ($budget?->amount ?? 0) - $used),
                'utilization' => $budget && (float) $budget->amount > 0
                    ? round(($used / (float) $budget->amount) * 100, 1)
                    : 0,
            ],
            'monthly' => collect(range(1, 12))->map(fn (int $month) => [
                'month' => $month,
                'total' => $monthly->get($month, collect())->count(),
                'cost' => (float) $monthly->get($month, collect())->sum('total_cost'),
            ]),
            'upcoming' => Training::query()
                ->with(['employee:id,name', 'course:id,name'])
                ->whereDate('starts_at', '>=', today())
                ->whereNot('status', TrainingStatus::Cancelled->value)
                ->orderBy('starts_at')
                ->limit(6)
                ->get(),
        ]);
    }
}
