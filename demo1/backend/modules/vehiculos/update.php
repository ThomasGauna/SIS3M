<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json; charset=utf-8');

try {
  $id          = (int)($_POST['id'] ?? 0);
  $patente     = trim($_POST['patente'] ?? '');
  $descripcion = trim($_POST['descripcion'] ?? '');
  $marca       = trim($_POST['marca'] ?? '');
  $modelo      = trim($_POST['modelo'] ?? '');
  $anio        = $_POST['anio'] !== '' ? (int)$_POST['anio'] : null;
  $estado      = $_POST['estado'] ?? 'activo';

  if ($id <= 0) throw new RuntimeException('ID inválido.');
  if ($patente === '' || $descripcion === '') {
    throw new RuntimeException('Patente y descripción son obligatorias.');
  }

  // Patente única (excluyendo mi propio id)
  $dup = db_one("SELECT id FROM vehiculos WHERE patente = ? AND id <> ?", [$patente, $id]);
  if ($dup) throw new RuntimeException('La patente ya existe en otro vehículo.');

  db_exec(
    "UPDATE vehiculos
     SET patente=?, descripcion=?, marca=?, modelo=?, anio=?, estado=?
     WHERE id=?",
    [$patente, $descripcion, $marca, $modelo, $anio, $estado, $id]
  );

  echo json_encode(['ok'=>true]);
} catch (Throwable $e) {
  http_response_code(400);
  echo json_encode(['ok'=>false, 'error'=>$e->getMessage()]);
}
