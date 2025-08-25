<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json; charset=utf-8');

try{
  $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
  $id = (int)($input['id'] ?? 0);
  if (!$id) throw new InvalidArgumentException('id requerido');

  $fields = [];
  $params = [':id'=>$id];

  if (isset($input['cliente_id'])) { $fields[] = "cliente_id=:cliente_id"; $params[':cliente_id']=(int)$input['cliente_id']; }
  if (isset($input['titulo']))     { $fields[] = "titulo=:titulo"; $params[':titulo']=trim($input['titulo']); }
  if (array_key_exists('descripcion_ini',$input)) { $fields[]="descripcion_ini=:descripcion_ini"; $params[':descripcion_ini']=trim((string)$input['descripcion_ini']) ?: null; }
  if (isset($input['prioridad']))  {
    $p = in_array($input['prioridad'], ['baja','media','alta','critica'],true) ? $input['prioridad'] : 'media';
    $fields[]="prioridad=:prioridad"; $params[':prioridad']=$p;
  }
  if (array_key_exists('ubicacion_id',$input)) { $fields[]="ubicacion_id=:ubicacion_id"; $params[':ubicacion_id']=$input['ubicacion_id']? (int)$input['ubicacion_id'] : null; }
  if (isset($input['estado'])) {
    $e = $input['estado'];
    if (!in_array($e, ['nuevo','asignado','en_progreso','pendiente','finalizado','cancelado'], true)) {
      throw new InvalidArgumentException('estado inválido');
    }
    $fields[]="estado=:estado"; $params[':estado']=$e;
  }

  if (!$fields) throw new InvalidArgumentException('Nada para actualizar');

  $sql = "UPDATE trabajos SET ".implode(',', $fields)." WHERE id=:id";
  db_query($sql, $params);
  echo json_encode(['ok'=>true]);
}catch(Throwable $e){
  http_response_code(400);
  echo json_encode(['ok'=>false,'error'=>$e->getMessage()]);
}
