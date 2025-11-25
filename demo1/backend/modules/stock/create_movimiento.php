<?php
declare(strict_types=1);
require_once __DIR__ . '../../../config/db.php';
header('Content-Type: application/json');

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
  echo json_encode(['status'=>'error','message'=>'Método no permitido.']); exit;
}

try {
  $producto_id     = (int)($_POST['producto_id'] ?? 0);
  $tipo            = $_POST['tipo'] ?? '';
  $cantidad        = (float)($_POST['cantidad'] ?? 0);
  $costo_unitario  = isset($_POST['costo_unitario']) && $_POST['costo_unitario']!=='' ? (float)$_POST['costo_unitario'] : null;
  $ubic_origen_id  = isset($_POST['ubic_origen_id']) ? (int)$_POST['ubic_origen_id'] : null;
  $ubic_destino_id = isset($_POST['ubic_destino_id']) ? (int)$_POST['ubic_destino_id'] : null;
  $usuario_id      = isset($_POST['usuario_id']) ? (int)$_POST['usuario_id'] : null;
  $origen          = $_POST['origen']   ?? 'manual';
  $ref_tipo        = $_POST['ref_tipo'] ?? null;
  $ref_id          = isset($_POST['ref_id']) && $_POST['ref_id']!=='' ? (int)$_POST['ref_id'] : null;
  $notas           = $_POST['notas'] ?? null;

  if ($producto_id<=0 || !in_array($tipo, ['entrada','salida'], true) || $cantidad<=0) {
    echo json_encode(['status'=>'error','message'=>'Datos inválidos.']); exit;
  }

  db_tx(function() use ($producto_id,$tipo,$cantidad,$costo_unitario,$ubic_origen_id,$ubic_destino_id,$usuario_id,$origen,$ref_tipo,$ref_id,$notas,&$movId) {
    $prod = db_query('SELECT id, stock_actual FROM productos WHERE id=? FOR UPDATE', [$producto_id])->fetch();
    if (!$prod) throw new RuntimeException('Producto inexistente');

    $stock = (float)$prod['stock_actual'];
    if ($tipo === 'salida') {
      if ($stock < $cantidad) throw new RuntimeException('Stock insuficiente');
      $stock -= $cantidad;
    } else { // entrada
      $stock += $cantidad;
    }

    db_query(
      'INSERT INTO movimientos_productos
       (producto_id, tipo, cantidad, costo_unitario, ubic_origen_id, ubic_destino_id, usuario_id, origen, ref_tipo, ref_id, notas)
       VALUES (?,?,?,?,?,?,?,?,?,?,?)',
      [$producto_id,$tipo,$cantidad,$costo_unitario,$ubic_origen_id,$ubic_destino_id,$usuario_id,$origen,$ref_tipo,$ref_id,$notas]
    );
    $movId = (int)db()->lastInsertId();

    db_query('UPDATE productos SET stock_actual=? WHERE id=?', [$stock, $producto_id]);
  });

  echo json_encode(['status'=>'success','message'=>'Movimiento registrado.','id'=>$movId]);
} catch (Throwable $e) {
  http_response_code(400);
  echo json_encode(['status'=>'error','message'=>$e->getMessage() ?: 'Error al registrar movimiento.']);
}
