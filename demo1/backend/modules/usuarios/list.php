<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json; charset=utf-8');

try {
  $q = trim($_GET['q'] ?? '');

  $sql = "SELECT u.id, u.nombre, u.email, u.estado, u.role_id, r.nombre AS rol_nombre
            FROM usuarios u
            LEFT JOIN roles r ON r.id = u.role_id";
  $params = [];

  if ($q !== '') {
    $sql .= " WHERE u.nombre LIKE ? OR u.email LIKE ?";
    $like = "%$q%";
    $params = [$like, $like];
  }

  $sql .= " ORDER BY u.nombre ASC";
  $rows = db_all($sql, $params);

  echo json_encode($rows, JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['ok'=>false, 'error'=>'No se pudo listar usuarios']);
}
