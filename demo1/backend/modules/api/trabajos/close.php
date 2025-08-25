<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json; charset=utf-8');

try{
  $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
  $id = (int)($input['id'] ?? 0);
  if (!$id) throw new InvalidArgumentException('id requerido');

  // marcamos finalizado si no lo está
  $sql = "UPDATE trabajos
          SET estado='finalizado', fecha_cierre=NOW()
          WHERE id=:id AND estado <> 'finalizado' AND estado <> 'cancelado'";
  db_query($sql, [':id'=>$id]);

  echo json_encode(['ok'=>true]);
}catch(Throwable $e){
  http_response_code(400);
  echo json_encode(['ok'=>false,'error'=>$e->getMessage()]);
}