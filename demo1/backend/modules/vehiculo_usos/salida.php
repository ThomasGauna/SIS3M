<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json; charset=utf-8');

try {
  $vehiculo_id = isset($_POST['vehiculo_id']) ? (int)$_POST['vehiculo_id'] : 0;
  $usuario_id  = isset($_POST['usuario_id'])  ? (int)$_POST['usuario_id']  : 0;

  $odometro = isset($_POST['odometro_salida']) && $_POST['odometro_salida'] !== '' ? (int)$_POST['odometro_salida'] : null;
  $destino  = trim((string)($_POST['destino'] ?? ''));
  $motivo   = trim((string)($_POST['motivo']  ?? ''));

  if ($vehiculo_id <= 0) throw new RuntimeException('Falta vehículo.');
  if ($usuario_id  <= 0) throw new RuntimeException('Falta usuario.');

  $veh = db_one("SELECT id, estado FROM vehiculos WHERE id=? LIMIT 1", [$vehiculo_id]);
  if (!$veh) throw new RuntimeException('Vehículo inexistente.');
  if (($veh['estado'] ?? '') !== 'activo') {
    throw new RuntimeException('El vehículo no está disponible (no activo).');
  }

  $usr = db_one("SELECT u.id, u.role_id, u.estado
                   FROM usuarios u
                  WHERE u.id = ? LIMIT 1", [$usuario_id]);
  if (!$usr) throw new RuntimeException('Usuario inexistente.');
  if (($usr['estado'] ?? '') !== 'activo') {
    throw new RuntimeException('El usuario no está activo.');
  }
  $roleId = (int)($usr['role_id'] ?? 0);
  if ($roleId <= 0) throw new RuntimeException('El usuario no tiene rol asignado.');

  $permEspecifico = db_one(
    "SELECT 1
       FROM vehiculo_roles_permitidos
      WHERE vehiculo_id = ? AND role_id = ?
      LIMIT 1",
    [$vehiculo_id, $roleId]
  );
  if (!$permEspecifico) {
    throw new RuntimeException('Tu rol no está habilitado para usar este vehículo.');
  }

  $uso_abierto = db_one(
    "SELECT id FROM vehiculo_usos WHERE vehiculo_id=? AND cerrado=0 LIMIT 1",
    [$vehiculo_id]
  );
  if ($uso_abierto) throw new RuntimeException('El vehículo ya está en uso.');

  db_exec(
    "INSERT INTO vehiculo_usos
       (vehiculo_id, usuario_id_salida, odometro_salida, destino, motivo)
     VALUES (?, ?, ?, ?, ?)",
    [$vehiculo_id, $usuario_id, $odometro, $destino, $motivo]
  );

  echo json_encode(['ok' => true], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
  http_response_code(400);
  echo json_encode(['ok' => false, 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
