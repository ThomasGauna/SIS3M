<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json; charset=utf-8');

try {
  $vehiculo_id = isset($_POST['vehiculo_id']) ? (int)$_POST['vehiculo_id'] : 0;
  $usuario_id  = isset($_POST['usuario_id'])  ? (int)$_POST['usuario_id']  : 0;

  $odometro    = (isset($_POST['odometro_salida']) && $_POST['odometro_salida'] !== '')
                  ? (int)$_POST['odometro_salida'] : null;
  $destino     = trim((string)($_POST['destino'] ?? ''));
  $motivo      = trim((string)($_POST['motivo']  ?? ''));
  $firmaPng    = (string)($_POST['firma_salida_png'] ?? '');

  if ($vehiculo_id <= 0) throw new RuntimeException('Falta vehículo.');
  if ($usuario_id  <= 0) throw new RuntimeException('Falta usuario.');
  if ($firmaPng === '')  throw new RuntimeException('Falta firma de salida.');

  $veh = db_one("SELECT id, estado FROM vehiculos WHERE id=? LIMIT 1", [$vehiculo_id]);
  if (!$veh) throw new RuntimeException('Vehículo inexistente.');
  if (($veh['estado'] ?? '') !== 'activo') throw new RuntimeException('El vehículo no está disponible.');

  $usr = db_one("SELECT id, role_id, estado, nombre, dni_legajo FROM usuarios WHERE id=? LIMIT 1", [$usuario_id]);
  if (!$usr) throw new RuntimeException('Usuario inexistente.');
  if (($usr['estado'] ?? '') !== 'activo') throw new RuntimeException('El usuario no está activo.');
  $roleId = (int)($usr['role_id'] ?? 0);
  if ($roleId <= 0) throw new RuntimeException('El usuario no tiene rol asignado.');

  $perm = db_one(
    "SELECT 1 FROM vehiculo_roles_permitidos WHERE vehiculo_id=? AND role_id=? LIMIT 1",
    [$vehiculo_id, $roleId]
  );
  if (!$perm) throw new RuntimeException('Tu rol no está habilitado para usar este vehículo.');

  $abierto = db_one("SELECT id FROM vehiculo_usos WHERE vehiculo_id=? AND cerrado=0 LIMIT 1", [$vehiculo_id]);
  if ($abierto) throw new RuntimeException('El vehículo ya está en uso.');

  db_exec(
    "INSERT INTO vehiculo_usos
       (vehiculo_id, usuario_id_salida, fecha_salida, odometro_salida, destino, motivo, cerrado)
     VALUES
       (?, ?, NOW(), ?, ?, ?, 0)",
    [$vehiculo_id, $usuario_id, $odometro, $destino, $motivo]
  );
  $usoIdRow = db_one("SELECT LAST_INSERT_ID() AS id");
  $usoId = (int)($usoIdRow['id'] ?? 0);
  if ($usoId <= 0) throw new RuntimeException('No se pudo registrar la salida.');

  $dir = __DIR__ . '/../../uploads/vehiculo_usos';
  if (!is_dir($dir)) { @mkdir($dir, 0775, true); }

  if (!preg_match('#^data:image/png;base64,(.+)$#', $firmaPng, $m)) {
    throw new RuntimeException('Formato de firma no reconocido.');
  }
  $pngBin = base64_decode($m[1], true);
  if ($pngBin === false) throw new RuntimeException('Firma inválida.');

  $pathRel = "uploads/vehiculo_usos/{$usoId}_salida.png";
  $pathAbs = __DIR__ . '/../../' . $pathRel;
  if (file_put_contents($pathAbs, $pngBin) === false) {
    throw new RuntimeException('No se pudo guardar la firma.');
  }

  $uNombre = (string)($usr['nombre'] ?? '');
  $uDni    = (string)($usr['dni_legajo'] ?? '');

  db_exec(
    "UPDATE vehiculo_usos
        SET firma_salida_path=?, firma_salida_nombre=?, firma_salida_dni=?, firma_salida_ts=NOW()
      WHERE id=?",
    [$pathRel, $uNombre, $uDni, $usoId]
  );

  echo json_encode(['ok' => true, 'id' => $usoId], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
  http_response_code(400);
  echo json_encode(['ok' => false, 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
