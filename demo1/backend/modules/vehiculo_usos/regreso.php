<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json; charset=utf-8');

try {
  $uso_id     = isset($_POST['uso_id']) ? (int)$_POST['uso_id'] : 0;
  $usuario_id = isset($_POST['usuario_id']) ? (int)$_POST['usuario_id'] : 0;
  $odometro   = (isset($_POST['odometro_regreso']) && $_POST['odometro_regreso'] !== '')
                  ? (int)$_POST['odometro_regreso'] : null;
  $obs        = trim((string)($_POST['observaciones'] ?? ''));
  $firmaPng   = (string)($_POST['firma_regreso_png'] ?? '');

  if ($uso_id <= 0)     throw new RuntimeException('Falta uso.');
  if ($usuario_id <= 0) throw new RuntimeException('Falta usuario.');
  if ($firmaPng === '') throw new RuntimeException('Falta firma de regreso.');

  $uso = db_one(
    "SELECT id, vehiculo_id, usuario_id_salida, fecha_salida, odometro_salida, cerrado
       FROM vehiculo_usos
      WHERE id = ?",
    [$uso_id]
  );
  if (!$uso) throw new RuntimeException('Uso no encontrado.');
  if ((int)$uso['cerrado'] === 1) throw new RuntimeException('El uso ya fue cerrado.');

  $usr = db_one("SELECT id, estado, nombre, dni_legajo FROM usuarios WHERE id=? LIMIT 1", [$usuario_id]);
  if (!$usr) throw new RuntimeException('Usuario inexistente.');
  if (($usr['estado'] ?? '') !== 'activo') throw new RuntimeException('El usuario no está activo.');

  if ($uso['odometro_salida'] !== null && $odometro !== null) {
    if ((int)$odometro < (int)$uso['odometro_salida']) {
      throw new RuntimeException('El odómetro de regreso no puede ser menor al de salida.');
    }
  }

  $dir = __DIR__ . '/../../uploads/vehiculo_usos';
  if (!is_dir($dir)) { @mkdir($dir, 0775, true); }

  if (!preg_match('#^data:image/png;base64,(.+)$#', $firmaPng, $m)) {
    throw new RuntimeException('Formato de firma no reconocido.');
  }
  $pngBin = base64_decode($m[1], true);
  if ($pngBin === false) throw new RuntimeException('Firma inválida.');

  $pathRel = "uploads/vehiculo_usos/{$uso_id}_regreso.png";
  $pathAbs = __DIR__ . '/../../' . $pathRel;
  if (file_put_contents($pathAbs, $pngBin) === false) {
    throw new RuntimeException('No se pudo guardar la firma.');
  }

  $uNombre = (string)($usr['nombre'] ?? '');
  $uDni    = (string)($usr['dni_legajo'] ?? '');

  db_exec(
    "UPDATE vehiculo_usos
        SET usuario_id_regreso = ?,
            fecha_regreso      = NOW(),
            odometro_regreso   = ?,
            observaciones      = ?,
            firma_regreso_path = ?,
            firma_regreso_nombre = ?,
            firma_regreso_dni    = ?,
            firma_regreso_ts   = NOW(),
            cerrado            = 1
      WHERE id = ?",
    [$usuario_id, $odometro, $obs, $pathRel, $uNombre, $uDni, $uso_id]
  );

  echo json_encode(['ok' => true], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
  http_response_code(400);
  echo json_encode(['ok' => false, 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
