---
name: Relatar um problema
about: Registrar um comportamento incorreto no KanbanFlow
title: "bug: "
labels: ["tipo:bug"]
assignees: []
---

## O que está acontecendo

<!-- Descreva o comportamento observado em uma ou duas frases. -->

## O que deveria acontecer

<!-- Descreva o comportamento esperado. -->

## Como reproduzir

1. Suba o ambiente com `docker compose up -d`
2. Acesse `http://localhost:8080`
3. ...
4. O problema aparece

## Onde acontece

- [ ] Frontend (HTML/CSS/JS)
- [ ] API PHP (`src/api/`)
- [ ] Banco de dados (MariaDB / `init.sql`)
- [ ] Containers (Dockerfile / Compose)
- [ ] Pipeline (GitHub Actions)

## Evidências

<!-- Anexe prints, o JSON retornado pela API ou a saída do terminal. -->

**Resposta da API** (se aplicável):

```json

```

**Logs dos containers:**

```
docker compose logs --tail 50 app
docker compose logs --tail 50 db
```

**Console do navegador** (se for erro de frontend):

```

```

## Ambiente

| Item                | Valor                                          |
| ------------------- | ---------------------------------------------- |
| Sistema operacional | <!-- Ex.: Ubuntu 24.04 / Windows 11 + WSL2 --> |
| Versão do Docker    | <!-- docker --version -->                      |
| Versão do Compose   | <!-- docker compose version -->                |
| Branch              | <!-- Ex.: develop -->                          |
| Commit              | <!-- git rev-parse --short HEAD -->            |
| Navegador           | <!-- Ex.: Firefox 129 -->                      |

## Impacto

- [ ] Bloqueia o uso da aplicação
- [ ] Atrapalha, mas existe uma alternativa
- [ ] Incômodo pequeno

## Contexto adicional

<!-- Hipóteses sobre a causa, quando o problema começou, o que já foi tentado. -->