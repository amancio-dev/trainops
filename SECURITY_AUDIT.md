# Auditoria de segurança

Data da revisão: 25 de julho de 2026.

## Riscos críticos encontrados na versão antiga

| Risco | Evidência anterior | Correção nesta versão |
|---|---|---|
| Senhas com SHA-1 | `login.php` aplicava `sha1($senha)` | Hash adaptativo do Laravel (`bcrypt`/Argon configurável) e política de senha forte |
| Senha padrão incorreta | `cad_usuario.php` gravava `sha1('senha')`, ignorando a senha enviada | Criação exclusiva por administrador, senha mínima forte e verificação contra vazamentos |
| Segredo no código | `env.php` continha host e credenciais | Configuração somente por `.env`, nunca versionado |
| Exclusão por GET | arquivos `excluir_*.php` apagavam registros usando parâmetros da URL | `DELETE` autenticado, CSRF, autorização e confirmação na interface |
| Autorização insuficiente | sessão era verificada sem papéis ou políticas | Papéis `admin`, `manager`, `viewer` e middleware de autorização |
| Falta de validação | campos eram usados diretamente de `$_POST` | Form Requests, limites de tamanho, enums, datas coerentes e chaves existentes |
| Sessão vulnerável | autenticação manual sem rotação e proteção abrangente | Fortify, regeneração de sessão, rate limiting, conta ativa, 2FA e passkeys |
| Ausência de auditoria | alterações não deixavam trilha | Registro de ator, recurso, valores alterados, horário e IP |
| Valores financeiros frágeis | `float` e `varchar` para custos | `decimal(14,2)`/`decimal(12,2)` e cálculo centralizado |
| Dependências antigas | AdminLTE 3, jQuery e plugins copiados no repositório | Laravel 13, React 19, TypeScript e dependências gerenciadas por Composer/npm |
| Dados pessoais no repositório | O dump `trainops.sql` incluía nomes, e-mails e hashes de senha | Dump removido da árvore atual; redefinição de senhas e limpeza do histórico recomendadas |

## Controles adicionados

- proteção CSRF padrão do middleware `web`;
- limitação de login, 2FA, passkeys e rotas de escrita;
- verificação de e-mail;
- cadastro público desativado;
- conta inativa encerrada na próxima requisição;
- bloqueio de autoexclusão e da remoção do último administrador;
- cabeçalhos CSP, HSTS em HTTPS, `X-Frame-Options`, `nosniff`, política de referência e permissões;
- mass assignment restrito;
- consultas Eloquent parametrizadas;
- trilha de auditoria sem armazenar senhas, tokens ou segredos de 2FA;
- Dependabot e pipeline de análise estática/testes.

## Pendências antes da produção

1. configurar domínio HTTPS e revisar a CSP para os serviços realmente utilizados;
2. escolher `SESSION_ENCRYPT=true`, Redis/database para sessão e cookies `secure`;
3. configurar SMTP e testar recuperação/verificação de e-mail;
4. migrar usuários legados obrigando redefinição de senha;
5. tratar os dados do dump público como expostos e avaliar a reescrita do histórico Git;
6. ativar proteção de branch e exigir o workflow de CI;
7. executar teste de invasão focado em autorização horizontal, exportações e importação legada;
8. definir retenção e acesso aos logs de auditoria conforme a LGPD.

## Relato responsável

Não publique vulnerabilidades em issues públicas. Envie o relato de forma privada ao mantenedor do repositório, incluindo impacto, passos mínimos de reprodução e sugestão de correção.
