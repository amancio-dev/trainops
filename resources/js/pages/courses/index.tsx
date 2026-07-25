import { Head, router, useForm, usePage } from '@inertiajs/react';
import {
    BookOpenCheck,
    Clock3,
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

type Course = {
    id: number;
    training_type_id: number;
    name: string;
    workload_hours: number;
    description: string | null;
    active: boolean;
    training_type: { id: number; name: string };
};

type Props = {
    courses: {
        data: Course[];
        links: Array<{ url: string | null; label: string; active: boolean }>;
        total: number;
    };
    trainingTypes: Array<{ id: number; name: string }>;
    filters: { search: string };
};

export default function CoursesIndex({
    courses,
    trainingTypes,
    filters,
}: Props) {
    const { auth } = usePage().props;
    const [search, setSearch] = useState(filters.search);
    const [editing, setEditing] = useState<Course | null>(null);
    const [open, setOpen] = useState(false);
    const form = useForm({
        training_type_id: '',
        name: '',
        workload_hours: '',
        description: '',
        active: true,
    });

    function openCreate() {
        setEditing(null);
        form.setData({
            training_type_id: trainingTypes[0]?.id.toString() ?? '',
            name: '',
            workload_hours: '',
            description: '',
            active: true,
        });
        form.clearErrors();
        setOpen(true);
    }

    function openEdit(course: Course) {
        setEditing(course);
        form.setData({
            training_type_id: course.training_type_id.toString(),
            name: course.name,
            workload_hours: course.workload_hours.toString(),
            description: course.description ?? '',
            active: course.active,
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
            form.put(`/courses/${editing.id}`, options);
        } else {
            form.post('/courses', options);
        }
    }

    return (
        <>
            <Head title="Cursos" />
            <div className="flex flex-1 flex-col gap-6 p-4 md:p-6">
                <PageHeader
                    eyebrow="Catálogo"
                    title="Cursos e capacitações"
                    description="Mantenha uma base limpa de cursos, carga horária e modalidade."
                    actions={
                        auth.permissions.manage && (
                            <Button onClick={openCreate}>
                                <Plus className="size-4" /> Novo curso
                            </Button>
                        )
                    }
                />

                <section className="overflow-hidden rounded-2xl border bg-card shadow-sm">
                    <div className="border-b p-4">
                        <form
                            className="relative max-w-md"
                            onSubmit={(event) => {
                                event.preventDefault();
                                router.get(
                                    '/courses',
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
                                placeholder="Buscar curso"
                                className="pl-9"
                            />
                        </form>
                    </div>
                    <div className="grid gap-3 p-4 sm:grid-cols-2 xl:grid-cols-3">
                        {courses.data.map((course) => (
                            <article
                                key={course.id}
                                className="group rounded-xl border bg-background/40 p-4 transition hover:-translate-y-0.5 hover:shadow-md"
                            >
                                <div className="flex items-start justify-between gap-3">
                                    <span className="rounded-lg bg-primary/10 p-2 text-primary">
                                        <BookOpenCheck className="size-5" />
                                    </span>
                                    <span
                                        className={`rounded-full px-2.5 py-1 text-xs font-medium ${
                                            course.active
                                                ? 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-300'
                                                : 'bg-zinc-500/10 text-zinc-600'
                                        }`}
                                    >
                                        {course.active ? 'Ativo' : 'Inativo'}
                                    </span>
                                </div>
                                <p className="mt-4 text-xs font-medium tracking-wide text-primary uppercase">
                                    {course.training_type.name}
                                </p>
                                <h2 className="mt-1 text-lg font-semibold">
                                    {course.name}
                                </h2>
                                <p className="mt-2 line-clamp-2 min-h-10 text-sm text-muted-foreground">
                                    {course.description ||
                                        'Sem descrição cadastrada.'}
                                </p>
                                <div className="mt-4 flex items-center justify-between border-t pt-3">
                                    <span className="flex items-center gap-1.5 text-sm text-muted-foreground">
                                        <Clock3 className="size-4" />{' '}
                                        {course.workload_hours}h
                                    </span>
                                    {auth.permissions.manage && (
                                        <div className="flex gap-1">
                                            <Button
                                                variant="ghost"
                                                size="icon"
                                                onClick={() => openEdit(course)}
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
                                                            `Remover o curso ${course.name}?`,
                                                        )
                                                    ) {
                                                        router.delete(
                                                            `/courses/${course.id}`,
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
                                </div>
                            </article>
                        ))}
                        {courses.data.length === 0 && (
                            <p className="col-span-full py-12 text-center text-sm text-muted-foreground">
                                Nenhum curso encontrado.
                            </p>
                        )}
                    </div>
                    <Pagination links={courses.links} />
                </section>
            </div>

            <Dialog open={open} onOpenChange={setOpen}>
                <DialogContent className="sm:max-w-lg">
                    <DialogHeader>
                        <DialogTitle>
                            {editing ? 'Editar curso' : 'Novo curso'}
                        </DialogTitle>
                        <DialogDescription>
                            Informações usadas no planejamento de treinamentos.
                        </DialogDescription>
                    </DialogHeader>
                    <form onSubmit={submit} className="space-y-4">
                        <div>
                            <Label htmlFor="course-name">Nome</Label>
                            <Input
                                id="course-name"
                                value={form.data.name}
                                onChange={(event) =>
                                    form.setData('name', event.target.value)
                                }
                            />
                            <FieldError message={form.errors.name} />
                        </div>
                        <div className="grid gap-4 sm:grid-cols-2">
                            <div>
                                <Label htmlFor="course-type">Modalidade</Label>
                                <select
                                    id="course-type"
                                    value={form.data.training_type_id}
                                    onChange={(event) =>
                                        form.setData(
                                            'training_type_id',
                                            event.target.value,
                                        )
                                    }
                                    className="mt-1 h-9 w-full rounded-md border bg-background px-3 text-sm"
                                >
                                    {trainingTypes.map((type) => (
                                        <option key={type.id} value={type.id}>
                                            {type.name}
                                        </option>
                                    ))}
                                </select>
                                <FieldError
                                    message={form.errors.training_type_id}
                                />
                            </div>
                            <div>
                                <Label htmlFor="course-hours">
                                    Carga horária
                                </Label>
                                <Input
                                    id="course-hours"
                                    type="number"
                                    min="1"
                                    value={form.data.workload_hours}
                                    onChange={(event) =>
                                        form.setData(
                                            'workload_hours',
                                            event.target.value,
                                        )
                                    }
                                />
                                <FieldError
                                    message={form.errors.workload_hours}
                                />
                            </div>
                        </div>
                        <div>
                            <Label htmlFor="course-description">
                                Descrição
                            </Label>
                            <textarea
                                id="course-description"
                                rows={4}
                                value={form.data.description}
                                onChange={(event) =>
                                    form.setData(
                                        'description',
                                        event.target.value,
                                    )
                                }
                                className="mt-1 w-full rounded-md border bg-background px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-ring/50"
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
                                className="size-4 rounded border"
                            />
                            Curso disponível para novos treinamentos
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

CoursesIndex.layout = {
    breadcrumbs: [
        { title: 'Visão geral', href: '/dashboard' },
        { title: 'Cursos', href: '/courses' },
    ],
};
