<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json');

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
  echo json_encode(['status'=>'error','message'=>'Método no permitido.']); exit;
}

try {
  $producto_id = (int)($_POST['producto_id'] ?? 0);
  $cantidad    = (float)($_POST['cantidad'] ?? 0);
  $origen      = $_POST['origen'] ?? 'ticket';   // 'ticket','pedido',...
  $ref_id      = (int)($_POST['ref_id'] ?? 0);

  if ($producto_id<=0 || $cantidad<=0 || $ref_id<=0) {
    echo json_encode(['status'=>'error','message'=>'Datos inválidos.']); exit;
  }

  db_tx(function() use ($producto_id,$cantidad,$origen,$ref_id) {
    // No movemos stock físico; solo marcamos reserva
    db_query(
      'INSERT INTO stock_reservas (producto_id,cantidad,origen,ref_id) VALUES (?,?,?,?)',
      [$producto_id,$cantidad,$origen,$ref_id]
    );
  });

  echo json_encode(['status'=>'success','message'=>'Reserva registrada.']);
} catch (Throwable $e) {
  http_response_code(400);
  echo json_encode(['status'=>'error','message'=>$e->getMessage() ?: 'Error al reservar.']);
}
