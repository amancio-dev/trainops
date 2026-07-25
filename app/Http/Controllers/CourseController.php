<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use App\Models\Course;
use App\Models\TrainingType;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CourseController extends Controller
{
    public function index(): Response
    {
        $search = request()->string('search')->trim()->limit(100)->toString();

        return Inertia::render('courses/index', [
            'courses' => Course::query()
                ->with('trainingType:id,name')
                ->when($search, fn ($query) => $query->where('name', 'like', "%{$search}%"))
                ->orderBy('name')
                ->paginate(15)
                ->withQueryString(),
            'trainingTypes' => TrainingType::query()->where('active', true)->orderBy('name')->get(['id', 'name']),
            'filters' => ['search' => $search],
        ]);
    }

    public function store(CourseRequest $request): RedirectResponse
    {
        Course::query()->create($request->validated());

        return back()->with('success', 'Curso criado.');
    }

    public function update(CourseRequest $request, Course $course): RedirectResponse
    {
        $course->update($request->validated());

        return back()->with('success', 'Curso atualizado.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        if ($course->trainings()->exists()) {
            return back()->withErrors(['course' => 'Desative o curso: ele já possui treinamentos vinculados.']);
        }

        $course->delete();

        return back()->with('success', 'Curso removido.');
    }
}
