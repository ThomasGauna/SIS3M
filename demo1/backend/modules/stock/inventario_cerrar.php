<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json');

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
  echo json_encode(['status'=>'error','message'=>'Método no permitido.']); exit;
}

try {
  $inventario_id = (int)($_POST['inventario_id'] ?? 0);
  $items_json    = $_POST['items'] ?? '[]';
  $items         = json_decode($items_json, true);
  if ($inventario_id<=0 || !is_array($items)) {
    echo json_encode(['status'=>'error','message'=>'Datos inválidos.']); exit;
  }

  db_tx(function() use ($inventario_id,$items,&$ajustes) {
    $cab = db_query('SELECT id, estado FROM inventarios WHERE id=? FOR UPDATE', [$inventario_id])->fetch();
    if (!$cab) throw new RuntimeException('Inventario inexistente');
    if ($cab['estado'] !== 'abierto') throw new RuntimeException('Inventario ya cerrado');

    $ajustes = [];

    foreach ($items as $it) {
      $producto_id = (int)($it['producto_id'] ?? 0);
      $conteo      = (float)($it['conteo'] ?? 0);
      if ($producto_id<=0) continue;

      $p = db_query('SELECT id, stock_actual FROM productos WHERE id=? FOR UPDATE', [$producto_id])->fetch();
      if (!$p) continue;

      $actual = (float)$p['stock_actual'];
      $diff = $conteo - $actual;

      $exists = db_query('SELECT id FROM inventario_items WHERE inventario_id=? AND producto_id=?', [$inventario_id,$producto_id])->fetch();
      if ($exists) {
        db_query('UPDATE inventario_items SET conteo=? WHERE id=?', [$conteo, $exists['id']]);
      } else {
        db_query('INSERT INTO inventario_items (inventario_id, producto_id, conteo) VALUES (?,?,?)', [$inventario_id,$producto_id,$conteo]);
      }

      if (abs($diff) >= 0.00001) {
        if ($diff > 0) {
          db_query(
            'INSERT INTO movimientos_productos
             (producto_id,tipo,cantidad,ubic_destino_id,usuario_id,origen,ref_tipo,ref_id,notas)
             VALUES (?,?,?,?,NULL,"inventario","INV",?,?)',
            [$producto_id,'entrada',$diff,null,$inventario_id,'Ajuste inventario +']
          );
          db_query('UPDATE productos SET stock_actual = stock_actual + ? WHERE id=?', [$diff, $producto_id]);
          $ajustes[] = ['producto_id'=>$producto_id,'tipo'=>'entrada','cantidad'=>$diff];
        } else {
          $salida = abs($diff);
          db_query(
            'INSERT INTO movimientos_productos
             (producto_id,tipo,cantidad,ubic_origen_id,usuario_id,origen,ref_tipo,ref_id,notas)
             VALUES (?,?,?,?,NULL,"inventario","INV",?,?)',
            [$producto_id,'salida',$salida,null,$inventario_id,'Ajuste inventario -']
          );
          db_query('UPDATE productos SET stock_actual = stock_actual - ? WHERE id=?', [$salida, $producto_id]);
          $ajustes[] = ['producto_id'=>$producto_id,'tipo'=>'salida','cantidad'=>$salida];
        }
      }
    }

    db_query('UPDATE inventarios SET estado="cerrado", closed_at=NOW() WHERE id=?', [$inventario_id]);
  });

  echo json_encode(['status'=>'success','message'=>'Inventario cerrado.','ajustes'=>$ajustes]);
} catch (Throwable $e) {
  http_response_code(400);
  echo json_encode(['status'=>'error','message'=>$e->getMessage() ?: 'Error al cerrar inventario.']);
}
