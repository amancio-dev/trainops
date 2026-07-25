import { Head, router, useForm, usePage } from '@inertiajs/react';
import {
    CalendarDays,
    Download,
    Pencil,
    Plus,
    Search,
    Trash2,
} from 'lucide-react';
import type { FormEvent } from 'react';
import { useState } from 'react';
import { FieldError } from '@/components/field-error';
import { PageHeader } from '@/components/page-header';
import { Pagination } from '@/components/pagination';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

type Status =
    'planned' | 'approved' | 'in_progress' | 'completed' | 'cancelled';

type Training = {
    id: number;
    employee_id: number;
    course_id: number;
    annual_budget_id: number | null;
    institution: string;
    starts_at: string;
    ends_at: string;
    status: Status;
    registration_cost: string;
    lodging_cost: string;
    transport_cost: string;
    transfer_cost: string;
    daily_allowance_cost: string;
    notes: string | null;
    cancellation_reason: string | null;
    total_cost: number;
    employee: { id: number; name: string };
    course: { id: number; name: string };
    annual_budget: { id: number; year: number } | null;
};

type Props = {
    trainings: {
        data: Training[];
        links: Array<{ url: string | null; label: string; active: boolean }>;
        total: number;
    };
    employees: Array<{ id: number; name: string }>;
    courses: Array<{ id: number; name: string }>;
    budgets: Array<{ id: number; year: number; amount: string }>;
    statuses: Status[];
    filters: { search: string; status: string };
};

const statusLabels: Record<Status, string> = {
    planned: 'Planejado',
    approved: 'Aprovado',
    in_progress: 'Em andamento',
    completed: 'Concluído',
    cancelled: 'Cancelado',
};

const statusStyles: Record<Status, string> = {
    planned: 'bg-slate-500/10 text-slate-700 dark:text-slate-300',
    approved: 'bg-blue-500/10 text-blue-700 dark:text-blue-300',
    in_progress: 'bg-amber-500/10 text-amber-700 dark:text-amber-300',
    completed: 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-300',
    cancelled: 'bg-red-500/10 text-red-700 dark:text-red-300',
};

const money = new Intl.NumberFormat('pt-BR', {
    style: 'currency',
    currency: 'BRL',
});
const emptyForm = {
    employee_id: '',
    course_id: '',
    annual_budget_id: '',
    institution: '',
    starts_at: '',
    ends_at: '',
    status: 'planned' as Status,
    registration_cost: '0',
    lodging_cost: '0',
    transport_cost: '0',
    transfer_cost: '0',
    daily_allowance_cost: '0',
    notes: '',
    cancellation_reason: '',
};

