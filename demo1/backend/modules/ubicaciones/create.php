<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json');

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
  echo json_encode(['status'=>'error','message'=>'Método no permitido.']); exit;
}

try {
  $nombre      = trim($_POST['nombre'] ?? '');
  $descripcion = ($_POST['descripcion'] ?? null) ?: null;
  $direccion   = trim($_POST['direccion'] ?? '');
  $localidad   = trim($_POST['localidad'] ?? '');
  $provincia   = trim($_POST['provincia'] ?? '');
  $pais        = trim($_POST['pais'] ?? 'Argentina');
  $estado      = in_array(($_POST['estado'] ?? 'activo'), ['activo','inactivo'], true) ? $_POST['estado'] : 'activo';
  $fecha_alta  = trim($_POST['fecha_alta'] ?? '') ?: date('Y-m-d');

  if ($nombre === '') { echo json_encode(['status'=>'error','message'=>'El nombre es obligatorio.']); exit; }

  $dup = db_query(
    'SELECT id FROM ubicaciones WHERE nombre = ? AND IFNULL(localidad,"") = IFNULL(?, "") LIMIT 1',
    [$nombre, $localidad]
  )->fetch();
  if ($dup) { echo json_encode(['status'=>'error','message'=>'Ya existe una ubicación con ese nombre en esa localidad.']); exit; }

  db_query(
    'INSERT INTO ubicaciones (nombre, descripcion, direccion, localidad, provincia, pais, estado, fecha_alta)
     VALUES (?,?,?,?,?,?,?,?)',
    [$nombre,$descripcion,$direccion,$localidad,$provincia,$pais,$estado,$fecha_alta]
  );
  $id = (int) db()->lastInsertId();

  echo json_encode(['status'=>'success','message'=>'Ubicación creada.','id'=>$id]);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['status'=>'error','message'=>'Error al crear ubicación.']);
}
