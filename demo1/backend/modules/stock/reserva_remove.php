<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'] ?? '';
if (!in_array($method, ['POST','DELETE'], true)) {
  echo json_encode(['status'=>'error','message'=>'Método no permitido.']); exit;
}

try {
  $producto_id = (int)(($_POST['producto_id'] ?? $_GET['producto_id']) ?? 0);
  $origen      = ($_POST['origen'] ?? $_GET['origen']) ?? 'ticket';
  $ref_id      = (int)(($_POST['ref_id'] ?? $_GET['ref_id']) ?? 0);

  if ($producto_id<=0 || $ref_id<=0) {
    echo json_encode(['status'=>'error','message'=>'Datos inválidos.']); exit;
  }

  $del = db_query('DELETE FROM stock_reservas WHERE producto_id=? AND origen=? AND ref_id=?',
    [$producto_id,$origen,$ref_id])->rowCount();

  echo json_encode(['status'=>'success','message'=>$del>0?'Reserva eliminada.':'No había reserva.','deleted'=>$del]);
} catch (Throwable $e) {
  http_response_code(400);
  echo json_encode(['status'=>'error','message'=>$e->getMessage() ?: 'Error al eliminar reserva.']);
}
