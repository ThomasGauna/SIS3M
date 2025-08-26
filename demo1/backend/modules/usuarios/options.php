<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json; charset=utf-8');

try {
  $vehiculo_id = isset($_GET['vehiculo_id']) ? (int)$_GET['vehiculo_id'] : 0;

  // Básico: todos activos (si más adelante filtrás por vehículo/rol, se agrega el WHERE)
  $rows = db_all("SELECT id, nombre, dni_legajo FROM usuarios WHERE estado='activo' ORDER BY nombre ASC");

  echo json_encode($rows, JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['ok'=>false,'error'=>'No se pudieron listar usuarios']);
}
