<?php

namespace App\Http\Controllers;

use App\Models\JobPosition;
use App\Models\TrainingType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class ReferenceDataController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('references/index', [
            'jobPositions' => JobPosition::query()->withCount('users')->orderBy('name')->get(),
            'trainingTypes' => TrainingType::query()->withCount('courses')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request, string $kind): RedirectResponse
    {
        $model = $this->model($kind);
        $model::query()->create($this->validated($request, $model));

        return back()->with('success', 'Cadastro criado.');
    }

    public function update(Request $request, string $kind, int $id): RedirectResponse
    {
        $model = $this->model($kind);
        $record = $model::query()->findOrFail($id);
        $record->update($this->validated($request, $model, $record));

        return back()->with('success', 'Cadastro atualizado.');
    }

    public function destroy(string $kind, int $id): RedirectResponse
    {
        $model = $this->model($kind);
        $record = $model::query()->findOrFail($id);
        $relation = $record instanceof JobPosition ? 'users' : 'courses';

        if ($record->{$relation}()->exists()) {
            return back()->withErrors(['reference' => 'Este item está em uso. Desative-o em vez de excluir.']);
        }

        $record->delete();

        return back()->with('success', 'Cadastro removido.');
    }

    /**
     * @return class-string<JobPosition|TrainingType>
     */
    private function model(string $kind): string
    {
        return match ($kind) {
            'job-positions' => JobPosition::class,
            'training-types' => TrainingType::class,
            default => abort(404),
        };
    }

    private function validated(Request $request, string $model, ?Model $record = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120', Rule::unique((new $model)->getTable())->ignore($record)],
            'description' => ['nullable', 'string', 'max:2000'],
            'active' => ['required', 'boolean'],
        ]);
    }
}
