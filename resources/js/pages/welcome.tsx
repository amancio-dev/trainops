import { Head, Link } from '@inertiajs/react';

export default function Welcome() {
    return (
        <>
            <Head title="TrainOps" />
            <main className="grid min-h-svh place-items-center bg-background p-6">
                <div className="text-center">
                    <h1 className="text-3xl font-semibold">TrainOps</h1>
                    <p className="mt-2 text-muted-foreground">Desenvolvimento que avança.</p>
                    <Link
                        href="/login"
                        className="mt-6 inline-flex rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground"
                    >
                        Entrar
                    </Link>
                </div>
            </main>
        </>
    );
}
