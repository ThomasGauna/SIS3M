<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json; charset=utf-8');

try{
  $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
  $cliente_id = (int)($input['cliente_id'] ?? 0);
  $titulo     = trim($input['titulo'] ?? '');
  $descripcion= trim($input['descripcion_ini'] ?? '');
  $prioridad  = $input['prioridad'] ?? 'media';
  $ubicacion  = $input['ubicacion_id'] ?? null;

  if (!$cliente_id || $titulo===''){ throw new InvalidArgumentException('cliente_id y título son obligatorios.'); }
  if (!in_array($prioridad, ['baja','media','alta','critica'], true)) $prioridad = 'media';

  $sql = "INSERT INTO trabajos (cliente_id, titulo, descripcion_ini, prioridad, ubicacion_id)
          VALUES (:cliente_id, :titulo, :descripcion_ini, :prioridad, :ubicacion_id)";
  db_query($sql, [
    ':cliente_id'=>$cliente_id,
    ':titulo'=>$titulo,
    ':descripcion_ini'=>$descripcion ?: null,
    ':prioridad'=>$prioridad,
    ':ubicacion_id'=>$ubicacion ? (int)$ubicacion : null,
  ]);
  $id = (int)db()->lastInsertId();

  echo json_encode(['ok'=>true,'id'=>$id]);
}catch(Throwable $e){
  http_response_code(400);
  echo json_encode(['ok'=>false,'error'=>$e->getMessage()]);
}
