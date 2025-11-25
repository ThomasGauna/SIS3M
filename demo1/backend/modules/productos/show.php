<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json');

try {
  $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
  if ($id <= 0) { echo json_encode(['status'=>'error','message'=>'ID inválido.']); exit; }

  $row = db_query(
    'SELECT
        p.id, p.sku, p.nombre, p.descripcion,
        p.unidad_id, p.categoria_id, p.marca_id, p.proveedor_id,
        p.costo_unitario, p.stock_actual, p.stock_minimo, p.estado,
        p.ubicacion_id,
        DATE_FORMAT(p.fecha_alta, "%Y-%m-%d") AS fecha_alta,
        DATE_FORMAT(p.fecha_alta, "%Y-%m-%d %H:%i:%s") AS fecha_alta_full, 
        DATE_FORMAT(p.fecha_actualizacion, "%Y-%m-%d %H:%i:%s") AS fecha_actualizacion
     FROM productos p
     WHERE p.id = ?',
    [$id]
  )->fetch();

  if (!$row) { echo json_encode(['status'=>'error','message'=>'Producto no encontrado.']); exit; }

  echo json_encode(['status'=>'success','producto'=>$row]);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['status'=>'error','message'=>'Error al obtener producto.']);
}
