<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json; charset=utf-8');

try {
  $q = trim($_GET['q'] ?? '');
  $estado = trim($_GET['estado'] ?? '');

  $where = []; $p = [];
  if ($q !== '') {
    $where[] = '(c.nombre LIKE ? OR c.apellido LIKE ? OR c.documento_nro LIKE ? OR c.email LIKE ? OR c.telefono LIKE ?)';
    for ($i=0; $i<5; $i++) $p[] = '%'.$q.'%';
  }
  if ($estado !== '') { $where[]='c.estado = ?'; $p[]=$estado; }

  $sql = '
    SELECT
      c.id, c.tipo, c.nombre, c.apellido,
      c.documento_tipo, c.documento_nro,
      c.email, c.telefono, c.estado,
      DATE_FORMAT(c.created_at, "%d-%m-%Y") AS fecha_alta
    FROM clientes c
  ';
  if ($where) $sql .= ' WHERE '.implode(' AND ', $where);
  $sql .= ' ORDER BY c.nombre, c.apellido';

  $stmt = db_query($sql, $p);
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode(['status'=>'success','clientes'=>$rows], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['status'=>'error','message'=>'Error al obtener clientes.']);
}
