<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json; charset=utf-8');

try {
  $patente     = trim($_POST['patente'] ?? '');
  $descripcion = trim($_POST['descripcion'] ?? '');
  $marca       = trim($_POST['marca'] ?? '');
  $modelo      = trim($_POST['modelo'] ?? '');
  $anio        = $_POST['anio'] !== '' ? (int)$_POST['anio'] : null;
  $estado      = $_POST['estado'] ?? 'activo';

  if ($patente === '' || $descripcion === '') {
    throw new RuntimeException('Patente y descripción son obligatorias.');
  }

  $dup = db_one("SELECT id FROM vehiculos WHERE patente = ?", [$patente]);
  if ($dup) throw new RuntimeException('La patente ya existe.');

  db_exec(
    "INSERT INTO vehiculos (patente, descripcion, marca, modelo, anio, estado)
     VALUES (?, ?, ?, ?, ?, ?)",
    [$patente, $descripcion, $marca, $modelo, $anio, $estado]
  );

  echo json_encode(['ok'=>true, 'id'=>db_insert_id()]);
} catch (Throwable $e) {
  http_response_code(400);
  echo json_encode(['ok'=>false, 'error'=>$e->getMessage()]);
}
