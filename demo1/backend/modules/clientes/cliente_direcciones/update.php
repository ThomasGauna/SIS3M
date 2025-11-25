<?php
declare(strict_types=1);
ini_set('display_errors','0'); ini_set('html_errors','0');
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../config/db.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
  echo json_encode(['status'=>'error','message'=>'Método no permitido']); exit;
}

try {
  $id         = (int)($_POST['id'] ?? 0);
  $cliente_id = (int)($_POST['cliente_id'] ?? 0);
  $etiqueta   = trim($_POST['etiqueta'] ?? '');
  $direccion  = trim($_POST['direccion'] ?? '');
  $localidad  = trim($_POST['localidad'] ?? '');
  $provincia  = trim($_POST['provincia'] ?? '');
  $pais       = trim($_POST['pais'] ?? 'Argentina');
  $cp         = trim($_POST['cp'] ?? '');
  $es_pri_in  = ($_POST['es_principal'] ?? '0') === '1';
  $es_principal = $es_pri_in ? 1 : 0;

  if ($id<=0 || $cliente_id<=0) { echo json_encode(['status'=>'error','message'=>'ID/cliente_id inválidos']); exit; }
  if ($direccion === '') { echo json_encode(['status'=>'error','message'=>'La dirección es obligatoria']); exit; }

  db()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  db_tx(function() use ($id,$cliente_id,$etiqueta,$direccion,$localidad,$provincia,$pais,$cp,$es_principal) {
    if ($es_principal === 1) {
      db_query('UPDATE cliente_direcciones SET es_principal = 0 WHERE cliente_id = ?', [$cliente_id]);
    }

    db_query(
      'UPDATE cliente_direcciones
         SET etiqueta=?, direccion=?, localidad=?, provincia=?, pais=?, cp=?, es_principal=?
       WHERE id=? AND cliente_id=?',
      [$etiqueta,$direccion,$localidad,$provincia,$pais,$cp,$es_principal,$id,$cliente_id]
    );
  });

  echo json_encode(['status'=>'success','message'=>'Dirección actualizada'], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['status'=>'error','message'=>'Error actualizando dirección','error'=>$e->getMessage()]);
}
