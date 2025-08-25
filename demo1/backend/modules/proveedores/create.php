<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json');

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    echo json_encode(['status'=>'error','message'=>'Método no permitido.']); exit;
}

try {
    $nombre            = trim($_POST['nombre'] ?? '');
    $cuit              = trim($_POST['cuit'] ?? '');
    $telefono          = trim($_POST['telefono'] ?? '');
    $email             = trim($_POST['email'] ?? '');
    $direccion         = trim($_POST['direccion'] ?? '');
    $localidad         = trim($_POST['localidad'] ?? '');
    $provincia         = trim($_POST['provincia'] ?? '');
    $pais              = trim($_POST['pais'] ?? 'Argentina');
    $contacto_nombre   = trim($_POST['contacto_nombre'] ?? '');
    $contacto_telefono = trim($_POST['contacto_telefono'] ?? '');
    $contacto_email    = trim($_POST['contacto_email'] ?? '');
    $observaciones     = trim($_POST['observaciones'] ?? '');
    $estado            = trim($_POST['estado'] ?? 'activo');
    $fecha_alta        = trim($_POST['fecha_alta'] ?? '') ?: date('Y-m-d');

    if ($nombre === '') {
        echo json_encode(['status'=>'error','message'=>'El nombre es obligatorio.']); exit;
    }
    if (!in_array($estado, ['activo','inactivo'], true)) $estado = 'activo';

    $existe = db_query(
        'SELECT id FROM proveedores WHERE nombre = ? OR (? <> "" AND cuit = ?) LIMIT 1',
        [$nombre, $cuit, $cuit]
    )->fetch();
    if ($existe) {
        echo json_encode(['status'=>'error','message'=>'Ya existe un proveedor con ese nombre o CUIT.']); exit;
    }

    db_query(
        'INSERT INTO proveedores
         (nombre, cuit, telefono, email, direccion, localidad, provincia, pais,
          contacto_nombre, contacto_telefono, contacto_email, observaciones, estado, fecha_alta)
         VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)',
        [
            $nombre, $cuit, $telefono, $email, $direccion, $localidad, $provincia, $pais,
            $contacto_nombre, $contacto_telefono, $contacto_email, $observaciones, $estado, $fecha_alta
        ]
    );

    $id = (int) db()->lastInsertId();
    echo json_encode(['status'=>'success','message'=>'Proveedor registrado con éxito.','id'=>$id]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['status'=>'error','message'=>'Error al registrar el proveedor.']);
}
