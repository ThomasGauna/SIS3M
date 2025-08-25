<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json');

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
    exit;
}

try {
    $nombre      = trim($_POST['nombre'] ?? '');
    $descripcion = ($_POST['descripcion'] ?? null) ?: null;

    if ($nombre === '') {
        echo json_encode(['status' => 'error', 'message' => 'El nombre es obligatorio.']);
        exit;
    }

    $existe = db_query('SELECT id FROM categorias WHERE nombre = ?', [$nombre])->fetch();
    if ($existe) {
        echo json_encode(['status' => 'error', 'message' => 'La categoría ya existe.']);
        exit;
    }

    db_query('INSERT INTO categorias (nombre, descripcion) VALUES (?, ?)', [$nombre, $descripcion]);
    $id = (int) db()->lastInsertId();

    echo json_encode(['status' => 'success', 'message' => 'Categoría creada.', 'id' => $id]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Error al crear la categoría.']);
}
