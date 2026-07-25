import { Head, router, useForm, usePage } from '@inertiajs/react';
import {
    Pencil,
    Plus,
    Search,
    Shield,
    Trash2,
    UserRoundCheck,
} from 'lucide-react';
import type { FormEvent} from 'react';
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

type Employee = {
    id: number;
    name: string;
    email: string;
    department: string | null;
    role: 'admin' | 'manager' | 'viewer';
    active: boolean;
    job_position_id: number | null;
    job_position: { id: number; name: string } | null;
};

type Props = {
    employees: {
        data: Employee[];
        links: Array<{ url: string | null; label: string; active: boolean }>;
        total: number;
    };
    jobPositions: Array<{ id: number; name: string }>;
    filters: { search: string };
};

const roleLabels = {
    admin: 'Administrador',
    manager: 'Gestor',
    viewer: 'Consulta',
};

export default function EmployeesIndex({
    employees,
    jobPositions,
    filters,
}: Props) {
    const { auth } = usePage().props;
    const [search, setSearch] = useState(filters.search);
    const [editing, setEditing] = useState<Employee | null>(null);
    const [open, setOpen] = useState(false);
    const form = useForm({
        name: '',
        email: '',
        department: '',
        job_position_id: '',
        role: 'viewer' as Employee['role'],
        active: true,
        password: '',
    });

    function openCreate() {
        setEditing(null);
        form.setData({
            name: '',
            email: '',
            department: '',
            job_position_id: '',
            role: 'viewer',
            active: true,
            password: '',
        });
        form.clearErrors();
        setOpen(true);
    }

    function openEdit(employee: Employee) {
        setEditing(employee);
        form.setData({
            name: employee.name,
            email: employee.email,
            department: employee.department ?? '',
            job_position_id: employee.job_position_id?.toString() ?? '',
            role: employee.role,
            active: employee.active,
            password: '',
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
            form.put(`/employees/${editing.id}`, options);
        } else {
            form.post('/employees', options);
        }
    }

    function remove(employee: Employee) {
        if (
            !confirm(
                `Remover ${employee.name}? Esta ação ficará registrada na auditoria.`,
            )
        ) {
return;
}

        router.delete(`/employees/${employee.id}`, { preserveScroll: true });
    }

    return (
        <>
            <Head title="Colaboradores" />
            <div className="flex flex-1 flex-col gap-6 p-4 md:p-6">
                <PageHeader
                    eyebrow="Pessoas"
                    title="Colaboradores"
                    description={`${employees.total} pessoas cadastradas, com acesso e responsabilidades centralizados.`}
                    actions={
                        auth.permissions.admin && (
                            <Button onClick={openCreate}>
                                <Plus className="size-4" /> Novo colaborador
                            </Button>
                        )
                    }
                />

                <section className="overflow-hidden rounded-2xl border bg-card shadow-sm">
                    <div className="flex flex-col gap-3 border-b p-4 sm:flex-row sm:items-center sm:justify-between">
                        <form
                            className="relative w-full max-w-md"
                            onSubmit={(event) => {
                                event.preventDefault();
                                router.get(
                                    '/employees',
                                    { search },
                                    { preserveState: true, replace: true },
                                );
                            }}
                        >
                            <Search className="absolute top-1/2 left-3 size-4 -translate-y-1/2 text-muted-foreground" />
                            <Input
                                value={search}
                                onChange={(event) =>
                                    setSearch(event.target.value)
                                }
                                placeholder="Buscar por nome, e-mail ou setor"
                                className="pl-9"
                            />
                        </form>
                        <span className="text-xs text-muted-foreground">
                            Senhas nunca são exibidas ou exportadas
                        </span>
                    </div>

                    <div className="overflow-x-auto">
                        <table className="w-full text-sm">
                            <thead className="bg-muted/45 text-left text-xs tracking-wide text-muted-foreground uppercase">
                                <tr>
                                    <th className="px-4 py-3 font-medium">
                                        Colaborador
                                    </th>
                                    <th className="px-4 py-3 font-medium">
                                        Cargo / setor
                                    </th>
                                    <th className="px-4 py-3 font-medium">
                                        Acesso
                                    </th>
                                    <th className="px-4 py-3 font-medium">
                                        Status
                                    </th>
                                    <th className="px-4 py-3 text-right font-medium">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody className="divide-y">
                                {employees.data.map((employee) => (
                                    <tr
                                        key={employee.id}
                                        className="transition hover:bg-muted/25"
                                    >
                                        <td className="px-4 py-3">
                                            <div className="flex items-center gap-3">
                                                <span className="grid size-9 shrink-0 place-items-center rounded-full bg-primary/10 font-semibold text-primary">
                                                    {employee.name
                                                        .slice(0, 2)
                                                        .toUpperCase()}
                                                </span>
                                                <div className="min-w-0">
                                                    <p className="truncate font-medium">
                                                        {employee.name}
                                                    </p>
                                                    <p className="truncate text-xs text-muted-foreground">
                                                        {employee.email}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td className="px-4 py-3">
                                            <p>
                                                {employee.job_position?.name ??
                                                    'Não informado'}
                                            </p>
                                            <p className="text-xs text-muted-foreground">
                                                {employee.department ??
                                                    'Sem setor'}
                                            </p>
                                        </td>
                                        <td className="px-4 py-3">
                                            <span className="inline-flex items-center gap-1.5 rounded-full bg-blue-500/10 px-2.5 py-1 text-xs font-medium text-blue-700 dark:text-blue-300">
                                                <Shield className="size-3" />
                                                {roleLabels[employee.role]}
                                            </span>
                                        </td>
                                        <td className="px-4 py-3">
                                            <span
                                                className={`inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium ${
                                                    employee.active
                                                        ? 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-300'
                                                        : 'bg-zinc-500/10 text-zinc-600 dark:text-zinc-300'
                                                }`}
                                            >
                                                <UserRoundCheck className="size-3" />
                                                {employee.active
                                                    ? 'Ativo'
                                                    : 'Inativo'}
                                            </span>
                                        </td>
                                        <td className="px-4 py-3">
                                            {auth.permissions.admin && (
                                                <div className="flex justify-end gap-1">
                                                    <Button
                                                        variant="ghost"
                                                        size="icon"
                                                        onClick={() =>
                                                            openEdit(employee)
                                                        }
                                                        aria-label={`Editar ${employee.name}`}
                                                    >
                                                        <Pencil className="size-4" />
                                                    </Button>
                                                    <Button
                                                        variant="ghost"
                                                        size="icon"
                                                        onClick={() =>
                                                            remove(employee)
                                                        }
                                                        aria-label={`Remover ${employee.name}`}
                                                        className="text-destructive hover:text-destructive"
                                                    >
                                                        <Trash2 className="size-4" />
                                                    </Button>
                                                </div>
                                            )}
                                        </td>
                                    </tr>
                                ))}
                                {employees.data.length === 0 && (
                                    <tr>
                                        <td
                                            colSpan={5}
                                            className="px-4 py-12 text-center text-muted-foreground"
                                        >
                                            Nenhum colaborador encontrado.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                    <Pagination links={employees.links} />
                </section>
            </div>

            <Dialog open={open} onOpenChange={setOpen}>
                <DialogContent className="max-h-[90vh] overflow-y-auto sm:max-w-xl">
                    <DialogHeader>
                        <DialogTitle>
                            {editing
                                ? 'Editar colaborador'
                                : 'Novo colaborador'}
                        </DialogTitle>
                        <DialogDescription>
                            Defina os dados profissionais e o menor nível de
                            acesso necessário.
                        </DialogDescription>
                    </DialogHeader>
                    <form onSubmit={submit} className="space-y-4">
                        <div>
                            <Label htmlFor="employee-name">Nome</Label>
                            <Input
                                id="employee-name"
                                value={form.data.name}
                                onChange={(event) =>
                                    form.setData('name', event.target.value)
                                }
                                autoComplete="name"
                            />
                            <FieldError message={form.errors.name} />
                        </div>
                        <div>
                            <Label htmlFor="employee-email">E-mail</Label>
                            <Input
                                id="employee-email"
                                type="email"
                                value={form.data.email}
                                onChange={(event) =>
                                    form.setData('email', event.target.value)
                                }
                                autoComplete="email"
                            />
                            <FieldError message={form.errors.email} />
                        </div>
                        <div className="grid gap-4 sm:grid-cols-2">
                            <div>
                                <Label htmlFor="employee-position">Cargo</Label>
                                <select
                                    id="employee-position"
                                    value={form.data.job_position_id}
                                    onChange={(event) =>
                                        form.setData(
                                            'job_position_id',
                                            event.target.value,
                                        )
                                    }
                                    className="mt-1 h-9 w-full rounded-md border bg-background px-3 text-sm"
                                >
                                    <option value="">Selecione</option>
                                    {jobPositions.map((position) => (
                                        <option
                                            key={position.id}
                                            value={position.id}
                                        >
                                            {position.name}
                                        </option>
                                    ))}
                                </select>
                                <FieldError
                                    message={form.errors.job_position_id}
                                />
                            </div>
                            <div>
                                <Label htmlFor="employee-department">
                                    Setor
                                </Label>
                                <Input
                                    id="employee-department"
                                    value={form.data.department}
                                    onChange={(event) =>
                                        form.setData(
                                            'department',
                                            event.target.value,
                                        )
                                    }
                                />
                                <FieldError message={form.errors.department} />
                            </div>
                        </div>
                        <div className="grid gap-4 sm:grid-cols-2">
                            <div>
                                <Label htmlFor="employee-role">
                                    Nível de acesso
                                </Label>
                                <select
                                    id="employee-role"
                                    value={form.data.role}
                                    onChange={(event) =>
                                        form.setData(
                                            'role',
                                            event.target
                                                .value as Employee['role'],
                                        )
                                    }
                                    className="mt-1 h-9 w-full rounded-md border bg-background px-3 text-sm"
                                >
                                    <option value="viewer">Consulta</option>
                                    <option value="manager">Gestor</option>
                                    <option value="admin">Administrador</option>
                                </select>
                                <FieldError message={form.errors.role} />
                            </div>
                            <div>
                                <Label htmlFor="employee-password">
                                    {editing
                                        ? 'Nova senha (opcional)'
                                        : 'Senha inicial'}
                                </Label>
                                <Input
                                    id="employee-password"
                                    type="password"
                                    value={form.data.password}
                                    onChange={(event) =>
                                        form.setData(
                                            'password',
                                            event.target.value,
                                        )
                                    }
                                    autoComplete="new-password"
                                />
                                <FieldError message={form.errors.password} />
                            </div>
                        </div>
                        <label className="flex items-center gap-2 text-sm">
                            <input
                                type="checkbox"
                                checked={form.data.active}
                                onChange={(event) =>
                                    form.setData('active', event.target.checked)
                                }
                                className="size-4 rounded border"
                            />
                            Conta ativa
                        </label>
                        <DialogFooter>
                            <Button
                                type="button"
                                variant="outline"
                                onClick={() => setOpen(false)}
                            >
                                Cancelar
                            </Button>
                            <Button type="submit" disabled={form.processing}>
                                {form.processing ? 'Salvando…' : 'Salvar'}
                            </Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>
        </>
    );
}

EmployeesIndex.layout = {
    breadcrumbs: [
        { title: 'Visão geral', href: '/dashboard' },
        { title: 'Colaboradores', href: '/employees' },
    ],
};
