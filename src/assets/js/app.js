'use strict';

const API = 'api/tasks.php';
const HEALTH = 'api/health.php';

const COLUMNS = ['todo', 'doing', 'done'];

const LABELS = {
    todo: 'A fazer',
    doing: 'Em andamento',
    done: 'Concluído'
};

const el = {
    board: document.getElementById('board'),
    modal: document.getElementById('modal'),
    form: document.getElementById('form-task'),

    title: document.getElementById('modal-title'),
    id: document.getElementById('task-id'),

    fTitle: document.getElementById('task-title'),
    fDesc: document.getElementById('task-description'),
    fStatus: document.getElementById('task-status'),
    fPriority: document.getElementById('task-priority'),
    fAssignee: document.getElementById('task-assignee'),

    errTitle: document.getElementById('error-title'),

    toast: document.getElementById('toast'),
    chip: document.getElementById('chip-status'),

    btnNew: document.getElementById('btn-new'),
    btnCancel: document.getElementById('btn-cancel')
};

let tasks = [];


/* ---------------- utilidades ---------------- */

function toast(message, kind = 'ok') {
    el.toast.textContent = message;
    el.toast.dataset.kind = kind;

    el.toast.classList.add('is-visible');

    clearTimeout(toast._timer);

    toast._timer = setTimeout(() => {
        el.toast.classList.remove('is-visible');
    }, 3200);
}


async function request(url, options = {}) {
    const response = await fetch(url, {
        headers: {
            'Content-Type': 'application/json'
        },
        ...options
    });

    const payload = response.status === 204
        ? null
        : await response.json();

    if (!response.ok) {
        const detail = payload?.campos
            ? Object.values(payload.campos).join(' ')
            : payload?.erro;

        throw new Error(
            detail || `Falha na requisição (${response.status}).`
        );
    }

    return payload;
}


function escapeHtml(text = '') {
    return String(text).replace(
        /[&<>"']/g,
        (character) => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        }[character])
    );
}


/* ---------------- leitura (R) ---------------- */

async function loadTasks() {
    try {
        const result = await request(API);

        tasks = result.data ?? [];

        render();
    } catch (error) {
        toast(error.message, 'erro');
    }
}


async function checkHealth() {
    try {
        const health = await request(HEALTH);

        el.chip.dataset.state = 'ok';

        el.chip.textContent =
            `banco ${health.database.split('-')[0]} · php ${health.php}`;

    } catch {
        el.chip.dataset.state = 'erro';
        el.chip.textContent = 'banco indisponível';
    }
}


/* ---------------- renderização ---------------- */

function render() {
    const total = tasks.length || 1;

    COLUMNS.forEach((status) => {

        const column = el.board.querySelector(
            `.column[data-status="${status}"]`
        );

        const body = column.querySelector('[data-dropzone]');

        const items = tasks.filter(
            (task) => task.status === status
        );

        column.querySelector('[data-count]').textContent =
            items.length;

        column.querySelector('[data-load]').style.width =
            `${(items.length / total) * 100}%`;

        body.innerHTML = items.length
            ? items.map(cardTemplate).join('')
            : `<p class="empty">
                Nada aqui. Arraste um cartão ou crie uma tarefa em "${LABELS[status]}".
              </p>`;
    });

    bindCards();
}


function cardTemplate(task) {
    return `
        <article
            class="card"
            draggable="true"
            tabindex="0"
            data-id="${task.id}"
            data-priority="${task.priority}"
        >

            <h3 class="card__title">
                ${escapeHtml(task.title)}
            </h3>

            ${
                task.description
                    ? `<p class="card__desc">
                        ${escapeHtml(task.description)}
                       </p>`
                    : ''
            }

            <div class="card__tags">

                <span class="tag">
                    #${task.id}
                </span>

                <span class="tag">
                    ${task.priority}
                </span>

                ${
                    task.assignee
                        ? `<span class="tag">
                            ${escapeHtml(task.assignee)}
                           </span>`
                        : ''
                }

            </div>

            <div class="card__meta">

                <span>
                    ${
                        new Date(
                            task.updated_at.replace(' ', 'T')
                        ).toLocaleDateString('pt-BR')
                    }
                </span>

                <span class="card__ops">

                    <button
                        class="icon-btn"
                        data-action="edit"
                        aria-label="Editar tarefa ${task.id}"
                    >
                        editar
                    </button>

                    <button
                        class="icon-btn icon-btn--danger"
                        data-action="delete"
                        aria-label="Excluir tarefa ${task.id}"
                    >
                        excluir
                    </button>

                </span>

            </div>

        </article>
    `;
}


/* ---------------- criação e atualização (C/U) ---------------- */

