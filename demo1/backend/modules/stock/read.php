<?php
declare(strict_types=1);

$paths = [
  __DIR__ . '/../modules/config/db.php',
  __DIR__ . '/../config/db.php',
  __DIR__ . '/../../config/db.php'
];
foreach ($paths as $p) { if (is_file($p)) { require_once $p; break; } }

header('Content-Type: application/json; charset=utf-8');

try {
  $pdo = db();

  $productoId = isset($_GET['producto_id']) && $_GET['producto_id'] !== '' ? (int)$_GET['producto_id'] : null;
  $q          = trim($_GET['q'] ?? '');
  $limit      = min(500, max(1, (int)($_GET['limit'] ?? 200)));

  $sql = "
    SELECT
      m.id,
      DATE_FORMAT(m.created_at, '%Y-%m-%d %H:%i:%s') AS created_at,
      p.nombre AS producto,
      m.tipo,
      m.cantidad,
      m.origen,
      m.ref_tipo,
      m.ref_id,
      m.notas
    FROM movimientos_productos m
    LEFT JOIN productos p ON p.id = m.producto_id
    WHERE 1=1
  ";

  $params = [];

  if (!is_null($productoId)) {
    $sql .= " AND m.producto_id = :pid";
    $params[':pid'] = $productoId;
  }

  if ($q !== '') {
    $sql .= " AND (p.nombre LIKE :qq OR m.origen LIKE :qq OR m.ref_tipo LIKE :qq OR CAST(m.ref_id AS CHAR) LIKE :qq OR m.notas LIKE :qq)";
    $params[':qq'] = "%{$q}%";
  }

  $sql .= " ORDER BY m.created_at DESC, m.id DESC LIMIT :lim";

  $stmt = $pdo->prepare($sql);
  foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v, is_int($v) ? PDO::PARAM_INT : PDO::PARAM_STR);
  }
  $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
  $stmt->execute();

  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode([
    'status'      => 'success',
    'movimientos' => $rows,
  ]);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
