<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json; charset=utf-8');

try {
  $id     = (int)($_POST['id'] ?? 0);
  $nombre = trim($_POST['nombre'] ?? '');
  $email  = trim($_POST['email']  ?? '');
  $estado = $_POST['estado'] ?? 'activo';
  $roleId = isset($_POST['role_id']) && $_POST['role_id'] !== '' ? (int)$_POST['role_id'] : 0;

  if ($id <= 0)       throw new RuntimeException('ID inválido.');
  if ($nombre === '') throw new RuntimeException('El nombre es obligatorio.');
  if ($roleId <= 0)   throw new RuntimeException('El rol es obligatorio.');

  if ($email !== '') {
    $dup = db_one("SELECT id FROM usuarios WHERE email = ? AND id <> ?", [$email, $id]);
    if ($dup) throw new RuntimeException('Ese email ya está registrado en otro usuario.');
  }

  db_exec(
    "UPDATE usuarios
        SET nombre=?, email=?, estado=?, role_id=?
      WHERE id=?",
    [$nombre, ($email ?: null), $estado, $roleId, $id]
  );

  echo json_encode(['ok'=>true]);
} catch (Throwable $e) {
  http_response_code(400);
  echo json_encode(['ok'=>false, 'error'=>$e->getMessage()]);
}
