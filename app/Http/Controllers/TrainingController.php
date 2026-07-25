<?php

namespace App\Http\Controllers;

use App\Enums\TrainingStatus;
use App\Http\Requests\TrainingRequest;
use App\Models\AnnualBudget;
use App\Models\Course;
use App\Models\Training;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Inertia\Inertia;
use Inertia\Response;

class TrainingController extends Controller
{
    public function index(): Response
    {
        $search = request()->string('search')->trim()->limit(100)->toString();
        $status = request()->string('status')->toString();

        return Inertia::render('trainings/index', [
            'trainings' => $this->filteredQuery($search, $status)
                ->paginate(15)
                ->withQueryString(),
            'employees' => User::query()->where('active', true)->orderBy('name')->get(['id', 'name']),
            'courses' => Course::query()->where('active', true)->orderBy('name')->get(['id', 'name']),
            'budgets' => AnnualBudget::query()->orderByDesc('year')->get(['id', 'year', 'amount']),
            'statuses' => collect(TrainingStatus::cases())->map(fn (TrainingStatus $status) => $status->value),
            'filters' => ['search' => $search, 'status' => $status],
        ]);
    }

    public function store(TrainingRequest $request): RedirectResponse
    {
        Training::query()->create([
            ...$request->validated(),
            'created_by' => $request->user()->id,
        ]);

        return back()->with('success', 'Treinamento criado.');
    }

    public function update(TrainingRequest $request, Training $training): RedirectResponse
    {
        $training->update($request->validated());

        return back()->with('success', 'Treinamento atualizado.');
    }

    public function destroy(Training $training): RedirectResponse
    {
        $training->delete();

        return back()->with('success', 'Treinamento removido.');
    }

    public function export(): StreamedResponse
    {
        $search = request()->string('search')->trim()->limit(100)->toString();
        $status = request()->string('status')->toString();

        return response()->streamDownload(function () use ($search, $status) {
            $output = fopen('php://output', 'w');
            fputs($output, "\xEF\xBB\xBF");
            fputcsv($output, ['Colaborador', 'Curso', 'Instituição', 'Início', 'Fim', 'Status', 'Custo total'], ';');

            $this->filteredQuery($search, $status)->chunk(500, function ($trainings) use ($output) {
                foreach ($trainings as $training) {
                    fputcsv($output, [
                        $training->employee->name,
                        $training->course->name,
                        $training->institution,
                        $training->starts_at->format('d/m/Y'),
                        $training->ends_at->format('d/m/Y'),
                        $training->status->value,
                        number_format($training->total_cost, 2, ',', '.'),
                    ], ';');
                }
            });

            fclose($output);
        }, 'trainops-treinamentos-'.now()->format('Y-m-d').'.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function filteredQuery(string $search, string $status): Builder
    {
        return Training::query()
            ->with(['employee:id,name', 'course:id,name', 'annualBudget:id,year'])
            ->when($search, fn ($query) => $query->where(function ($query) use ($search) {
                $query->where('institution', 'like', "%{$search}%")
                    ->orWhereHas('employee', fn ($query) => $query->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('course', fn ($query) => $query->where('name', 'like', "%{$search}%"));
            }))
            ->when(in_array($status, array_column(TrainingStatus::cases(), 'value'), true), fn ($query) => $query->where('status', $status))
            ->orderByDesc('starts_at');
    }
}
