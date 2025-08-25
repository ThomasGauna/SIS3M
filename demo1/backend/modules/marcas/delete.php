<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'] ?? '';
if (!in_array($method, ['DELETE', 'POST'], true)) {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
    exit;
}

try {
    $data = $method === 'DELETE'
        ? json_decode(file_get_contents('php://input'), true)
        : $_POST;

    $id = isset($data['id']) ? (int)$data['id'] : 0;

    if ($id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'ID inválido.']);
        exit;
    }

    $deleted = db_query(
        'DELETE FROM marcas WHERE id = ?',
        [$id]
    )->rowCount();

    echo json_encode([
        'status'  => 'success',
        'message' => $deleted > 0 ? 'Marca eliminada con éxito.' : 'No se encontró la marca.',
        'deleted' => $deleted
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Error al eliminar la marca.']);
}
