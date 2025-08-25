<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'] ?? '';
if (!in_array($method, ['PUT','POST'], true)) {
    echo json_encode(['status'=>'error','message'=>'Método no permitido.']); exit;
}

try {
    $data = $method === 'PUT'
        ? (json_decode(file_get_contents('php://input'), true) ?? [])
        : $_POST;

    $id                = isset($data['id']) ? (int)$data['id'] : 0;
    $nombre            = trim($data['nombre'] ?? '');
    $cuit              = trim($data['cuit'] ?? '');
    $telefono          = trim($data['telefono'] ?? '');
    $email             = trim($data['email'] ?? '');
    $direccion         = trim($data['direccion'] ?? '');
    $localidad         = trim($data['localidad'] ?? '');
    $provincia         = trim($data['provincia'] ?? '');
    $pais              = trim($data['pais'] ?? 'Argentina');
    $contacto_nombre   = trim($data['contacto_nombre'] ?? '');
    $contacto_telefono = trim($data['contacto_telefono'] ?? '');
    $contacto_email    = trim($data['contacto_email'] ?? '');
    $observaciones     = trim($data['observaciones'] ?? '');
    $estado            = trim($data['estado'] ?? 'activo');

    if ($id <= 0 || $nombre === '') {
        echo json_encode(['status'=>'error','message'=>'ID y nombre son obligatorios.']); exit;
    }
    if (!in_array($estado, ['activo','inactivo'], true)) $estado = 'activo';

    $dup = db_query(
        'SELECT id FROM proveedores
         WHERE (nombre = ? OR (? <> "" AND cuit = ?)) AND id <> ?
         LIMIT 1',
        [$nombre, $cuit, $cuit, $id]
    )->fetch();
    if ($dup) {
        echo json_encode(['status'=>'error','message'=>'Ya existe otro proveedor con ese nombre o CUIT.']); exit;
    }

    $aff = db_query(
        'UPDATE proveedores SET
           nombre = ?, cuit = ?, telefono = ?, email = ?, direccion = ?, localidad = ?, provincia = ?, pais = ?,
           contacto_nombre = ?, contacto_telefono = ?, contacto_email = ?, observaciones = ?, estado = ?
         WHERE id = ?',
        [
            $nombre, $cuit, $telefono, $email, $direccion, $localidad, $provincia, $pais,
            $contacto_nombre, $contacto_telefono, $contacto_email, $observaciones, $estado, $id
        ]
    )->rowCount();

    echo json_encode([
        'status'=>'success',
        'message'=> $aff>0 ? 'Proveedor actualizado.' : 'Sin cambios.',
        'updated'=>$aff
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['status'=>'error','message'=>'Error al actualizar el proveedor.']);
}
