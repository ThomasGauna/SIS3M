<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json; charset=utf-8');

try {
  if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'GET') {
    echo json_encode(['status'=>'error','message'=>'Método no permitido']); exit;
  }

  $q      = trim($_GET['q'] ?? '');
  $limit  = (int)($_GET['limit'] ?? 1000);
  if ($limit < 1)   $limit = 1;
  if ($limit > 1000) $limit = 1000;

  $where  = [];
  $params = [];

  $where[] = '(p.estado IS NULL OR p.estado = "activo")';

  if ($q !== '') {
    $where[] = '(p.nombre LIKE ? OR p.sku LIKE ?)';
    $like = "%{$q}%";
    $params[] = $like; $params[] = $like;
  }

  $sql = 'SELECT p.id, p.nombre, p.sku, p.stock_actual
          FROM productos p';
  if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
  $sql .= ' ORDER BY p.nombre ASC, p.id ASC
            LIMIT ' . $limit;

  $rows = db_query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode(['status'=>'success','productos'=>$rows,'items'=>$rows], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['status'=>'error','message'=>'Error al listar productos (options).','error'=>$e->getMessage()]);
}
