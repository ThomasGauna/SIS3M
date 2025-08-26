<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json; charset=utf-8');

try {
  $uso_id     = isset($_POST['uso_id']) ? (int)$_POST['uso_id'] : 0;
  $usuario_id = isset($_POST['usuario_id']) ? (int)$_POST['usuario_id'] : 0;
  $odometro   = isset($_POST['odometro_regreso']) && $_POST['odometro_regreso'] !== ''
                  ? (int)$_POST['odometro_regreso'] : null;
  $obs        = trim((string)($_POST['observaciones'] ?? ''));

  if ($uso_id <= 0)     throw new RuntimeException('Falta uso.');
  if ($usuario_id <= 0) throw new RuntimeException('Falta usuario.');

  $uso = db_one(
    "SELECT u.id, u.vehiculo_id, u.usuario_id_salida, u.fecha_salida,
            u.odometro_salida, u.cerrado
       FROM vehiculo_usos u
      WHERE u.id = ?",
    [$uso_id]
  );
  if (!$uso) throw new RuntimeException('Uso no encontrado.');
  if ((int)$uso['cerrado'] === 1) throw new RuntimeException('El uso ya fue cerrado.');

  $usr = db_one("SELECT id, estado FROM usuarios WHERE id=? LIMIT 1", [$usuario_id]);
  if (!$usr) throw new RuntimeException('Usuario inexistente.');
  if (($usr['estado'] ?? '') !== 'activo') {
    throw new RuntimeException('El usuario no está activo.');
  }

  if ($uso['odometro_salida'] !== null && $odometro !== null) {
    if ((int)$odometro < (int)$uso['odometro_salida']) {
      throw new RuntimeException('El odómetro de regreso no puede ser menor al de salida.');
    }
  }

  // (Opcional) Si querés validar contra el último regreso previo del mismo vehículo:
  // $ultimo = db_one("SELECT odometro_regreso FROM vehiculo_usos
  //                   WHERE vehiculo_id=? AND cerrado=1
  //                   ORDER BY fecha_regreso DESC LIMIT 1", [$uso['vehiculo_id']]);
  // if ($ultimo && $odometro !== null && (int)$odometro < (int)$ultimo['odometro_regreso']) {
  //   throw new RuntimeException('El odómetro de regreso no puede ser menor al último registrado.');
  // }

  db_exec(
    "UPDATE vehiculo_usos
        SET usuario_id_regreso = ?,
            fecha_regreso      = NOW(),
            odometro_regreso   = ?,
            observaciones      = ?,
            cerrado            = 1
      WHERE id = ?",
    [$usuario_id, $odometro, $obs, $uso_id]
  );

  echo json_encode(['ok' => true], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
  http_response_code(400);
  echo json_encode(['ok' => false, 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
