-- KanbanFlow: schema inicial
SET NAMES utf8mb4;
CREATE TABLE IF NOT EXISTS tasks (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    title VARCHAR(120) NOT NULL,
    description TEXT NULL,
    status ENUM('todo', 'doing', 'done') NOT NULL DEFAULT 'todo',
    priority ENUM('baixa', 'media', 'alta') NOT NULL DEFAULT 'media',
    assignee VARCHAR(60) NULL,
    position INT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_status_position (status, position)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
-- Carga inicial: espelha o diagnóstico da CodeFactory Solutions
INSERT INTO tasks (
        title,
        description,
        status,
        priority,
        assignee,
        position
    )
VALUES (
        'Padronizar versionamento com Git',
        'Definir modelo de branches e padrão de mensagens de commit para toda a equipe.',
        'done',
        'alta',
        'Equipe DevOps',
        1
    ),
    (
        'Containerizar o ambiente de desenvolvimento',
        'Publicar Dockerfile e docker-compose para eliminar divergência entre máquinas.',
        'doing',
        'alta',
        'Infra',
        1
    ),
    (
        'Escrever documentação de onboarding',
        'Guia de ambiente na Wiki para reduzir o tempo de entrada de novos colaboradores.',
        'doing',
        'media',
        'Documentação',
        2
    ),
    (
        'Automatizar pipeline de integração contínua',
        'Validar cada Pull Request antes da integração em main.',
        'todo',
        'alta',
        'Infra',
        1
    ),
    (
        'Criar rotina de backup do banco',
        'Agendar dump periódico do MariaDB em volume externo.',
        'todo',
        'baixa',
        'Banco de dados',
        2
    );