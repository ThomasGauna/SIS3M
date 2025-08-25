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

  $nombre   = trim($_POST['nombre']   ?? '');
  $cargo    = trim($_POST['cargo']    ?? '');
  $email    = trim($_POST['email']    ?? '');
  $telefono = trim($_POST['telefono'] ?? '');

  if ($nombre === '') throw new Exception('El nombre del contacto es obligatorio');

  $es_principal = isset($_POST['es_principal']) ? (string)$_POST['es_principal'] : '0';
  $es_principal = ($es_principal === '1' || $es_principal === 'on') ? 1 : 0;


  db()->beginTransaction();

  if ($es_principal === 1) {
    $q = db()->prepare('UPDATE cliente_contactos SET es_principal=0 WHERE cliente_id=?');
    $q->execute([$cliente_id]);
  }

  $stmt = db()->prepare(
    'INSERT INTO cliente_contactos
      (cliente_id, nombre, cargo, email, telefono, es_principal)
     VALUES (?,?,?,?,?,?)'
  );
  $stmt->execute([$cliente_id, $nombre, $cargo, $email, $telefono, $es_principal]);

  $newId = (int)db()->lastInsertId();
  db()->commit();

  echo json_encode(['status'=>'success','message'=>'Contacto creado','id'=>$newId], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
  if (db()->inTransaction()) db()->rollBack();
  http_response_code(422);
  echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
}
