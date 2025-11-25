<?php
declare(strict_types=1);
require_once __DIR__ . '/../../../config/db.php';
header('Content-Type: application/json; charset=utf-8');

try{
  $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
  if (!$id) throw new InvalidArgumentException('id requerido');

  $sql = "SELECT t.*,
             TRIM(CONCAT(c.nombre,' ',COALESCE(c.apellido,''))) AS cliente_nombre,
             u.nombre AS ubicacion_nombre
          FROM trabajos t
          LEFT JOIN clientes c   ON c.id=t.cliente_id
          LEFT JOIN ubicaciones u ON u.id=t.ubicacion_id
          WHERE t.id=:id";
  $st = db_query($sql, [':id'=>$id]);
  $row = $st->fetch();
  if (!$row) throw new RuntimeException('No existe');

  echo json_encode(['ok'=>true,'item'=>$row]);
}catch(Throwable $e){
  http_response_code(404);
  echo json_encode(['ok'=>false,'error'=>$e->getMessage()]);
}
