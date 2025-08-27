<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json; charset=utf-8');

try {
  if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'GET') {
    echo json_encode(['status'=>'error','message'=>'Método no permitido']); exit;
  }

  $q     = trim($_GET['q'] ?? '');
  $limit = (int)($_GET['limit'] ?? 500);
  if ($limit < 1) $limit = 1;
  if ($limit > 1000) $limit = 1000;

  $where  = [];
  $params = [];

  $where[] = '(estado IS NULL OR estado IN ("activo","activa"))';

  if ($q !== '') {
    $where[] = '(nombre LIKE ? OR localidad LIKE ? OR provincia LIKE ?)';
    $like = "%$q%";
    array_push($params, $like, $like, $like);
  }

  $sql = 'SELECT id, nombre
          FROM ubicaciones';
  if ($where) $sql .= ' WHERE '.implode(' AND ', $where);
  $sql .= ' ORDER BY nombre ASC, id ASC LIMIT '.$limit;

  $rows = db_query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
  $items = array_map(fn($r)=> ['id'=>(int)$r['id'], 'nombre'=>($r['nombre'] ?: ('Ubicación #'.$r['id']))], $rows);

  echo json_encode(['status'=>'success','items'=>$items], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['status'=>'error','message'=>'Error al listar ubicaciones','error'=>$e->getMessage()]);
}
