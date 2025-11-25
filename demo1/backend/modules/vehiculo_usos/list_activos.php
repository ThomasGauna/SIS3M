<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json; charset=utf-8');

try {
  $vehiculoId = isset($_GET['vehiculo_id']) && $_GET['vehiculo_id'] !== '' ? (int)$_GET['vehiculo_id'] : null;
  $usuarioId  = isset($_GET['usuario_id'])  && $_GET['usuario_id']  !== '' ? (int)$_GET['usuario_id']  : null;

  $where  = ['u.cerrado = 0'];
  $params = [];

  if ($vehiculoId !== null) { $where[] = 'u.vehiculo_id = ?';        $params[] = $vehiculoId; }
  if ($usuarioId  !== null) { $where[] = 'u.usuario_id_salida = ?';  $params[] = $usuarioId;  }

  $sql = "
    SELECT
      u.id,
      u.vehiculo_id,
      v.patente,
      v.descripcion,
      u.usuario_id_salida,
      us.nombre     AS usuario_salida,
      u.fecha_salida,
      u.odometro_salida,
      u.destino,
      u.motivo
    FROM vehiculo_usos u
    JOIN vehiculos v ON v.id = u.vehiculo_id
    JOIN usuarios  us ON us.id = u.usuario_id_salida
    WHERE " . implode(' AND ', $where) . "
    ORDER BY u.fecha_salida DESC, u.id DESC
  ";

  $rows = db_all($sql, $params);
  echo json_encode($rows, JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['ok' => false, 'error' => 'No se pudo listar usos activos'], JSON_UNESCAPED_UNICODE);
}
