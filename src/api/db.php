<?php

declare(strict_types=1);

/**
 * Conexão PDO com MariaDB.
 * As credenciais vêm de variáveis de ambiente injetadas pelo Docker Compose,
 * nunca do código fonte.
 */
function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $host = getenv('DB_HOST') ?: 'db';
    $port = getenv('DB_PORT') ?: '3306';
    $name = getenv('DB_NAME') ?: 'kanbanflow';
    $user = getenv('DB_USER') ?: 'kbf_user';
    $pass = getenv('DB_PASSWORD') ?: 'kbf_pass';
    $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";

    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return $pdo;
}

/** Envia uma resposta JSON e encerra a requisição. */
function json_response(mixed $payload, int $status = 200): never
{
    http_response_code($status);
    header('Content_Type: application/json; charset=utf-8');
    header('X-App-Version: ' . (getenv('APP_VERSION') ?: 'dev'));
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

/** Lê e valida o corpo JSON da requisição. */
function json_body(): array
{
    $raw = file_get_contents('php://input') ?: '';
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}
