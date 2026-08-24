# Como contribuir

## Fluxo

1. Toda tarefa nasce como uma Issue com Label e Milestone.
2. Crie a branch a partir de `dev`: `git checkout -b feature/<slug> dev`.
3. Commits pequenos e frequentes, no padrão Conventional Commits.
4. Abra um Pull Request para `dev`, referenciando a Issue com `Closes #N`.
5. O PR só é integrado com CI verde e revisão de outro integrante.

## Conventional Commits

`<tipo>(<escopo>): <descrição no imperativo, minúscula, sem ponto final>`

Tipos: feat, fix, docs, style, refactor, test, chore, ci, build

Exemplos:

- feat(api): adiciona endpoint de atualização de tarefa
- fix(frontend): corrige contagem de cartões ao mover coluna
- ci(actions): adiciona job de build da imagem docker
