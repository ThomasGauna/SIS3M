<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../../config/db.php';

try {
  $cliente_id      = (int)($_POST['cliente_id'] ?? 0);
  $titulo          = trim($_POST['titulo'] ?? '');
  $descripcion_ini = trim($_POST['descripcion_ini'] ?? '');
  $prioridad       = $_POST['prioridad'] ?? 'media';
  $ubicacion_id    = isset($_POST['ubicacion_id']) && $_POST['ubicacion_id'] !== '' ? (int)$_POST['ubicacion_id'] : null;

  if ($cliente_id <= 0)  throw new RuntimeException('Falta seleccionar cliente.');
  if ($titulo === '')    throw new RuntimeException('Falta el título.');

  $sql = "INSERT INTO trabajos
          (cliente_id, titulo, descripcion_ini, prioridad, estado, ubicacion_id)
          VALUES (:cliente_id, :titulo, :descripcion_ini, :prioridad, :estado, :ubicacion_id)";
  $st = db()->prepare($sql);
  $st->execute([
    ':cliente_id'      => $cliente_id,
    ':titulo'          => $titulo,
    ':descripcion_ini' => $descripcion_ini,
    ':prioridad'       => $prioridad,
    ':estado'          => 'nuevo',     // enum válido
    ':ubicacion_id'    => $ubicacion_id,
  ]);

  echo json_encode(['ok'=>true, 'data'=>['id'=>(int)db()->lastInsertId()], 'message'=>'Trabajo creado']);
} catch (Throwable $e) {
  http_response_code(400);
  echo json_encode(['ok'=>false, 'error'=>$e->getMessage()]);
}