export default function TrainingsIndex({
    trainings,
    employees,
    courses,
    budgets,
    statuses,
    filters,
}: Props) {
    const { auth } = usePage().props;
    const [search, setSearch] = useState(filters.search);
    const [statusFilter, setStatusFilter] = useState(filters.status);
    const [editing, setEditing] = useState<Training | null>(null);
    const [open, setOpen] = useState(false);
    const form = useForm(emptyForm);

    function openCreate() {
        setEditing(null);
        form.setData({
            ...emptyForm,
            employee_id: employees[0]?.id.toString() ?? '',
            course_id: courses[0]?.id.toString() ?? '',
            annual_budget_id: budgets[0]?.id.toString() ?? '',
        });
        form.clearErrors();
        setOpen(true);
    }

    function openEdit(training: Training) {
        setEditing(training);
        form.setData({
            employee_id: training.employee_id.toString(),
            course_id: training.course_id.toString(),
            annual_budget_id: training.annual_budget_id?.toString() ?? '',
            institution: training.institution,
            starts_at: training.starts_at,
            ends_at: training.ends_at,
            status: training.status,
            registration_cost: training.registration_cost,
            lodging_cost: training.lodging_cost,
            transport_cost: training.transport_cost,
            transfer_cost: training.transfer_cost,
            daily_allowance_cost: training.daily_allowance_cost,
            notes: training.notes ?? '',
            cancellation_reason: training.cancellation_reason ?? '',
        });
        form.clearErrors();
        setOpen(true);
    }

    function submit(event: FormEvent) {
        event.preventDefault();
        const options = {
            preserveScroll: true,
            onSuccess: () => setOpen(false),
        };

        if (editing) {
            form.put(`/trainings/${editing.id}`, options);
        } else {
            form.post('/trainings', options);
        }
    }

    function applyFilters(nextStatus = statusFilter) {
        router.get(
            '/trainings',
            { search, status: nextStatus },
            { preserveState: true, replace: true },
        );
    }

    const exportQuery = new URLSearchParams({
        ...(search ? { search } : {}),
        ...(statusFilter ? { status: statusFilter } : {}),
    }).toString();

    return (
        <>
            <Head title="Treinamentos" />
            <div className="flex flex-1 flex-col gap-6 p-4 md:p-6">
                <PageHeader
                    eyebrow="Operação"
                    title="Treinamentos"
                    description="Planeje, aprove e acompanhe cada capacitação até sua conclusão."
                    actions={
                        <>
                            <Button variant="outline" asChild>
                                <a
                                    href={`/trainings/export${exportQuery ? `?${exportQuery}` : ''}`}
                                >
                                    <Download className="size-4" /> Exportar CSV
                                </a>
                            </Button>
                            {auth.permissions.manage && (
                                <Button onClick={openCreate}>
                                    <Plus className="size-4" /> Novo treinamento
                                </Button>
                            )}
                        </>
                    }
                />

                <section className="overflow-hidden rounded-2xl border bg-card shadow-sm">
                    <div className="flex flex-col gap-3 border-b p-4 lg:flex-row lg:items-center lg:justify-between">
                        <form
                            className="relative w-full max-w-md"
                            onSubmit={(event) => {
                                event.preventDefault();
                                applyFilters();
                            }}
                        >
                            <Search className="absolute top-1/2 left-3 size-4 -translate-y-1/2 text-muted-foreground" />
                            <Input
                                value={search}
                                onChange={(event) =>
                                    setSearch(event.target.value)
                                }
                                placeholder="Buscar colaborador, curso ou instituição"
                                className="pl-9"
                            />
                        </form>
                        <div className="flex flex-wrap gap-1.5">
                            <button
                                type="button"
                                onClick={() => {
                                    setStatusFilter('');
                                    applyFilters('');
                                }}
                                className={`rounded-full px-3 py-1.5 text-xs font-medium transition ${
                                    !statusFilter
                                        ? 'bg-primary text-primary-foreground'
                                        : 'bg-muted hover:bg-muted/70'
                                }`}
                            >
                                Todos
                            </button>
                            {statuses.map((status) => (
                                <button
                                    key={status}
                                    type="button"
                                    onClick={() => {
                                        setStatusFilter(status);
                                        applyFilters(status);
                                    }}
                                    className={`rounded-full px-3 py-1.5 text-xs font-medium transition ${
                                        statusFilter === status
                                            ? 'bg-primary text-primary-foreground'
                                            : 'bg-muted hover:bg-muted/70'
                                    }`}
                                >
                                    {statusLabels[status]}
                                </button>
                            ))}
                        </div>
                    </div>

                    <div className="overflow-x-auto">
                        <table className="w-full min-w-[900px] text-sm">
                            <thead className="bg-muted/45 text-left text-xs tracking-wide text-muted-foreground uppercase">
                                <tr>
                                    <th className="px-4 py-3 font-medium">
                                        Treinamento
                                    </th>
                                    <th className="px-4 py-3 font-medium">
                                        Colaborador
                                    </th>
                                    <th className="px-4 py-3 font-medium">
                                        Período
                                    </th>
                                    <th className="px-4 py-3 font-medium">
                                        Status
                                    </th>
                                    <th className="px-4 py-3 text-right font-medium">
                                        Investimento
                                    </th>
                                    <th className="px-4 py-3 text-right font-medium">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody className="divide-y">
                                {trainings.data.map((training) => (
                                    <tr
                                        key={training.id}
                                        className="transition hover:bg-muted/25"
                                    >
                                        <td className="px-4 py-3">
                                            <p className="font-medium">
                                                {training.course.name}
                                            </p>
                                            <p className="text-xs text-muted-foreground">
                                                {training.institution}
                                                {training.annual_budget &&
                                                    ` · orçamento ${training.annual_budget.year}`}
                                            </p>
                                        </td>
                                        <td className="px-4 py-3">
                                            {training.employee.name}
                                        </td>
                                        <td className="px-4 py-3">
                                            <div className="flex items-center gap-2">
                                                <CalendarDays className="size-4 text-muted-foreground" />
                                                <span>
                                                    {new Date(
                                                        `${training.starts_at}T12:00:00`,
                                                    ).toLocaleDateString(
                                                        'pt-BR',
                                                    )}
                                                    {' – '}
                                                    {new Date(
                                                        `${training.ends_at}T12:00:00`,
                                                    ).toLocaleDateString(
                                                        'pt-BR',
                                                    )}
                                                </span>
                                            </div>
                                        </td>
                                        <td className="px-4 py-3">
                                            <span
                                                className={`rounded-full px-2.5 py-1 text-xs font-medium ${statusStyles[training.status]}`}
                                            >
                                                {statusLabels[training.status]}
                                            </span>
                                        </td>
                                        <td className="px-4 py-3 text-right font-medium">
                                            {money.format(training.total_cost)}
                                        </td>
                                        <td className="px-4 py-3">
                                            {auth.permissions.manage && (
                                                <div className="flex justify-end gap-1">
                                                    <Button
                                                        variant="ghost"
                                                        size="icon"
                                                        onClick={() =>
                                                            openEdit(training)
                                                        }
                                                    >
                                                        <Pencil className="size-4" />
                                                    </Button>
                                                    <Button
                                                        variant="ghost"
                                                        size="icon"
                                                        className="text-destructive hover:text-destructive"
                                                        onClick={() => {
                                                            if (
                                                                confirm(
                                                                    `Remover o treinamento ${training.course.name}?`,
                                                                )
                                                            ) {
                                                                router.delete(
                                                                    `/trainings/${training.id}`,
                                                                    {
                                                                        preserveScroll: true,
                                                                    },
                                                                );
                                                            }
                                                        }}
                                                    >
                                                        <Trash2 className="size-4" />
                                                    </Button>
                                                </div>
                                            )}
                                        </td>
                                    </tr>
                                ))}
                                {trainings.data.length === 0 && (
                                    <tr>
                                        <td
                                            colSpan={6}
                                            className="px-4 py-12 text-center text-muted-foreground"
                                        >
                                            Nenhum treinamento encontrado.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                    <Pagination links={trainings.links} />
                </section>
            </div>

            <Dialog open={open} onOpenChange={setOpen}>
                <DialogContent className="max-h-[92vh] overflow-y-auto sm:max-w-3xl">
                    <DialogHeader>
                        <DialogTitle>
                            {editing
                                ? 'Editar treinamento'
                                : 'Novo treinamento'}
                        </DialogTitle>
                        <DialogDescription>
                            Registre agenda, participantes e todos os
                            componentes do investimento.
                        </DialogDescription>
                    </DialogHeader>
                    <form onSubmit={submit} className="space-y-5">
                        <div className="grid gap-4 sm:grid-cols-2">
                            <div>
                                <Label htmlFor="training-employee">
                                    Colaborador
                                </Label>
                                <select
                                    id="training-employee"
                                    value={form.data.employee_id}
                                    onChange={(event) =>
                                        form.setData(
                                            'employee_id',
                                            event.target.value,
                                        )
                                    }
                                    className="mt-1 h-9 w-full rounded-md border bg-background px-3 text-sm"
                                >
                                    {employees.map((employee) => (
                                        <option
                                            key={employee.id}
                                            value={employee.id}
                                        >
                                            {employee.name}
                                        </option>
                                    ))}
                                </select>
                                <FieldError message={form.errors.employee_id} />
                            </div>
                            <div>
                                <Label htmlFor="training-course">Curso</Label>
                                <select
                                    id="training-course"
                                    value={form.data.course_id}
                                    onChange={(event) =>
                                        form.setData(
                                            'course_id',
                                            event.target.value,
                                        )
                                    }
                                    className="mt-1 h-9 w-full rounded-md border bg-background px-3 text-sm"
                                >
                                    {courses.map((course) => (
                                        <option
                                            key={course.id}
                                            value={course.id}
                                        >
                                            {course.name}
                                        </option>
                                    ))}
                                </select>
                                <FieldError message={form.errors.course_id} />
                            </div>
                        </div>
                        <div className="grid gap-4 sm:grid-cols-2">
                            <div>
                                <Label htmlFor="training-institution">
                                    Instituição
                                </Label>
                                <Input
                                    id="training-institution"
                                    value={form.data.institution}
                                    onChange={(event) =>
                                        form.setData(
                                            'institution',
                                            event.target.value,
                                        )
                                    }
                                />
                                <FieldError message={form.errors.institution} />
                            </div>
                            <div>
                                <Label htmlFor="training-budget">
                                    Orçamento
                                </Label>
                                <select
                                    id="training-budget"
                                    value={form.data.annual_budget_id}
                                    onChange={(event) =>
                                        form.setData(
                                            'annual_budget_id',
                                            event.target.value,
                                        )
                                    }
                                    className="mt-1 h-9 w-full rounded-md border bg-background px-3 text-sm"
                                >
                                    <option value="">Sem orçamento</option>
                                    {budgets.map((budget) => (
                                        <option
                                            key={budget.id}
                                            value={budget.id}
                                        >
                                            {budget.year} ·{' '}
                                            {money.format(
                                                Number(budget.amount),
                                            )}
                                        </option>
                                    ))}
                                </select>
                                <FieldError
                                    message={form.errors.annual_budget_id}
                                />
                            </div>
                        </div>
                        <div className="grid gap-4 sm:grid-cols-3">
                            <div>
                                <Label htmlFor="training-start">Início</Label>
                                <Input
                                    id="training-start"
                                    type="date"
                                    value={form.data.starts_at}
                                    onChange={(event) =>
                                        form.setData(
                                            'starts_at',
                                            event.target.value,
                                        )
                                    }
                                />
                                <FieldError message={form.errors.starts_at} />
                            </div>
                            <div>
                                <Label htmlFor="training-end">Fim</Label>
                                <Input
                                    id="training-end"
                                    type="date"
                                    value={form.data.ends_at}
                                    onChange={(event) =>
                                        form.setData(
                                            'ends_at',
                                            event.target.value,
                                        )
                                    }
                                />
                                <FieldError message={form.errors.ends_at} />
                            </div>
                            <div>
                                <Label htmlFor="training-status">Status</Label>
                                <select
                                    id="training-status"
                                    value={form.data.status}
                                    onChange={(event) =>
                                        form.setData(
                                            'status',
                                            event.target.value as Status,
                                        )
                                    }
                                    className="mt-1 h-9 w-full rounded-md border bg-background px-3 text-sm"
                                >
                                    {statuses.map((status) => (
                                        <option key={status} value={status}>
                                            {statusLabels[status]}
                                        </option>
                                    ))}
                                </select>
                                <FieldError message={form.errors.status} />
                            </div>
                        </div>

                        <fieldset>
                            <legend className="text-sm font-semibold">
                                Composição do investimento
                            </legend>
                            <div className="mt-2 grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
                                {[
                                    ['registration_cost', 'Inscrição'],
                                    ['lodging_cost', 'Hospedagem'],
                                    ['transport_cost', 'Passagem'],
                                    ['transfer_cost', 'Translado'],
                                    ['daily_allowance_cost', 'Diárias'],
                                ].map(([field, label]) => (
                                    <div key={field}>
                                        <Label htmlFor={`training-${field}`}>
                                            {label}
                                        </Label>
                                        <Input
                                            id={`training-${field}`}
                                            type="number"
                                            min="0"
                                            step="0.01"
                                            value={
                                                form.data[
                                                    field as keyof typeof emptyForm
                                                ]
                                            }
                                            onChange={(event) =>
                                                form.setData(
                                                    field as 'registration_cost',
                                                    event.target.value,
                                                )
                                            }
                                        />
                                        <FieldError
                                            message={
                                                form.errors[
                                                    field as keyof typeof form.errors
                                                ]
                                            }
                                        />
                                    </div>
                                ))}
                            </div>
                        </fieldset>

                        {form.data.status === 'cancelled' && (
                            <div>
                                <Label htmlFor="training-cancellation">
                                    Motivo do cancelamento
                                </Label>
                                <textarea
                                    id="training-cancellation"
                                    rows={3}
                                    value={form.data.cancellation_reason}
                                    onChange={(event) =>
                                        form.setData(
                                            'cancellation_reason',
                                            event.target.value,
                                        )
                                    }
                                    className="mt-1 w-full rounded-md border bg-background px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-ring/50"
                                />
                                <FieldError
                                    message={form.errors.cancellation_reason}
                                />
                            </div>
                        )}
                        <div>
                            <Label htmlFor="training-notes">Observações</Label>
                            <textarea
                                id="training-notes"
                                rows={3}
                                value={form.data.notes}
                                onChange={(event) =>
                                    form.setData('notes', event.target.value)
                                }
                                className="mt-1 w-full rounded-md border bg-background px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-ring/50"
                            />
                            <FieldError message={form.errors.notes} />
                        </div>
                        <DialogFooter>
                            <Button
                                type="button"
                                variant="outline"
                                onClick={() => setOpen(false)}
                            >
                                Cancelar
                            </Button>
                            <Button type="submit" disabled={form.processing}>
                                {form.processing
                                    ? 'Salvando…'
                                    : 'Salvar treinamento'}
                            </Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>
        </>
    );
}

TrainingsIndex.layout = {
    breadcrumbs: [
        { title: 'Visão geral', href: '/dashboard' },
        { title: 'Treinamentos', href: '/trainings' },
    ],
};
