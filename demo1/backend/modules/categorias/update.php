<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'] ?? '';
if (!in_array($method, ['PUT','POST'], true)) {
    echo json_encode(['status'=>'error','message'=>'Método no permitido.']);
    exit;
}

try {
    $data = $method === 'PUT'
        ? (json_decode(file_get_contents('php://input'), true) ?? [])
        : $_POST;

    $id          = isset($data['id']) ? (int)$data['id'] : 0;
    $nombre      = trim($data['nombre'] ?? '');
    $descripcion = ($data['descripcion'] ?? null) ?: null;

    if ($id <= 0 || $nombre === '') {
        echo json_encode(['status'=>'error','message'=>'ID y nombre son obligatorios.']);
        exit;
    }

    $existe = db_query(
        'SELECT id FROM categorias WHERE nombre = ? AND id <> ?',
        [$nombre, $id]
    )->fetch();
    if ($existe) {
        echo json_encode(['status'=>'error','message'=>'Ya existe otra categoría con ese nombre.']);
        exit;
    }

    $aff = db_query(
        'UPDATE categorias SET nombre = ?, descripcion = ? WHERE id = ?',
        [$nombre, $descripcion, $id]
    )->rowCount();

    echo json_encode([
        'status'=>'success',
        'message'=> $aff > 0 ? 'Categoría actualizada.' : 'Sin cambios.',
        'updated'=>$aff
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['status'=>'error','message'=>'Error al actualizar la categoría.']);
}
