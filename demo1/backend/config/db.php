<?php
/** @var ?PDO $db */
static $db = null;

if ($db === null) {
    $cfg = [
        'DB_HOST'    => 'localhost',
        'DB_PORT'    => '3308',
        'DB_NAME'    => 'trimod_bdd',
        'DB_USER'    => 'root',
        'DB_PASS'    => '',
        'DB_CHARSET' => 'utf8mb4',
    ];

    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=%s',
        $cfg['DB_HOST'],
        $cfg['DB_PORT'],
        $cfg['DB_NAME'],
        $cfg['DB_CHARSET']
    );

    try {
        $db = new PDO($dsn, $cfg['DB_USER'], $cfg['DB_PASS'], [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_PERSISTENT         => false,
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode([
            'status'  => 'error',
            'message' => 'Error de conexión a la base de datos.',
        ]);
        exit;
    }
}

/** @return PDO */
function db(): PDO {
    global $db;
    if (!($db instanceof PDO)) {
        throw new RuntimeException('Database connection is not initialized.');
    }
    return $db;
}

function db_query(string $sql, array $params = []) {
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

function db_tx(callable $fn) {
    $pdo = db();
    try {
        $pdo->beginTransaction();
        $res = $fn($pdo);
        $pdo->commit();
        return $res;
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $e;
    }
}

function db_all(string $sql, array $params = []): array {
    $st = db()->prepare($sql);
    $st->execute($params);
    return $st->fetchAll(PDO::FETCH_ASSOC);
}

function db_one(string $sql, array $params = []): ?array {
    $st = db()->prepare($sql);
    $st->execute($params);
    $row = $st->fetch(PDO::FETCH_ASSOC);
    return $row === false ? null : $row;
}

function db_exec(string $sql, array $params = []): int {
    $st = db()->prepare($sql);
    $st->execute($params);
    return $st->rowCount();
}

function db_insert_id(): string {
    return db()->lastInsertId();
}
