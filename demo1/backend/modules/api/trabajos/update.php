<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../../config/db.php';

try {
  $id              = (int)($_POST['id'] ?? 0);
  $cliente_id      = (int)($_POST['cliente_id'] ?? 0);
  $titulo          = trim($_POST['titulo'] ?? '');
  $descripcion_ini = trim($_POST['descripcion_ini'] ?? '');
  $prioridad       = $_POST['prioridad'] ?? 'media';
  $ubicacion_id    = isset($_POST['ubicacion_id']) && $_POST['ubicacion_id'] !== '' ? (int)$_POST['ubicacion_id'] : null;

  $estado = $_POST['estado'] ?? null;
  $validEstados = ['nuevo','asignado','en_progreso','pendiente','finalizado','cancelado'];
  if ($estado !== null && !in_array($estado, $validEstados, true)) {
    throw new RuntimeException('Estado inválido.');
  }

  if ($id <= 0)         throw new RuntimeException('ID inválido.');
  if ($cliente_id <= 0) throw new RuntimeException('Falta seleccionar cliente.');
  if ($titulo === '')   throw new RuntimeException('Falta el título.');

  $fields = [
    'cliente_id = :cliente_id',
    'titulo = :titulo',
    'descripcion_ini = :descripcion_ini',
    'prioridad = :prioridad',
    'ubicacion_id = :ubicacion_id'
  ];
  $params = [
    ':cliente_id'      => $cliente_id,
    ':titulo'          => $titulo,
    ':descripcion_ini' => $descripcion_ini,
    ':prioridad'       => $prioridad,
    ':ubicacion_id'    => $ubicacion_id,
    ':id'              => $id,
  ];

  if ($estado !== null) {
    $fields[] = 'estado = :estado';
    $params[':estado'] = $estado;
    $fields[] = 'fecha_cierre = ' . (in_array($estado, ['finalizado','cancelado'], true) ? 'CURRENT_TIMESTAMP' : 'NULL');
  }

  $sql = 'UPDATE trabajos SET ' . implode(', ', $fields) . ' WHERE id = :id';
  $st = db()->prepare($sql);
  $st->execute($params);

  echo json_encode(['ok'=>true, 'data'=>['id'=>$id], 'message'=>'Trabajo actualizado']);
} catch (Throwable $e) {
  http_response_code(400);
  echo json_encode(['ok'=>false, 'error'=>$e->getMessage()]);
}
