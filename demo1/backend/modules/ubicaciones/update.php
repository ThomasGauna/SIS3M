<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'] ?? '';
if (!in_array($method, ['PUT','POST'], true)) {
  echo json_encode(['status'=>'error','message'=>'Método no permitido.']); exit;
}

try {
  $d = $method==='PUT' ? (json_decode(file_get_contents('php://input'), true) ?? []) : $_POST;

  $id          = isset($d['id']) ? (int)$d['id'] : 0;
  $nombre      = trim($d['nombre'] ?? '');
  $descripcion = ($d['descripcion'] ?? null) ?: null;
  $direccion   = trim($d['direccion'] ?? '');
  $localidad   = trim($d['localidad'] ?? '');
  $provincia   = trim($d['provincia'] ?? '');
  $pais        = trim($d['pais'] ?? 'Argentina');
  $estado      = in_array(($d['estado'] ?? 'activo'), ['activo','inactivo'], true) ? $d['estado'] : 'activo';

  if ($id<=0 || $nombre==='') { echo json_encode(['status'=>'error','message'=>'ID y nombre son obligatorios.']); exit; }

  $dup = db_query(
    'SELECT id FROM ubicaciones WHERE id <> ? AND nombre = ? AND IFNULL(localidad,"") = IFNULL(?, "") LIMIT 1',
    [$id, $nombre, $localidad]
  )->fetch();
  if ($dup) { echo json_encode(['status'=>'error','message'=>'Conflicto: ya existe una ubicación con ese nombre en esa localidad.']); exit; }

  $aff = db_query(
    'UPDATE ubicaciones SET nombre=?, descripcion=?, direccion=?, localidad=?, provincia=?, pais=?, estado=? WHERE id=?',
    [$nombre,$descripcion,$direccion,$localidad,$provincia,$pais,$estado,$id]
  )->rowCount();

  echo json_encode(['status'=>'success','message'=>$aff>0?'Ubicación actualizada.':'Sin cambios.','updated'=>$aff]);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['status'=>'error','message'=>'Error al actualizar ubicación.']);
}
