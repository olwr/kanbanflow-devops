<?php

declare(strict_types=1);

require_once __DIR__ . '/db.php';

const STATUSES = ['todo', 'doing', 'done'];
const PRIORITIES = ['baixa', 'media', 'alta'];

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? (int) $_GET['id'] : null;

if ($method === 'OPTIONS') {
    json_response(null, 204);
}

/** Valida os campos enviados. Retorna a lista de erros encontrados. */
function validate(array $data, bool $partial = false): array
{
    $errors = [];

    if (!$partial || array_key_exists('title', $data)) {
        $title = trim((string) ($data['title'] ?? ''));

        if ($title === '') {
            $errors['title'] = 'Informe um título para a tarefa.';
        } elseif (mb_strlen($title) > 120) {
            $errors['title'] = 'O título deve ter no máximo 120 caracteres.';
        }
    }

    if (array_key_exists('status', $data) && !in_array($data['status'], STATUSES, true)) {
        $errors['status'] = 'Status deve ser todo, doing ou done.';
    }

    if (array_key_exists('priority', $data) && !in_array($data['priority'], PRIORITIES, true)) {
        $errors['priority'] = 'Prioridade deve ser baixa, media ou alta.';
    }

    if (array_key_exists('assignee', $data) && mb_strlen((string) $data['assignee']) > 60) {
        $errors['assignee'] = 'O responsável ter no máximo 60 caracteres.';
    }

    return $errors;
}

try {
    switch ($method) {
        // ---------- READ ----------
        case 'GET':
            if ($id !== null) {
                $stmt = db()->prepare('SELECT * FROM tasks WHERE id = :id');
                $stmt->execute(['id' => $id]);
                $task = $stmt->fetch();
                $task
                    ? json_response($task)
                    : json_response(['erro' => 'Tarefa não encontrada.'], 404);
            }
            $tasks = db()->query(
                'SELECT * FROM tasks ORDER BY FIELD(status, "todo","doing","done"), position, id'
            )->fetchAll();
            json_response(['total' => count($tasks), 'data' => $tasks]);

            // ---------- CREATE ----------
        case 'POST':
            $data = json_body();
            if ($errors = validate($data)) {
                json_response(['erro' => 'Dados inválidos.', 'campos' => $errors], 422);
            }
            $status = $data['status'] ?? 'todo';
            $next = db()->prepare('SELECT COALESCE(MAX(position), 0) + 1 AS p FROM tasks WHERE status = :s');
            $next->execute(['s' => $status]);

            $stmt = db()->prepare(
                'INSERT INTO tasks (title, description, status, priority, assignee, position)
                 VALUES (:title, :description, :status, :priority, :assignee, :position)'
            );
            $stmt->execute([
                'title'       => trim((string) $data['title']),
                'description' => $data['description'] ?? null,
                'status'      => $status,
                'priority'    => $data['priority'] ?? 'media',
                'assignee'    => $data['assignee'] ?? null,
                'position'    => (int) $next->fetch()['p'],
            ]);

            $created = db()->prepare('SELECT * FROM tasks WHERE id = :id');
            $created->execute(['id' => (int) db()->lastInsertId()]);
            json_response($created->fetch(), 201);

            // ---------- UPDATE ----------
        case 'PUT':
            if ($id === null) {
                json_response(['erro' => 'Informe o id da tarefa na query string.'], 400);
            }
            $data = json_body();
            if ($errors = validate($data, partial: true)) {
                json_response(['erro' => 'Dados inválidos.', 'campos' => $errors], 422);
            }

            $fields = [];
            $params = ['id' => $id];
            foreach (['title', 'description', 'status', 'priority', 'assignee', 'position'] as $field) {
                if (array_key_exists($field, $data)) {
                    $fields[] = "{$field} = :{$field}";
                    $params[$field] = is_string($data[$field]) ? trim($data[$field]) : $data[$field];
                }
            }
            if (!$fields) {
                json_response(['erro' => 'Nenhum campo para atualizar.'], 400);
            }

            $stmt = db()->prepare('UPDATE tasks SET ' . implode(', ', $fields) . ' WHERE id = :id');
            $stmt->execute($params);
            if ($stmt->rowCount() === 0) {
                $exists = db()->prepare('SELECT COUNT(*) AS c FROM tasks WHERE id = :id');
                $exists->execute(['id' => $id]);
                if ((int) $exists->fetch()['c'] === 0) {
                    json_response(['erro' => 'Tarefa não encontrada.'], 404);
                }
            }

            $updated = db()->prepare('SELECT * FROM tasks WHERE id = :id');
            $updated->execute(['id' => $id]);
            json_response($updated->fetch());

            // ---------- DELETE ----------
        case 'DELETE':
            if ($id === null) {
                json_response(['erro' => 'Informe o id da tarefa na query string.'], 400);
            }
            $stmt = db()->prepare('DELETE FROM tasks WHERE id = :id');
            $stmt->execute(['id' => $id]);
            $stmt->rowCount() > 0
                ? json_response(['mensagem' => 'Tarefa excluída.', 'id' => $id])
                : json_response(['erro' => 'Tarefa não encontrada.'], 404);

        default:
            json_response(['erro' => 'Método não permitido.'], 405);
    }
} catch (\Throwable $e) {
    error_log('[tasks] ' . $e->getMessage());
    json_response(['erro' => 'Falha interna ao processar a requisição.'], 500);
}
