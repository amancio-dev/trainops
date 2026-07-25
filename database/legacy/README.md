# Migração do sistema legado

O dump SQL original não é distribuído nesta versão porque continha dados pessoais, e-mails e hashes de senhas SHA-1.

Mapeamento recomendado:

| Tabela antiga | Modelo atual |
|---|---|
| `cargo` | `job_positions` |
| `usuario` | `users` |
| `tipos_treinamento` | `training_types` |
| `curso` | `courses` |
| `orcamento_anual` | `annual_budgets` |
| `acompanhamento_treinamento_usuario` | `trainings` |

Ao migrar dados reais:

1. trabalhe com uma cópia privada e criptografada do banco;
2. não importe a coluna de senha antiga;
3. envie recuperação de senha para todos os usuários;
4. converta valores monetários para decimal;
5. valide referências antes de ativar as restrições;
6. gere um relatório de reconciliação de registros e totais;
7. apague com segurança os arquivos temporários da migração.