function openModal(task = null) {

    el.form.reset();

    el.errTitle.textContent = '';

    el.id.value = task?.id ?? '';

    el.title.textContent = task
        ? `Editar tarefa #${task.id}`
        : 'Nova tarefa';

    el.fTitle.value = task?.title ?? '';

    el.fDesc.value = task?.description ?? '';

    el.fStatus.value = task?.status ?? 'todo';

    el.fPriority.value = task?.priority ?? 'media';

    el.fAssignee.value = task?.assignee ?? '';

    el.modal.showModal();

    el.fTitle.focus();
}


async function submitTask(event) {

    event.preventDefault();

    const title = el.fTitle.value.trim();

    if (!title) {

        el.errTitle.textContent =
            'Informe um título para a tarefa.';

        el.fTitle.focus();

        return;
    }

    const body = JSON.stringify({
        title,
        description: el.fDesc.value.trim() || null,
        status: el.fStatus.value,
        priority: el.fPriority.value,
        assignee: el.fAssignee.value.trim() || null
    });

    const id = el.id.value;

    try {

        await request(
            id ? `${API}?id=${id}` : API,
            {
                method: id ? 'PUT' : 'POST',
                body
            }
        );

        el.modal.close();

        toast(
            id
                ? 'Tarefa atualizada.'
                : 'Tarefa criada.'
        );

        await loadTasks();

    } catch (error) {

        el.errTitle.textContent = error.message;
    }
}


/* ---------------- movimentação ---------------- */

async function moveTask(id, status) {

    const task = tasks.find(
        (item) => String(item.id) === String(id)
    );

    if (!task || task.status === status) {
        return;
    }

    try {

        await request(
            `${API}?id=${id}`,
            {
                method: 'PUT',
                body: JSON.stringify({ status })
            }
        );

        toast(
            `Movida para "${LABELS[status]}".`
        );

        await loadTasks();

    } catch (error) {

        toast(error.message, 'erro');
    }
}


/* ---------------- exclusão (D) ---------------- */

async function deleteTask(id) {

    const task = tasks.find(
        (item) => String(item.id) === String(id)
    );

    if (
        !confirm(
            `Excluir "${task?.title}"? A ação não pode ser desfeita.`
        )
    ) {
        return;
    }

    try {

        await request(
            `${API}?id=${id}`,
            {
                method: 'DELETE'
            }
        );

        toast('Tarefa excluída.');

        await loadTasks();

    } catch (error) {

        toast(error.message, 'erro');
    }
}


/* ---------------- eventos dos cartões ---------------- */

function bindCards() {

    el.board
        .querySelectorAll('.card')
        .forEach((card) => {

            card.addEventListener('dragstart', (event) => {

                event.dataTransfer.setData(
                    'text/plain',
                    card.dataset.id
                );

                card.classList.add('is-dragging');
            });


            card.addEventListener(
                'dragend',
                () => card.classList.remove('is-dragging')
            );


            card
                .querySelector('[data-action="edit"]')
                .addEventListener('click', () => {

                    openModal(
                        tasks.find(
                            (task) =>
                                String(task.id) === card.dataset.id
                        )
                    );
                });


            card
                .querySelector('[data-action="delete"]')
                .addEventListener('click', () => {

                    deleteTask(card.dataset.id);
                });


            // Alternativa por teclado ao arrastar:
            // setas movem entre colunas

            card.addEventListener('keydown', (event) => {

                if (
                    event.key !== 'ArrowRight' &&
                    event.key !== 'ArrowLeft'
                ) {
                    return;
                }

                event.preventDefault();

                const task = tasks.find(
                    (item) =>
                        String(item.id) === card.dataset.id
                );

                const next =
                    COLUMNS.indexOf(task.status) +
                    (event.key === 'ArrowRight' ? 1 : -1);

                if (COLUMNS[next]) {
                    moveTask(task.id, COLUMNS[next]);
                }
            });

        });
}


/* ---------------- Drag & Drop ---------------- */

function bindDropzones() {

    el.board
        .querySelectorAll('[data-dropzone]')
        .forEach((zone) => {

            zone.addEventListener('dragover', (event) => {

                event.preventDefault();

                zone.classList.add('is-over');
            });


            zone.addEventListener(
                'dragleave',
                () => zone.classList.remove('is-over')
            );


            zone.addEventListener('drop', (event) => {

                event.preventDefault();

                zone.classList.remove('is-over');

                const id =
                    event.dataTransfer.getData('text/plain');

                const status =
                    zone.closest('.column').dataset.status;

                moveTask(id, status);
            });

        });
}


/* ---------------- eventos gerais ---------------- */

el.btnNew.addEventListener('click', () => {
    openModal();
});


el.btnCancel.addEventListener('click', () => {
    el.modal.close();
});


el.form.addEventListener('submit', submitTask);


/* ---------------- inicialização ---------------- */

bindDropzones();

checkHealth();

loadTasks();

