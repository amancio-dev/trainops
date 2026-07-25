import { Head, router, useForm, usePage } from '@inertiajs/react';
import {
    AlertTriangle,
    CircleDollarSign,
    Pencil,
    Plus,
    Trash2,
    TrendingDown,
} from 'lucide-react';
import type { FormEvent } from 'react';
import { useState } from 'react';
import { FieldError } from '@/components/field-error';
import { PageHeader } from '@/components/page-header';
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

type Budget = {
    id: number;
    year: number;
    amount: string;
    owner_id: number | null;
    owner: { id: number; name: string } | null;
    used: number;
    remaining: number;
    utilization: number;
};

type Props = {
    budgets: Budget[];
    owners: Array<{ id: number; name: string }>;
};

const money = new Intl.NumberFormat('pt-BR', {
    style: 'currency',
    currency: 'BRL',
});

export default function BudgetsIndex({ budgets, owners }: Props) {
    const { auth } = usePage().props;
    const [editing, setEditing] = useState<Budget | null>(null);
    const [open, setOpen] = useState(false);
    const form = useForm({
        year: new Date().getFullYear().toString(),
        amount: '',
        owner_id: '',
    });

    function openCreate() {
        setEditing(null);
        form.setData({
            year: new Date().getFullYear().toString(),
            amount: '',
            owner_id: '',
        });
        form.clearErrors();
        setOpen(true);
    }

    function openEdit(budget: Budget) {
        setEditing(budget);
        form.setData({
            year: budget.year.toString(),
            amount: budget.amount,
            owner_id: budget.owner_id?.toString() ?? '',
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
            form.put(`/budgets/${editing.id}`, options);
        } else {
            form.post('/budgets', options);
        }
    }

    return (
        <>
            <Head title="Orçamentos" />
            <div className="flex flex-1 flex-col gap-6 p-4 md:p-6">
                <PageHeader
                    eyebrow="Financeiro"
                    title="Orçamentos anuais"
                    description="Controle o previsto, o comprometido e o saldo disponível para capacitações."
                    actions={
                        auth.permissions.manage && (
                            <Button onClick={openCreate}>
                                <Plus className="size-4" /> Novo orçamento
                            </Button>
                        )
                    }
                />

                <div className="grid gap-4 lg:grid-cols-2 xl:grid-cols-3">
                    {budgets.map((budget) => {
                        const warning = budget.utilization >= 80;

                        return (
                            <article
                                key={budget.id}
                                className="rounded-2xl border bg-card p-5 shadow-sm"
                            >
                                <div className="flex items-start justify-between">
                                    <div className="flex items-center gap-3">
                                        <span className="rounded-xl bg-primary/10 p-2.5 text-primary">
                                            <CircleDollarSign className="size-5" />
                                        </span>
                                        <div>
                                            <p className="text-sm text-muted-foreground">
                                                Exercício
                                            </p>
                                            <h2 className="text-xl font-semibold">
                                                {budget.year}
                                            </h2>
                                        </div>
                                    </div>
                                    {auth.permissions.manage && (
                                        <div className="flex">
                                            <Button
                                                variant="ghost"
                                                size="icon"
                                                onClick={() => openEdit(budget)}
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
                                                            `Remover o orçamento de ${budget.year}?`,
                                                        )
                                                    ) {
                                                        router.delete(
                                                            `/budgets/${budget.id}`,
                                                        );
                                                    }
                                                }}
                                            >
                                                <Trash2 className="size-4" />
                                            </Button>
                                        </div>
                                    )}
                                </div>

                                <dl className="mt-6 grid grid-cols-2 gap-4">
                                    <div>
                                        <dt className="text-xs text-muted-foreground">
                                            Planejado
                                        </dt>
                                        <dd className="mt-1 font-semibold">
                                            {money.format(
                                                Number(budget.amount),
                                            )}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt className="text-xs text-muted-foreground">
                                            Comprometido
                                        </dt>
                                        <dd className="mt-1 font-semibold">
                                            {money.format(budget.used)}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt className="text-xs text-muted-foreground">
                                            Disponível
                                        </dt>
                                        <dd className="mt-1 font-semibold text-primary">
                                            {money.format(budget.remaining)}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt className="text-xs text-muted-foreground">
                                            Responsável
                                        </dt>
                                        <dd className="mt-1 truncate text-sm font-medium">
                                            {budget.owner?.name ??
                                                'Não definido'}
                                        </dd>
                                    </div>
                                </dl>

                                <div className="mt-5">
                                    <div className="mb-2 flex items-center justify-between text-xs">
                                        <span className="text-muted-foreground">
                                            Utilização
                                        </span>
                                        <span
                                            className={
                                                warning
                                                    ? 'font-semibold text-amber-600'
                                                    : 'font-semibold'
                                            }
                                        >
                                            {budget.utilization}%
                                        </span>
                                    </div>
                                    <div className="h-2 overflow-hidden rounded-full bg-muted">
                                        <div
                                            className={`h-full rounded-full ${warning ? 'bg-amber-500' : 'bg-primary'}`}
                                            style={{
                                                width: `${Math.min(budget.utilization, 100)}%`,
                                            }}
                                        />
                                    </div>
                                    {warning && (
                                        <p className="mt-3 flex items-center gap-2 text-xs text-amber-700 dark:text-amber-300">
                                            <AlertTriangle className="size-4" />
                                            Atenção: limite de 80% atingido.
                                        </p>
                                    )}
                                </div>
                            </article>
                        );
                    })}
                    {budgets.length === 0 && (
                        <div className="col-span-full rounded-2xl border border-dashed p-12 text-center">
                            <TrendingDown className="mx-auto size-8 text-muted-foreground" />
                            <p className="mt-3 font-medium">
                                Nenhum orçamento cadastrado
                            </p>
                            <p className="text-sm text-muted-foreground">
                                Cadastre o orçamento anual para ativar os
                                indicadores.
                            </p>
                        </div>
                    )}
                </div>
            </div>

            <Dialog open={open} onOpenChange={setOpen}>
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>
                            {editing ? 'Editar orçamento' : 'Novo orçamento'}
                        </DialogTitle>
                        <DialogDescription>
                            Um orçamento por exercício. Os custos são calculados
                            pelos treinamentos aprovados.
                        </DialogDescription>
                    </DialogHeader>
                    <form onSubmit={submit} className="space-y-4">
                        <div className="grid gap-4 sm:grid-cols-2">
                            <div>
                                <Label htmlFor="budget-year">Ano</Label>
                                <Input
                                    id="budget-year"
                                    type="number"
                                    min="2020"
                                    value={form.data.year}
                                    onChange={(event) =>
                                        form.setData('year', event.target.value)
                                    }
                                />
                                <FieldError message={form.errors.year} />
                            </div>
                            <div>
                                <Label htmlFor="budget-amount">
                                    Valor total
                                </Label>
                                <Input
                                    id="budget-amount"
                                    type="number"
                                    min="0"
                                    step="0.01"
                                    value={form.data.amount}
                                    onChange={(event) =>
                                        form.setData(
                                            'amount',
                                            event.target.value,
                                        )
                                    }
                                />
                                <FieldError message={form.errors.amount} />
                            </div>
                        </div>
                        <div>
                            <Label htmlFor="budget-owner">Responsável</Label>
                            <select
                                id="budget-owner"
                                value={form.data.owner_id}
                                onChange={(event) =>
                                    form.setData('owner_id', event.target.value)
                                }
                                className="mt-1 h-9 w-full rounded-md border bg-background px-3 text-sm"
                            >
                                <option value="">Não definido</option>
                                {owners.map((owner) => (
                                    <option key={owner.id} value={owner.id}>
                                        {owner.name}
                                    </option>
                                ))}
                            </select>
                            <FieldError message={form.errors.owner_id} />
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
                                {form.processing ? 'Salvando…' : 'Salvar'}
                            </Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>
        </>
    );
}

BudgetsIndex.layout = {
    breadcrumbs: [
        { title: 'Visão geral', href: '/dashboard' },
        { title: 'Orçamentos', href: '/budgets' },
    ],
};
