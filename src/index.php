<?php $version = getenv('APP_VERSION') ?: 'dev'; ?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>KanbanFlow — CodeFactory Solutions</title>

    <meta
        name="description"
        content="Quadro Kanban para acompanhamento de tarefas da CodeFactory Solutions."
    >

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Familjen+Grotesk:wght@500;700&family=IBM+Plex+Mono:wght@500&family=IBM+Plex+Sans:wght@400;600&display=swap"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <!-- Cabeçalho -->
    <header class="topbar">

        <div class="brand">
            <span class="brand__mark" aria-hidden="true"></span>

            <div>
                <h1>KanbanFlow</h1>
                <p class="brand__sub">
                    CodeFactory Solutions · fluxo de entrega
                </p>
            </div>
        </div>

        <div class="topbar__actions">

            <span
                class="chip"
                id="chip-status"
                data-state="loading"
            >
                verificando ambiente
            </span>

            <span class="chip chip--mono">
                v<?= htmlspecialchars($version, ENT_QUOTES) ?>
            </span>

            <button
                class="btn btn--primary"
                id="btn-new"
                type="button"
            >
                Nova tarefa
            </button>

        </div>

    </header>


    <!-- Quadro Kanban -->
    <main class="board" id="board" aria-live="polite">

        <!-- A fazer -->
        <section class="column" data-status="todo">

            <header class="column__head">

                <h2>A fazer</h2>

                <span class="column__count" data-count>0</span>

                <div class="column__load">
                    <span data-load></span>
                </div>

            </header>

            <div
                class="column__body"
                data-dropzone
            ></div>

        </section>


        <!-- Em andamento -->
        <section class="column" data-status="doing">

            <header class="column__head">

                <h2>Em andamento</h2>

                <span class="column__count" data-count>0</span>

                <div class="column__load">
                    <span data-load></span>
                </div>

            </header>

            <div
                class="column__body"
                data-dropzone
            ></div>

        </section>


        <!-- Concluído -->
        <section class="column" data-status="done">

            <header class="column__head">

                <h2>Concluído</h2>

                <span class="column__count" data-count>0</span>

                <div class="column__load">
                    <span data-load></span>
                </div>

            </header>

            <div
                class="column__body"
                data-dropzone
            ></div>

        </section>

    </main>


    <!-- Modal de criação e edição -->
    <dialog class="modal" id="modal">

        <form id="form-task" novalidate>

            <h2 id="modal-title">Nova tarefa</h2>

            <input
                type="hidden"
                id="task-id"
            >

            <label for="task-title">
                Título
            </label>

            <input
                type="text"
                id="task-title"
                maxlength="120"
                required
                placeholder="Ex.: Automatizar deploy do container"
            >

            <small
                class="field-error"
                id="error-title"
            ></small>


            <label for="task-description">
                Descrição
            </label>

            <textarea
                id="task-description"
                rows="4"
                placeholder="O que precisa ser feito e como saber que terminou"
            ></textarea>


            <div class="grid-2">

                <div>

                    <label for="task-status">
                        Coluna
                    </label>

                    <select id="task-status">

                        <option value="todo">
                            A fazer
                        </option>

                        <option value="doing">
                            Em andamento
                        </option>

                        <option value="done">
                            Concluído
                        </option>

                    </select>

                </div>


                <div>

                    <label for="task-priority">
                        Prioridade
                    </label>

                    <select id="task-priority">

                        <option value="baixa">
                            Baixa
                        </option>

                        <option value="media" selected>
                            Média
                        </option>

                        <option value="alta">
                            Alta
                        </option>

                    </select>

                </div>

            </div>


            <label for="task-assignee">
                Responsável
            </label>

            <input
                type="text"
                id="task-assignee"
                maxlength="60"
                placeholder="Ex.: Infra"
            >


            <footer class="modal__actions">

                <button
                    type="button"
                    class="btn"
                    id="btn-cancel"
                >
                    Cancelar
                </button>

                <button
                    type="submit"
                    class="btn btn--primary"
                    id="btn-save"
                >
                    Salvar tarefa
                </button>

            </footer>

        </form>

    </dialog>


    <!-- Mensagens de feedback -->
    <div
        class="toast"
        id="toast"
        role="status"
        aria-live="polite"
    ></div>


    <!-- Rodapé -->
    <footer class="footer">

        <span>
            Apache 2.4 · PHP <?= PHP_VERSION ?> · MariaDB 11.4 · Docker
        </span>

        <span class="mono">
            MIT License
        </span>

    </footer>


    <script src="assets/js/app.js" defer></script>

</body>
</html>
