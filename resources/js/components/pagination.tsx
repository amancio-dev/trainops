import { Link } from '@inertiajs/react';

type PaginationLink = {
    url: string | null;
    label: string;
    active: boolean;
};

export function Pagination({ links }: { links: PaginationLink[] }) {
    if (links.length <= 3) {
return null;
}

    return (
        <nav className="flex flex-wrap justify-end gap-1 border-t px-4 py-3">
            {links.map((link) => (
                <Link
                    key={link.label}
                    href={link.url ?? '#'}
                    preserveScroll
                    className={`rounded-md px-3 py-1.5 text-sm transition ${
                        link.active
                            ? 'bg-primary text-primary-foreground'
                            : 'text-muted-foreground hover:bg-muted hover:text-foreground'
                    } ${!link.url ? 'pointer-events-none opacity-40' : ''}`}
                    dangerouslySetInnerHTML={{ __html: link.label }}
                />
            ))}
        </nav>
    );
}
