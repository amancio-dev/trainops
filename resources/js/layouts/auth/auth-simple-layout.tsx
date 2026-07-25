import { Link } from '@inertiajs/react';
import { Fingerprint, LockKeyhole, ShieldCheck } from 'lucide-react';
import AppLogoIcon from '@/components/app-logo-icon';
import type { AuthLayoutProps } from '@/types';

export default function AuthSimpleLayout({
    children,
    title,
    description,
}: AuthLayoutProps) {
    return (
        <div className="relative flex min-h-svh items-center justify-center overflow-hidden bg-[#07131f] p-6">
            <div className="absolute inset-0 [background-image:linear-gradient(rgba(66,211,146,.12)_1px,transparent_1px),linear-gradient(90deg,rgba(66,211,146,.12)_1px,transparent_1px)] [background-size:42px_42px] opacity-30" />
            <div className="absolute -top-40 -left-24 size-96 rounded-full bg-emerald-500/15 blur-3xl" />
            <div className="absolute -right-32 -bottom-40 size-96 rounded-full bg-blue-500/15 blur-3xl" />

            <div className="relative grid w-full max-w-5xl overflow-hidden rounded-3xl border border-white/10 bg-white shadow-2xl lg:grid-cols-[1.15fr_1fr] dark:bg-card">
                <aside className="hidden min-h-[650px] flex-col justify-between bg-gradient-to-br from-[#0d2233] via-[#102b3b] to-[#0b3b35] p-10 text-white lg:flex">
                    <Link
                        href="/"
                        className="flex items-center gap-3 text-xl font-semibold"
                    >
                        <span className="grid size-11 place-items-center rounded-xl bg-emerald-400 text-[#09251e]">
                            <AppLogoIcon className="size-7 fill-current" />
                        </span>
                        TrainOps
                    </Link>
                    <div>
                        <p className="text-xs font-semibold tracking-[0.22em] text-emerald-300 uppercase">
                            Desenvolvimento que avança
                        </p>
                        <h2 className="mt-4 max-w-md text-4xl leading-tight font-semibold">
                            Pessoas preparadas movem toda a operação.
                        </h2>
                        <p className="mt-4 max-w-md text-sm leading-6 text-slate-300">
                            Planeje capacitações, acompanhe investimentos e tome
                            decisões com dados confiáveis.
                        </p>
                    </div>
                    <div className="flex gap-5 text-xs text-slate-300">
                        <span className="flex items-center gap-2">
                            <ShieldCheck className="size-4 text-emerald-300" />{' '}
                            Acesso controlado
                        </span>
                        <span className="flex items-center gap-2">
                            <Fingerprint className="size-4 text-emerald-300" />{' '}
                            2FA e passkeys
                        </span>
                        <span className="flex items-center gap-2">
                            <LockKeyhole className="size-4 text-emerald-300" />{' '}
                            Dados auditáveis
                        </span>
                    </div>
                </aside>

                <div className="flex min-h-[620px] items-center p-7 sm:p-12">
                    <div className="mx-auto w-full max-w-sm">
                        <div className="flex flex-col gap-8">
                            <div className="flex flex-col items-center gap-4">
                                <Link
                                    href="/"
                                    className="flex flex-col items-center gap-2 font-medium"
                                >
                                    <div className="mb-1 flex size-12 items-center justify-center rounded-xl bg-primary text-primary-foreground lg:hidden">
                                        <AppLogoIcon className="size-7 fill-current" />
                                    </div>
                                    <span className="sr-only">{title}</span>
                                </Link>

                                <div className="space-y-2 text-center">
                                    <h1 className="text-xl font-medium">
                                        {title}
                                    </h1>
                                    <p className="text-center text-sm text-muted-foreground">
                                        {description}
                                    </p>
                                </div>
                            </div>
                            {children}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
