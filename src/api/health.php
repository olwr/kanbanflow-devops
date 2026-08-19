<?php

declare(strict_types=1);

require_once __DIR__ . '/db.php';

try {
    $version = db()->query('SELECT VERSION() AS v')->fetch()['v'] ?? 'desconhecida';

    json_response([
        'status' => 'ok',
        'php' => PHP_VERSION,
        'database' => $version,
        'app_version' => getenv('APP_VERSION') ?: 'dev',
        'checked_at' => date('c'),
    ]);
} catch (\Throwable $e) {
    error_log('[health] ' . $e->getMessage());
    json_response(['status' => 'erro', 'detalhe' => 'banco de dados indisponível'], 503);
}
