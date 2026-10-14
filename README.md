![TrainOps — Amancio.dev](.github/assets/cover.svg)

<div align="center">

# TrainOps

**Gestão de treinamentos corporativos, colaboradores e orçamento anual**

![PHP](https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=flat-square&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![React](https://img.shields.io/badge/React-19-149ECA?style=flat-square&logo=react&logoColor=white)
![TypeScript](https://img.shields.io/badge/TypeScript-3178C6?style=flat-square&logo=typescript&logoColor=white)

</div>

[Visão geral](#visão-geral) · [Tecnologias](#tecnologias) · [Funcionalidades](#funcionalidades) · [Decisões técnicas](#decisões-técnicas) · [Como executar](#como-executar) · [Qualidade](#qualidade) · [Documentação](#documentação-adicional)

## Visão geral

O TrainOps reúne o planejamento de treinamentos, a organização de colaboradores e o acompanhamento de custos em uma aplicação web. A versão atual substitui uma implementação em PHP procedural com AdminLTE por Laravel, React e TypeScript.

O projeto apresenta práticas de desenvolvimento back-end como modelagem de relacionamentos, validação no servidor, controle de acesso, filtros de consulta e testes automatizados.

## Tecnologias

| Camada | Tecnologias e uso |
| --- | --- |
| Back-end | PHP 8.3+, Laravel 13 e Eloquent ORM |
| Interface | Inertia 3, React 19 e TypeScript |
| Estilos e componentes | Tailwind CSS 4 e Radix UI |
| Autenticação | Laravel Fortify: recuperação de senha, verificação de e-mail, 2FA e passkeys |
| Banco de dados | SQLite no desenvolvimento; configuração para MySQL/MariaDB ou PostgreSQL |
| Qualidade | PHPUnit, Laravel Pint, Larastan, ESLint, Prettier e verificação de tipos TypeScript |

## Funcionalidades

| Área | Recursos |
| --- | --- |
| Pessoas e catálogo | Colaboradores com papéis `admin`, `manager` e `viewer`; cursos, modalidades e cargos |
| Treinamentos | Planejamento, aprovação, andamento, conclusão ou cancelamento; composição de custos e vínculo ao orçamento anual |
| Orçamento | Acompanhamento de utilização e saldo; aviso visual ao atingir 80% do orçamento |
| Consultas | Dashboard com indicadores, busca, filtros, paginação e exportação CSV |
| Auditoria | Registro de criação, edição e exclusão de dados |
| Interface | Layout responsivo e tema claro/escuro |

## Decisões técnicas

- **Permissões no servidor:** as rotas distinguem consulta, gestão de cursos/treinamentos/orçamentos e administração de colaboradores e auditoria.
- **Domínio com Eloquent e enums:** relacionamentos organizam colaboradores, cursos, treinamentos e orçamento; enums representam papéis e status.
- **Consulta e exportação:** filtros são aplicados tanto na listagem quanto na exportação CSV, que processa registros em blocos.
- **Validação e testes:** Form Requests validam os dados recebidos; testes incluem restrição de escrita para viewer, criação de usuário com senha armazenada como hash e compatibilidade entre ano do treinamento e orçamento.

## Como executar

### Pré-requisitos

- PHP 8.3+ e as extensões exigidas pelo Laravel
- Composer 2
- Node.js 22+

### Ambiente local

~~~bash
git clone https://github.com/amancio-dev/trainops.git
cd trainops
cp .env.example .env
~~~

Antes de executar o seeder, defina `TRAINOPS_ADMIN_EMAIL` e `TRAINOPS_ADMIN_PASSWORD` no arquivo `.env` para configurar o primeiro administrador.

~~~bash
composer setup
php artisan db:seed
composer dev
~~~

Se a senha inicial não for informada, uma senha aleatória será exibida no terminal ao criar o administrador. Guarde-a nesse momento.

### Configuração do MySQL

Para usar MySQL, altere a conexão no `.env`:

~~~dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=trainops
DB_USERNAME=trainops
DB_PASSWORD=uma-senha-forte
~~~

## Qualidade

~~~bash
npm run lint:check
npm run format:check
npm run types:check
composer test
composer audit
~~~

Os workflows de CI estão configurados para executar tipagem TypeScript, ESLint, Prettier, Laravel Pint, Larastan nível 7, PHPUnit e auditoria das dependências. Os comandos acima permitem verificar a versão instalada no seu ambiente.

A suíte inclui testes de autenticação, configurações da conta, dashboard e regras de acesso do domínio TrainOps.

## Documentação adicional

### Migração da versão antiga

O dump original foi removido da árvore atual porque continha dados pessoais e hashes SHA-1. O mapeamento das tabelas está documentado em [database/legacy/README.md](database/legacy/README.md).

Recomenda-se migrar dados com um comando dedicado que:

1. normalize valores monetários para `decimal`;
2. remapeie usuários, cargos, cursos, modalidades, orçamentos e treinamentos;
3. invalide as senhas SHA-1 e envie recuperação de senha;
4. valide totais e chaves estrangeiras antes do corte.

Como o dump existiu no primeiro commit público, considere os dados expostos: redefina as senhas afetadas e, se necessário, remova o arquivo também do histórico Git com uma ferramenta de reescrita de histórico.

Leia [SECURITY_AUDIT.md](SECURITY_AUDIT.md) antes de implantar.

### Implantação

- `APP_ENV=production`, `APP_DEBUG=false` e HTTPS obrigatório;
- cookies `secure`, `http_only` e `same_site=lax`;
- banco, cache e filas com credenciais de privilégio mínimo;
- backup criptografado e restauração testada;
- `php artisan optimize` e `npm run build` durante a entrega;
- execute migrações com `php artisan migrate --force`.

## Licença

MIT.
