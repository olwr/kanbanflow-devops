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

## Equipe

| Nome | RU | Frente |
| --- | --- | --- |
| Oliver Benites | 5058026 | Infraestrutura, CI/CD e Backend |
| Jéssica Vasques | 5015640 | Frontend |

## Licença

Distribuído sob a licença MIT. Consulte [LICENSE](LICENSE).
