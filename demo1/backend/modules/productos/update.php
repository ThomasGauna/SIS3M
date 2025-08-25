<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'] ?? '';
if (!in_array($method, ['PUT','POST'], true)) {
  echo json_encode(['status'=>'error','message'=>'Método no permitido.']); exit;
}

try {
  $d = $method==='PUT' ? (json_decode(file_get_contents('php://input'), true) ?? []) : $_POST;

  $id             = isset($d['id']) ? (int)$d['id'] : 0;
  $nombre         = trim($d['nombre'] ?? '');
  $descripcion    = ($d['descripcion'] ?? null) ?: null;
  $categoria_id   = ($d['categoria_id']   ?? '') !== '' ? (int)$d['categoria_id']   : null;
  $marca_id       = ($d['marca_id']       ?? '') !== '' ? (int)$d['marca_id']       : null;
  $proveedor_id   = ($d['proveedor_id']   ?? '') !== '' ? (int)$d['proveedor_id']   : null;
  $costo_unitario = ($d['costo_unitario'] ?? '') !== '' ? (float)$d['costo_unitario'] : null;
  $stock_actual   = ($d['stock_actual']   ?? '') !== '' ? (int)$d['stock_actual']   : 0;
  $ubicacion_id   = ($d['ubicacion_id']   ?? '') !== '' ? (int)$d['ubicacion_id']   : null;
  $unidad_id      = ($d['unidad_id']      ?? '') !== '' ? (int)$d['unidad_id']      : null;
  $sku            = ($d['sku']            ?? null) ?: null;
  $stock_minimo   = ($d['stock_minimo']   ?? '') !== '' ? (int)$d['stock_minimo']   : 0;
  $estado         = $d['estado'] ?? 'activo';

  if ($id<=0 || $nombre==='' || $categoria_id===null || $unidad_id===null) {
    echo json_encode(['status'=>'error','message'=>'ID, Nombre, Categoría y Unidad son obligatorios.']); exit;
  }
  if (!in_array($estado, ['activo','inactivo'], true)) $estado = 'activo';

  $dup = db_query(
    'SELECT id FROM productos WHERE id <> ? AND (
        (sku IS NOT NULL AND sku <> "" AND sku = ?) OR
        (nombre = ? AND IFNULL(categoria_id,0)=IFNULL(?,0) AND IFNULL(marca_id,0)=IFNULL(?,0))
     ) LIMIT 1',
    [$id, $sku, $nombre, $categoria_id, $marca_id]
  )->fetch();
  if ($dup) { echo json_encode(['status'=>'error','message'=>'Conflicto de duplicado (SKU o nombre/categoría/marca).']); exit; }

  $aff = db_query(
    'UPDATE productos SET
      nombre=?, descripcion=?, categoria_id=?, marca_id=?, proveedor_id=?, costo_unitario=?,
      stock_actual=?, ubicacion_id=?, unidad_id=?, sku=?, stock_minimo=?, estado=?
     WHERE id=?',
    [$nombre,$descripcion,$categoria_id,$marca_id,$proveedor_id,$costo_unitario,
     $stock_actual,$ubicacion_id,$unidad_id,$sku,$stock_minimo,$estado,$id]
  )->rowCount();

  echo json_encode(['status'=>'success','message'=>$aff>0?'Producto actualizado.':'Sin cambios.','updated'=>$aff]);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['status'=>'error','message'=>'Error al actualizar producto.']);
}
