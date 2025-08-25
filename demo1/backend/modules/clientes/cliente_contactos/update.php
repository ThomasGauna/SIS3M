<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'] ?? '';
if (!in_array($method, ['POST','PUT'], true)) { echo json_encode(['status'=>'error','message'=>'Método no permitido.']); exit; }

try {
  $d = $method==='PUT' ? (json_decode(file_get_contents('php://input'), true) ?? []) : $_POST;
  $id = (int)($d['id'] ?? 0);
  $cliente_id = (int)($d['cliente_id'] ?? 0);
  $nombre  = trim($d['nombre'] ?? '');
  $cargo   = trim($d['cargo'] ?? '');
  $email   = trim($d['email'] ?? '');
  $telefono= trim($d['telefono'] ?? '');
  $principal = isset($d['es_principal']) && (string)$d['es_principal']==='1' ? 1 : 0;

  if ($id<=0 || $cliente_id<=0 || $nombre==='') { echo json_encode(['status'=>'error','message'=>'Datos obligatorios faltantes.']); exit; }

  db_tx(function() use ($id,$cliente_id,$nombre,$cargo,$email,$telefono,$principal,&$aff) {
    if ($principal === 1) {
      db_query('UPDATE cliente_contactos SET es_principal=0 WHERE cliente_id=?', [$cliente_id]);
    }
    $aff = db_query(
      'UPDATE cliente_contactos SET nombre=?, cargo=?, email=?, telefono=?, es_principal=? WHERE id=? AND cliente_id=?',
      [$nombre,$cargo,$email,$telefono,$principal,$id,$cliente_id]
    )->rowCount();
  });

  echo json_encode(['status'=>'success','message'=>$aff>0?'Contacto actualizado.':'Sin cambios.','updated'=>$aff]);
} catch (Throwable $e) { http_response_code(500); echo json_encode(['status'=>'error','message'=>'Error al actualizar contacto.']); }
