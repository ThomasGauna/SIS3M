<?php
declare(strict_types=1);
ini_set('display_errors','0'); ini_set('html_errors','0');
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../config/db.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
  echo json_encode(['status'=>'error','message'=>'Método no permitido']); exit;
}

try {
  $cliente_id = (int)($_POST['cliente_id'] ?? 0);
  if ($cliente_id <= 0) throw new Exception('cliente_id requerido');

  $etiqueta   = trim($_POST['etiqueta']   ?? '');
  $direccion  = trim($_POST['direccion']  ?? '');
  $localidad  = trim($_POST['localidad']  ?? '');
  $provincia  = trim($_POST['provincia']  ?? '');
  $pais       = trim($_POST['pais']       ?? 'Argentina');
  $cp         = trim($_POST['cp']         ?? '');
  $estado     = trim($_POST['estado']     ?? 'activo');

  if ($direccion === '') throw new Exception('La dirección es obligatoria');

  $es_principal = isset($_POST['es_principal']) ? (string)$_POST['es_principal'] : '0';
  $es_principal = ($es_principal === '1' || $es_principal === 'on') ? 1 : 0;

  if (!in_array($estado, ['activo','inactivo'], true)) $estado = 'activo';

  db()->beginTransaction();

  if ($es_principal === 1) {
    db()->prepare('UPDATE cliente_direcciones SET es_principal=0 WHERE cliente_id=?')->execute([$cliente_id]);
  }
  $stmt = db()->prepare(
    'INSERT INTO cliente_direcciones
      (cliente_id, etiqueta, direccion, localidad, provincia, pais, cp, es_principal)
      VALUES (?,?,?,?,?,?,?,?)'
  );
  $stmt->execute([
    $cliente_id,$etiqueta,$direccion,$localidad,$provincia,$pais,$cp,$es_principal
  ]);

  $newId = (int)db()->lastInsertId();
  db()->commit();

  echo json_encode(['status'=>'success','message'=>'Dirección creada','id'=>$newId], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
  if (db()->inTransaction()) db()->rollBack();
  http_response_code(422);
  echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
}
