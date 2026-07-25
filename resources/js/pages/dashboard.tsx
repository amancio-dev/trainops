import { Head, Link } from '@inertiajs/react';
import {
    ArrowRight,
    CalendarDays,
    CheckCircle2,
    CircleDollarSign,
    TrendingUp,
    Users,
} from 'lucide-react';
import { PageHeader } from '@/components/page-header';

type DashboardProps = {
    metrics: {
        employees: number;
        trainings: number;
        completed: number;
        budget: number;
        used: number;
        remaining: number;
        utilization: number;
    };
    monthly: Array<{ month: number; total: number; cost: number }>;
    upcoming: Array<{
        id: number;
        institution: string;
        starts_at: string;
        employee: { name: string };
        course: { name: string };
    }>;
};

const money = new Intl.NumberFormat('pt-BR', {
    style: 'currency',
    currency: 'BRL',
});

const monthNames = [
    'Jan',
    'Fev',
    'Mar',
    'Abr',
    'Mai',
    'Jun',
    'Jul',
    'Ago',
    'Set',
    'Out',
    'Nov',
    'Dez',
];

export default function Dashboard({
    metrics,
    monthly,
    upcoming,
}: DashboardProps) {
    const maxMonthly = Math.max(...monthly.map((item) => item.total), 1);
    const cards = [
        {
            label: 'Colaboradores ativos',
            value: metrics.employees,
            helper: 'pessoas disponíveis',
            icon: Users,
            tone: 'bg-blue-500/10 text-blue-600 dark:text-blue-400',
        },
        {
            label: 'Treinamentos no ano',
            value: metrics.trainings,
            helper: `${metrics.completed} concluídos`,
            icon: CalendarDays,
            tone: 'bg-violet-500/10 text-violet-600 dark:text-violet-400',
        },
        {
            label: 'Taxa de conclusão',
            value: `${metrics.trainings ? Math.round((metrics.completed / metrics.trainings) * 100) : 0}%`,
            helper: 'do planejamento anual',
            icon: CheckCircle2,
            tone: 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400',
        },
        {
            label: 'Saldo disponível',
            value: money.format(metrics.remaining),
            helper: `${metrics.utilization}% utilizado`,
            icon: CircleDollarSign,
            tone: 'bg-amber-500/10 text-amber-600 dark:text-amber-400',
        },
    ];

    return (
        <>
            <Head title="Visão geral" />
            <div className="flex flex-1 flex-col gap-6 p-4 md:p-6">
                <PageHeader
                    eyebrow="Painel de controle"
                    title="Visão geral do desenvolvimento"
                    description="Acompanhe pessoas, capacitações e orçamento em um único lugar."
                    actions={
                        <Link
                            href="/trainings"
                            className="inline-flex h-9 items-center gap-2 rounded-md bg-primary px-4 text-sm font-medium text-primary-foreground shadow-sm transition hover:bg-primary/90"
                        >
                            Ver treinamentos <ArrowRight className="size-4" />
                        </Link>
                    }
                />

                <div className="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    {cards.map((card) => (
                        <div
                            key={card.label}
                            className="rounded-2xl border bg-card p-5 shadow-sm"
                        >
                            <div className="flex items-start justify-between">
                                <div>
                                    <p className="text-sm text-muted-foreground">
                                        {card.label}
                                    </p>
                                    <p className="mt-2 text-2xl font-semibold tracking-tight">
                                        {card.value}
                                    </p>
                                </div>
                                <span
                                    className={`rounded-xl p-2.5 ${card.tone}`}
                                >
                                    <card.icon className="size-5" />
                                </span>
                            </div>
                            <p className="mt-3 text-xs text-muted-foreground">
                                {card.helper}
                            </p>
                        </div>
                    ))}
                </div>

                <div className="grid gap-4 xl:grid-cols-[1.65fr_1fr]">
                    <section className="rounded-2xl border bg-card p-5 shadow-sm">
                        <div className="flex items-center justify-between">
                            <div>
                                <h2 className="font-semibold">
                                    Ritmo de capacitação
                                </h2>
                                <p className="text-sm text-muted-foreground">
                                    Treinamentos iniciados por mês
                                </p>
                            </div>
                            <span className="rounded-lg bg-primary/10 p-2 text-primary">
                                <TrendingUp className="size-5" />
                            </span>
                        </div>
                        <div className="mt-8 flex h-56 items-end gap-2 sm:gap-3">
                            {monthly.map((item) => (
                                <div
                                    key={item.month}
                                    className="group flex h-full flex-1 flex-col items-center justify-end gap-2"
                                >
                                    <span className="text-xs font-medium opacity-0 transition group-hover:opacity-100">
                                        {item.total}
                                    </span>
                                    <div
                                        className="w-full min-w-2 rounded-t-md bg-primary/75 transition hover:bg-primary"
                                        style={{
                                            height: `${Math.max((item.total / maxMonthly) * 100, item.total ? 8 : 2)}%`,
                                        }}
                                        title={`${item.total} treinamentos · ${money.format(item.cost)}`}
                                    />
                                    <span className="text-[10px] text-muted-foreground sm:text-xs">
                                        {monthNames[item.month - 1]}
                                    </span>
                                </div>
                            ))}
                        </div>
                    </section>

                    <section className="rounded-2xl border bg-card p-5 shadow-sm">
                        <h2 className="font-semibold">Saúde do orçamento</h2>
                        <p className="text-sm text-muted-foreground">
                            Comprometimento do orçamento anual
                        </p>
                        <div className="mt-6 flex items-center gap-5">
                            <div
                                className="grid size-32 shrink-0 place-items-center rounded-full"
                                style={{
                                    background: `conic-gradient(var(--primary) ${Math.min(metrics.utilization, 100)}%, var(--muted) 0)`,
                                }}
                            >
                                <div className="grid size-24 place-items-center rounded-full bg-card">
                                    <span className="text-xl font-semibold">
                                        {metrics.utilization}%
                                    </span>
                                </div>
                            </div>
                            <dl className="min-w-0 space-y-3 text-sm">
                                <div>
                                    <dt className="text-muted-foreground">
                                        Previsto
                                    </dt>
                                    <dd className="font-medium">
                                        {money.format(metrics.budget)}
                                    </dd>
                                </div>
                                <div>
                                    <dt className="text-muted-foreground">
                                        Utilizado
                                    </dt>
                                    <dd className="font-medium">
                                        {money.format(metrics.used)}
                                    </dd>
                                </div>
                                <div>
                                    <dt className="text-muted-foreground">
                                        Disponível
                                    </dt>
                                    <dd className="font-medium text-primary">
                                        {money.format(metrics.remaining)}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </section>
                </div>

                <section className="overflow-hidden rounded-2xl border bg-card shadow-sm">
                    <div className="flex items-center justify-between border-b px-5 py-4">
                        <div>
                            <h2 className="font-semibold">
                                Próximos treinamentos
                            </h2>
                            <p className="text-sm text-muted-foreground">
                                Agenda mais próxima da equipe
                            </p>
                        </div>
                        <Link
                            href="/trainings"
                            className="text-sm font-medium text-primary hover:underline"
                        >
                            Ver agenda
                        </Link>
                    </div>
                    <div className="divide-y">
                        {upcoming.length === 0 && (
                            <p className="px-5 py-10 text-center text-sm text-muted-foreground">
                                Nenhum treinamento futuro agendado.
                            </p>
                        )}
                        {upcoming.map((training) => (
                            <div
                                key={training.id}
                                className="grid gap-2 px-5 py-4 sm:grid-cols-[minmax(0,1.4fr)_minmax(0,1fr)_auto] sm:items-center"
                            >
                                <div className="min-w-0">
                                    <p className="truncate font-medium">
                                        {training.course.name}
                                    </p>
                                    <p className="truncate text-sm text-muted-foreground">
                                        {training.institution}
                                    </p>
                                </div>
                                <p className="truncate text-sm">
                                    {training.employee.name}
                                </p>
                                <time className="text-sm font-medium text-muted-foreground">
                                    {new Date(
                                        `${training.starts_at}T12:00:00`,
                                    ).toLocaleDateString('pt-BR')}
                                </time>
                            </div>
                        ))}
                    </div>
                </section>
            </div>
        </>
    );
}

Dashboard.layout = {
    breadcrumbs: [
        {
            title: 'Visão geral',
            href: '/dashboard',
        },
    ],
};
