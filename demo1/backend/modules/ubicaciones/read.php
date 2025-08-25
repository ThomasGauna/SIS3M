<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json');

try {
  $q      = trim($_GET['q'] ?? '');
  $estado = trim($_GET['estado'] ?? '');

  $where=[]; $p=[];
  if ($q !== '') {
    $where[] = '(nombre LIKE ? OR descripcion LIKE ? OR direccion LIKE ? OR localidad LIKE ? OR provincia LIKE ? OR pais LIKE ?)';
    for ($i=0; $i<6; $i++) $p[] = '%'.$q.'%';
  }
  if ($estado !== '') { $where[]='estado = ?'; $p[]=$estado; }

  $sql = 'SELECT id, nombre, descripcion, direccion, localidad, provincia, pais, estado, fecha_alta
          FROM ubicaciones';
  if ($where) $sql .= ' WHERE '.implode(' AND ', $where);
  $sql .= ' ORDER BY nombre';

  $rows = db_query($sql, $p)->fetchAll();
  header('Cache-Control: public, max-age=600');
  echo json_encode(['status'=>'success','ubicaciones'=>$rows]);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['status'=>'error','message'=>'Error al obtener ubicaciones.']);
}
