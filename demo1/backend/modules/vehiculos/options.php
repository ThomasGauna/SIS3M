<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json; charset=utf-8');

try {
  $rows = db_all("SELECT id, CONCAT(patente,' - ',descripcion) AS nombre
                  FROM vehiculos
                  WHERE estado='activo'
                  ORDER BY patente ASC");
  echo json_encode($rows, JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['ok'=>false,'error'=>'No se pudo cargar opciones de vehículos']);
}
