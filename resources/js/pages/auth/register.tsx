import { Head, Link } from '@inertiajs/react';

export default function RegistrationDisabled() {
    return (
        <>
            <Head title="Cadastro indisponível" />
            <p className="text-center text-sm text-muted-foreground">
                A criação de contas é restrita aos administradores.{' '}
                <Link href="/login" className="font-medium text-primary hover:underline">
                    Voltar ao login
                </Link>
            </p>
        </>
    );
}

RegistrationDisabled.layout = {
    title: 'Cadastro protegido',
    description: 'Solicite acesso ao administrador do TrainOps.',
};
