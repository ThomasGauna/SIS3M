<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json; charset=utf-8');

try {
  $q = trim($_GET['q'] ?? '');

  $sql = "SELECT id, patente, descripcion, marca, modelo, anio, estado
          FROM vehiculos";
  $params = [];

  if ($q !== '') {
    $sql .= " WHERE patente LIKE ? OR descripcion LIKE ? OR marca LIKE ? OR modelo LIKE ?";
    $like = "%$q%";
    $params = [$like, $like, $like, $like];
  }

  $sql .= " ORDER BY patente ASC";
  $rows = db_all($sql, $params);

  echo json_encode($rows, JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['ok'=>false,'error'=>'No se pudo listar vehículos']);
}
