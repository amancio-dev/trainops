<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\EmployeeRequest;
use App\Models\JobPosition;
use App\Models\Training;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class EmployeeController extends Controller
{
    public function index(): Response
    {
        $search = request()->string('search')->trim()->limit(100)->toString();

        return Inertia::render('employees/index', [
            'employees' => User::query()
                ->with('jobPosition:id,name')
                ->when($search, fn ($query) => $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('department', 'like', "%{$search}%");
                }))
                ->orderBy('name')
                ->paginate(15)
                ->withQueryString(),
            'jobPositions' => JobPosition::query()->where('active', true)->orderBy('name')->get(['id', 'name']),
            'filters' => ['search' => $search],
        ]);
    }

    public function store(EmployeeRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        User::query()->create($data);

        return back()->with('success', 'Colaborador criado com segurança.');
    }

    public function update(EmployeeRequest $request, User $employee): RedirectResponse
    {
        $data = $request->validated();

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        if ($employee->is($request->user()) && ($data['active'] === false || $data['role'] !== UserRole::Admin->value)) {
            return back()->withErrors(['role' => 'Você não pode remover o próprio acesso administrativo.']);
        }

        $employee->update($data);

        return back()->with('success', 'Colaborador atualizado.');
    }

    public function destroy(User $employee): RedirectResponse
    {
        abort_if($employee->is(request()->user()), 422, 'Você não pode excluir a própria conta.');

        if ($employee->role === UserRole::Admin && User::query()->where('role', UserRole::Admin)->where('active', true)->count() <= 1) {
            return back()->withErrors(['employee' => 'O sistema precisa manter ao menos um administrador ativo.']);
        }

        if (Training::query()
            ->where('employee_id', $employee->id)
            ->orWhere('created_by', $employee->id)
            ->exists()) {
            return back()->withErrors([
                'employee' => 'Este colaborador possui treinamentos vinculados. Desative a conta em vez de excluir.',
            ]);
        }

        $employee->delete();

        return back()->with('success', 'Colaborador removido.');
    }
}
