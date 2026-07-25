import { Head, router, useForm, usePage } from '@inertiajs/react';
import {
    BriefcaseBusiness,
    GraduationCap,
    Pencil,
    Plus,
    Trash2,
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

type Reference = {
    id: number;
    name: string;
    description: string | null;
    active: boolean;
    users_count?: number;
    courses_count?: number;
};

type Kind = 'job-positions' | 'training-types';

export default function ReferencesIndex({
    jobPositions,
    trainingTypes,
}: {
    jobPositions: Reference[];
    trainingTypes: Reference[];
}) {
    const { auth } = usePage().props;
    const [kind, setKind] = useState<Kind>('job-positions');
    const [editing, setEditing] = useState<Reference | null>(null);
    const [open, setOpen] = useState(false);
    const form = useForm({ name: '', description: '', active: true });

    function showModal(nextKind: Kind, record: Reference | null = null) {
        setKind(nextKind);
        setEditing(record);
        form.setData({
            name: record?.name ?? '',
            description: record?.description ?? '',
            active: record?.active ?? true,
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
            form.put(`/references/${kind}/${editing.id}`, options);
        } else {
            form.post(`/references/${kind}`, options);
        }
    }

    const sections: Array<{
        kind: Kind;
        title: string;
        description: string;
        items: Reference[];
        icon: typeof BriefcaseBusiness;
        countKey: 'users_count' | 'courses_count';
    }> = [
        {
            kind: 'job-positions',
            title: 'Cargos',
            description: 'Estrutura de funções da empresa',
            items: jobPositions,
            icon: BriefcaseBusiness,
            countKey: 'users_count',
        },
        {
            kind: 'training-types',
            title: 'Modalidades',
            description: 'Classificação dos cursos',
            items: trainingTypes,
            icon: GraduationCap,
            countKey: 'courses_count',
        },
    ];

    return (
        <>
            <Head title="Cadastros auxiliares" />
            <div className="flex flex-1 flex-col gap-6 p-4 md:p-6">
                <PageHeader
                    eyebrow="Configuração"
                    title="Cadastros auxiliares"
                    description="Padronize cargos e modalidades usados em todo o TrainOps."
                />
                <div className="grid gap-4 xl:grid-cols-2">
                    {sections.map((section) => (
                        <section
                            key={section.kind}
                            className="overflow-hidden rounded-2xl border bg-card shadow-sm"
                        >
                            <div className="flex items-center justify-between border-b p-5">
                                <div className="flex items-center gap-3">
                                    <span className="rounded-xl bg-primary/10 p-2.5 text-primary">
                                        <section.icon className="size-5" />
                                    </span>
                                    <div>
                                        <h2 className="font-semibold">
                                            {section.title}
                                        </h2>
                                        <p className="text-sm text-muted-foreground">
                                            {section.description}
                                        </p>
                                    </div>
                                </div>
                                {auth.permissions.manage && (
                                    <Button
                                        size="sm"
                                        onClick={() => showModal(section.kind)}
                                    >
                                        <Plus className="size-4" /> Adicionar
                                    </Button>
                                )}
                            </div>
                            <div className="divide-y">
                                {section.items.map((item) => (
                                    <div
                                        key={item.id}
                                        className="flex items-center justify-between gap-3 px-5 py-3"
                                    >
                                        <div className="min-w-0">
                                            <p className="truncate font-medium">
                                                {item.name}
                                            </p>
                                            <p className="text-xs text-muted-foreground">
                                                {item[section.countKey] ?? 0}{' '}
                                                vínculos ·{' '}
                                                {item.active
                                                    ? 'ativo'
                                                    : 'inativo'}
                                            </p>
                                        </div>
                                        {auth.permissions.manage && (
                                            <div className="flex">
                                                <Button
                                                    variant="ghost"
                                                    size="icon"
                                                    onClick={() =>
                                                        showModal(
                                                            section.kind,
                                                            item,
                                                        )
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
                                                                `Remover ${item.name}?`,
                                                            )
                                                        ) {
                                                            router.delete(
                                                                `/references/${section.kind}/${item.id}`,
                                                            );
                                                        }
                                                    }}
                                                >
                                                    <Trash2 className="size-4" />
                                                </Button>
                                            </div>
                                        )}
                                    </div>
                                ))}
                            </div>
                        </section>
                    ))}
                </div>
            </div>

            <Dialog open={open} onOpenChange={setOpen}>
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>
                            {editing ? 'Editar cadastro' : 'Novo cadastro'}
                        </DialogTitle>
                        <DialogDescription>
                            {kind === 'job-positions'
                                ? 'Cargo dos colaboradores.'
                                : 'Modalidade dos cursos.'}
                        </DialogDescription>
                    </DialogHeader>
                    <form onSubmit={submit} className="space-y-4">
                        <div>
                            <Label htmlFor="reference-name">Nome</Label>
                            <Input
                                id="reference-name"
                                value={form.data.name}
                                onChange={(event) =>
                                    form.setData('name', event.target.value)
                                }
                            />
                            <FieldError message={form.errors.name} />
                        </div>
                        <div>
                            <Label htmlFor="reference-description">
                                Descrição
                            </Label>
                            <textarea
                                id="reference-description"
                                rows={3}
                                value={form.data.description}
                                onChange={(event) =>
                                    form.setData(
                                        'description',
                                        event.target.value,
                                    )
                                }
                                className="mt-1 w-full rounded-md border bg-background px-3 py-2 text-sm"
                            />
                            <FieldError message={form.errors.description} />
                        </div>
                        <label className="flex items-center gap-2 text-sm">
                            <input
                                type="checkbox"
                                checked={form.data.active}
                                onChange={(event) =>
                                    form.setData('active', event.target.checked)
                                }
                                className="size-4"
                            />
                            Ativo
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
                                Salvar
                            </Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>
        </>
    );
}

ReferencesIndex.layout = {
    breadcrumbs: [
        { title: 'Visão geral', href: '/dashboard' },
        { title: 'Cadastros auxiliares', href: '/references' },
    ],
};
