# KanbanFlow

![status](https://img.shields.io/badge/status-ativo-58919B)
![php](https://img.shields.io/badge/PHP-8.4-626D74)
![db](https://img.shields.io/badge/MariaDB-11.4-C6B89C)
![license](https://img.shields.io/badge/license-MIT-90999F)

## Descrição do projeto

KanbanFlow é uma aplicação web de página única para acompanhamento de tarefas em
formato de quadro Kanban, com operações completas de criação, leitura, atualização
e exclusão. O projeto é o estudo de caso da consultoria de adoção da Cultura DevOps
na empresa fictícia CodeFactory Solutions, disciplina de DevOps e Integração Contínua.

## Objetivo

Demonstrar, em um produto pequeno e funcional, como versionamento distribuído,
padronização de repositório, containerização e integração contínua resolvem os
problemas relatados pela CodeFactory Solutions: ambientes divergentes entre
desenvolvedores, integração manual e demorada, ausência de documentação e erros
recorrentes após atualizações.

## Tecnologias utilizadas

| Camada | Tecnologia |
| --- | --- |
| Frontend | HTML5, CSS3 (custom properties), JavaScript ES6+ (Fetch API, Drag and Drop) |
| Backend | PHP 8.4 (PDO), API REST em JSON |
| Banco de dados | MariaDB 11.4 |
| Servidor web | Apache 2.4 (mod_php, mod_rewrite) |
| Containers | Docker + Docker Compose |
| CI/CD | GitHub Actions |
| Versionamento | Git + GitHub (Issues, Milestones, Labels, Wiki, Projects) |

## Estrutura de pastas

```sh
kanbanflow/
├── .github/workflows/   # pipelines de CI/CD
├── db/init.sql          # schema e carga inicial do MariaDB
├── docker/              # Dockerfile, php.ini e vhost do Apache
├── docs/                # documentação e evidências
├── src/                 # código servido pelo Apache
│   ├── api/             # endpoints REST em PHP
│   ├── assets/          # css e js
│   └── index.php        # página única
├── docker-compose.yml
└── README.md
```

## Instruções de instalação

Pré-requisitos: Docker Engine 24+ e Docker Compose v2. Nada mais precisa ser
instalado na máquina — nem PHP, nem MariaDB, nem Apache.

```bash
git clone https://github.com/olwr/kanbanflow-devops.git
cd kanbanflow-devops
cp .env.example .env      # ajuste as senhas se desejar
docker compose build
```

## Instruções de execução

```bash
docker compose up -d          # sobe app + banco
docker compose ps             # confere o estado (healthy)
docker compose logs -f app    # acompanha os logs do Apache/PHP
```

Acesse **<http://localhost:8080>**. A API responde em `http://localhost:8080/api/tasks.php`
e a verificação de saúde em `http://localhost:8080/api/health.php`.

Para encerrar:

```bash
docker compose down           # mantém os dados no volume
docker compose down -v        # remove também o volume do banco
```

## Endpoints da API

| Método | Rota | Ação |
| --- | --- | --- |
| GET | /api/tasks.php | Lista todas as tarefas |
| GET | /api/tasks.php?id=1 | Retorna uma tarefa |
| POST | /api/tasks.php | Cria tarefa |
| PUT | /api/tasks.php?id=1 | Atualiza tarefa |
| DELETE | /api/tasks.php?id=1 | Exclui tarefa |

## Equipe

| Nome | RU | Frente |
| --- | --- | --- |
| Oliver Benites | 5058026 | Infraestrutura, CI/CD e Backend |
| Jéssica Vasques | 5015640 | Frontend |

## Licença

Distribuído sob a licença MIT. Consulte [LICENSE](LICENSE).
