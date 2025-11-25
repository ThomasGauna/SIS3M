<?php
declare(strict_types=1);
require_once __DIR__ . '/../../../config/db.php';
header('Content-Type: application/json; charset=utf-8');

try {
  $q          = trim($_GET['q'] ?? '');
  $estado     = trim($_GET['estado'] ?? '');
  $cliente_id = isset($_GET['cliente_id']) && $_GET['cliente_id'] !== '' ? (int)$_GET['cliente_id'] : null;
  $page       = max(1, (int)($_GET['page'] ?? 1));
  $perPage    = min(50, max(5, (int)($_GET['per_page'] ?? 10)));
  $offset     = ($page - 1) * $perPage;

  $sqlBase = "
    FROM trabajos t
    INNER JOIN clientes c ON c.id = t.cliente_id
    WHERE 1=1
  ";

  $params = [];

  if ($q !== '') {
    $sqlBase .= " AND (t.titulo LIKE :qw OR c.nombre LIKE :qc OR c.apellido LIKE :qa)";
    $like = "%{$q}%";
    $params[':qw'] = $like;
    $params[':qc'] = $like;
    $params[':qa'] = $like;
  }
  if ($estado !== '') {
    $sqlBase .= " AND t.estado = :estado";
    $params[':estado'] = $estado;
  }
  if (!is_null($cliente_id)) {
    $sqlBase .= " AND t.cliente_id = :cliente_id";
    $params[':cliente_id'] = $cliente_id;
  }

  $stmtCount = db_query("SELECT COUNT(*) ".$sqlBase, $params);
  $total = (int)$stmtCount->fetchColumn();

  $sqlData = "
    SELECT
      t.id,
      t.fecha_alta,
      t.titulo,
      t.prioridad,
      t.estado,
      c.id AS cliente_id,
      TRIM(CONCAT(c.nombre, ' ', COALESCE(c.apellido, ''))) AS cliente
    ".$sqlBase."
    ORDER BY t.fecha_alta DESC, t.id DESC
    LIMIT :limit OFFSET :offset
  ";

  $pdo = db();
  $stmt = $pdo->prepare($sqlData);
  foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v, is_int($v) ? PDO::PARAM_INT : PDO::PARAM_STR);
  }
  $stmt->bindValue(':limit',  $perPage, PDO::PARAM_INT);
  $stmt->bindValue(':offset', $offset,  PDO::PARAM_INT);
  $stmt->execute();

  $items = $stmt->fetchAll();

  echo json_encode([
    'ok'       => true,
    'items'    => $items,
    'total'    => $total,
    'page'     => $page,
    'per_page' => $perPage,
  ]);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['ok'=>false, 'error'=>$e->getMessage()]);
}
