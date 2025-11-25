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
  $origen_id   = (int)($_POST['ubic_origen_id'] ?? 0);
  $destino_id  = (int)($_POST['ubic_destino_id'] ?? 0);
  $usuario_id  = isset($_POST['usuario_id']) ? (int)$_POST['usuario_id'] : null;
  $notas       = $_POST['notas'] ?? null;

  if ($producto_id<=0 || $cantidad<=0 || $origen_id<=0 || $destino_id<=0 || $origen_id===$destino_id) {
    echo json_encode(['status'=>'error','message'=>'Datos inválidos.']); exit;
  }

  db_tx(function() use ($producto_id,$cantidad,$origen_id,$destino_id,$usuario_id,$notas) {
    $p = db_query('SELECT stock_actual FROM productos WHERE id=? FOR UPDATE', [$producto_id])->fetch();
    if (!$p) throw new RuntimeException('Producto inexistente');
    if ((float)$p['stock_actual'] < $cantidad) throw new RuntimeException('Stock insuficiente');

    db_query(
      'INSERT INTO movimientos_productos (producto_id,tipo,cantidad,ubic_origen_id,usuario_id,origen,ref_tipo,ref_id,notas)
       VALUES (?,?,?,?,?,"manual","TRANSFER",NULL,?)',
      [$producto_id,'salida',$cantidad,$origen_id,$usuario_id,$notas]
    );
    db_query('UPDATE productos SET stock_actual = stock_actual - ? WHERE id=?', [$cantidad,$producto_id]);

    db_query(
      'INSERT INTO movimientos_productos (producto_id,tipo,cantidad,ubic_destino_id,usuario_id,origen,ref_tipo,ref_id,notas)
       VALUES (?,?,?,?,?,"manual","TRANSFER",NULL,?)',
      [$producto_id,'entrada',$cantidad,$destino_id,$usuario_id,$notas]
    );
    db_query('UPDATE productos SET stock_actual = stock_actual + ? WHERE id=?', [$cantidad,$producto_id]);
  });

  echo json_encode(['status'=>'success','message'=>'Transferencia realizada.']);
} catch (Throwable $e) {
  http_response_code(400);
  echo json_encode(['status'=>'error','message'=>$e->getMessage() ?: 'Error en transferencia.']);
}
