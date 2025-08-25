<?php
declare(strict_types=1);
require_once __DIR__.'/../../../config/db.php';
header('Content-Type: application/json; charset=utf-8');

$id = (int)($_POST['id'] ?? 0);
if ($id<=0){ echo json_encode(['status'=>'error','message'=>'ID inválido']); exit; }

db_tx(function() use ($id){
  $row = db_query('SELECT cliente_id FROM cliente_direcciones WHERE id=? LIMIT 1', [$id])->fetch(PDO::FETCH_ASSOC);
  if (!$row) { throw new RuntimeException('Dirección no existe'); }
  $cid = (int)$row['cliente_id'];

  db_query('UPDATE cliente_direcciones SET es_principal=NULL WHERE cliente_id=?', [$cid]);
  db_query('UPDATE cliente_direcciones SET es_principal=1   WHERE id=? AND cliente_id=?', [$id,$cid]);
});

echo json_encode(['status'=>'success','message'=>'Principal actualizada']);
