import { Head } from '@inertiajs/react';
import { FileClock, ShieldCheck } from 'lucide-react';
import { PageHeader } from '@/components/page-header';
import { Pagination } from '@/components/pagination';

type AuditLog = {
    id: number;
    event: 'created' | 'updated' | 'deleted';
    subject_type: string | null;
    subject_id: number | null;
    ip_address: string | null;
    before: Record<string, unknown> | null;
    after: Record<string, unknown> | null;
    created_at: string;
    actor: { id: number; name: string } | null;
};

type Props = {
    logs: {
        data: AuditLog[];
        links: Array<{ url: string | null; label: string; active: boolean }>;
    };
};

const eventLabels = {
    created: 'Criação',
    updated: 'Alteração',
    deleted: 'Exclusão',
};

export default function AuditIndex({ logs }: Props) {
    return (
        <>
            <Head title="Auditoria" />
            <div className="flex flex-1 flex-col gap-6 p-4 md:p-6">
                <PageHeader
                    eyebrow="Segurança"
                    title="Trilha de auditoria"
                    description="Histórico imutável das alterações realizadas nos dados críticos do sistema."
                    actions={
                        <span className="inline-flex items-center gap-2 rounded-full bg-emerald-500/10 px-3 py-1.5 text-xs font-medium text-emerald-700 dark:text-emerald-300">
                            <ShieldCheck className="size-4" /> Somente
                            administradores
                        </span>
                    }
                />
                <section className="overflow-hidden rounded-2xl border bg-card shadow-sm">
                    <div className="overflow-x-auto">
                        <table className="w-full min-w-[780px] text-sm">
                            <thead className="bg-muted/45 text-left text-xs tracking-wide text-muted-foreground uppercase">
                                <tr>
                                    <th className="px-4 py-3 font-medium">
                                        Quando
                                    </th>
                                    <th className="px-4 py-3 font-medium">
                                        Responsável
                                    </th>
                                    <th className="px-4 py-3 font-medium">
                                        Evento
                                    </th>
                                    <th className="px-4 py-3 font-medium">
                                        Recurso
                                    </th>
                                    <th className="px-4 py-3 font-medium">
                                        Campos alterados
                                    </th>
                                    <th className="px-4 py-3 font-medium">
                                        IP
                                    </th>
                                </tr>
                            </thead>
                            <tbody className="divide-y">
                                {logs.data.map((log) => {
                                    const fields = Object.keys(
                                        log.after ?? log.before ?? {},
                                    );
                                    const subject =
                                        log.subject_type?.split('\\').pop() ??
                                        'Sistema';

                                    return (
                                        <tr
                                            key={log.id}
                                            className="hover:bg-muted/25"
                                        >
                                            <td className="px-4 py-3 whitespace-nowrap">
                                                {new Date(
                                                    log.created_at,
                                                ).toLocaleString('pt-BR')}
                                            </td>
                                            <td className="px-4 py-3 font-medium">
                                                {log.actor?.name ??
                                                    'Processo do sistema'}
                                            </td>
                                            <td className="px-4 py-3">
                                                <span className="rounded-full bg-primary/10 px-2.5 py-1 text-xs font-medium text-primary">
                                                    {eventLabels[log.event]}
                                                </span>
                                            </td>
                                            <td className="px-4 py-3">
                                                {subject}{' '}
                                                {log.subject_id &&
                                                    `#${log.subject_id}`}
                                            </td>
                                            <td className="max-w-xs px-4 py-3 text-xs text-muted-foreground">
                                                <span className="line-clamp-2">
                                                    {fields.length
                                                        ? fields.join(', ')
                                                        : '—'}
                                                </span>
                                            </td>
                                            <td className="px-4 py-3 font-mono text-xs text-muted-foreground">
                                                {log.ip_address ?? '—'}
                                            </td>
                                        </tr>
                                    );
                                })}
                                {logs.data.length === 0 && (
                                    <tr>
                                        <td
                                            colSpan={6}
                                            className="px-4 py-12 text-center text-muted-foreground"
                                        >
                                            <FileClock className="mx-auto mb-2 size-7" />
                                            Nenhum evento registrado.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                    <Pagination links={logs.links} />
                </section>
            </div>
        </>
    );
}

AuditIndex.layout = {
    breadcrumbs: [
        { title: 'Visão geral', href: '/dashboard' },
        { title: 'Auditoria', href: '/audit' },
    ],
};
