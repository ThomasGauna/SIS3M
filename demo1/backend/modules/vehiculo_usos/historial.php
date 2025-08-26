<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json; charset=utf-8');

try {
  $vehiculoId = isset($_GET['vehiculo_id']) && $_GET['vehiculo_id'] !== '' ? (int)$_GET['vehiculo_id'] : null;

  $usuarioIdSalida  = isset($_GET['usuario_id_salida'])  && $_GET['usuario_id_salida']  !== '' ? (int)$_GET['usuario_id_salida']  : null;
  $usuarioIdRegreso = isset($_GET['usuario_id_regreso']) && $_GET['usuario_id_regreso'] !== '' ? (int)$_GET['usuario_id_regreso'] : null;
  $usuarioIdAny     = isset($_GET['usuario_id'])         && $_GET['usuario_id']         !== '' ? (int)$_GET['usuario_id']         : null;

  $cerrado = isset($_GET['cerrado']) && $_GET['cerrado'] !== '' ? (int)$_GET['cerrado'] : null;

  $fechaCampo = strtolower(trim($_GET['fecha_campo'] ?? 'salida'));
  $fechaCampo = ($fechaCampo === 'regreso') ? 'fecha_regreso' : 'fecha_salida';

  $desde = trim($_GET['desde'] ?? '');
  $hasta = trim($_GET['hasta'] ?? '');
  $desdeDT = ($desde !== '') ? ($desde . ' 00:00:00') : null;
  $hastaDT = ($hasta !== '') ? ($hasta . ' 23:59:59') : null;

  $q = trim($_GET['q'] ?? '');

  $order = strtolower(trim($_GET['order'] ?? 'desc'));
  $order = ($order === 'asc') ? 'ASC' : 'DESC';

  $limit  = isset($_GET['limit'])  ? max(1, min(500, (int)$_GET['limit'])) : 100;
  $offset = isset($_GET['offset']) ? max(0, (int)$_GET['offset']) : 0;

  $where  = ['1=1'];
  $params = [];

  if ($vehiculoId !== null)        { $where[] = 'u.vehiculo_id = ?';           $params[] = $vehiculoId; }
  if ($usuarioIdSalida !== null)   { $where[] = 'u.usuario_id_salida = ?';     $params[] = $usuarioIdSalida; }
  if ($usuarioIdRegreso !== null)  { $where[] = 'u.usuario_id_regreso = ?';    $params[] = $usuarioIdRegreso; }
  if ($usuarioIdAny !== null)      { $where[] = '(u.usuario_id_salida = ? OR u.usuario_id_regreso = ?)'; $params[] = $usuarioIdAny; $params[] = $usuarioIdAny; }
  if ($cerrado !== null)           { $where[] = 'u.cerrado = ?';               $params[] = $cerrado; }
  if ($desdeDT !== null)           { $where[] = "u.$fechaCampo >= ?";          $params[] = $desdeDT; }
  if ($hastaDT !== null)           { $where[] = "u.$fechaCampo <= ?";          $params[] = $hastaDT; }

  if ($q !== '') {
    $like = "%$q%";
    $where[] = '(v.patente LIKE ? OR v.descripcion LIKE ? OR us.nombre LIKE ? OR ur.nombre LIKE ? OR u.destino LIKE ? OR u.motivo LIKE ?)';
    array_push($params, $like, $like, $like, $like, $like, $like);
  }

  $sql = "
    SELECT
      u.id,
      u.vehiculo_id,
      v.patente,
      v.descripcion AS vehiculo,
      u.usuario_id_salida,
      us.nombre     AS usuario_salida,
      u.fecha_salida,
      u.odometro_salida,
      u.destino,
      u.motivo,
      u.usuario_id_regreso,
      ur.nombre     AS usuario_regreso,
      u.fecha_regreso,
      u.odometro_regreso,
      u.observaciones,
      u.cerrado
    FROM vehiculo_usos u
    JOIN vehiculos v   ON v.id  = u.vehiculo_id
    JOIN usuarios  us  ON us.id = u.usuario_id_salida
    LEFT JOIN usuarios ur  ON ur.id = u.usuario_id_regreso
    WHERE " . implode(' AND ', $where) . "
    ORDER BY u.$fechaCampo $order, u.id $order
    LIMIT $limit OFFSET $offset
  ";

  $rows = db_all($sql, $params);
  echo json_encode($rows, JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['ok' => false, 'error' => 'No se pudo obtener el historial'], JSON_UNESCAPED_UNICODE);
}
