<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json');

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
  echo json_encode(['status'=>'error','message'=>'Método no permitido.']); exit;
}

try {
  $nombre         = trim($_POST['nombre'] ?? '');
  $descripcion    = ($_POST['descripcion'] ?? null) ?: null;
  $categoria_id   = ($_POST['categoria_id']   ?? '') !== '' ? (int)$_POST['categoria_id']   : null;
  $marca_id       = ($_POST['marca_id']       ?? '') !== '' ? (int)$_POST['marca_id']       : null;
  $proveedor_id   = ($_POST['proveedor_id']   ?? '') !== '' ? (int)$_POST['proveedor_id']   : null;
  $costo_unitario = ($_POST['costo_unitario'] ?? '') !== '' ? (float)$_POST['costo_unitario'] : null;
  $stock_actual   = ($_POST['stock_actual']   ?? '') !== '' ? (int)$_POST['stock_actual']   : 0;
  $ubicacion_id   = ($_POST['ubicacion_id']   ?? '') !== '' ? (int)$_POST['ubicacion_id']   : null;
  $unidad_id      = ($_POST['unidad_id']      ?? '') !== '' ? (int)$_POST['unidad_id']      : null;
  $sku            = ($_POST['sku']            ?? null) ?: null;
  $stock_minimo   = ($_POST['stock_minimo']   ?? '') !== '' ? (int)$_POST['stock_minimo']   : 0;
  $estado         = $_POST['estado'] ?? 'activo';
  $fecha_alta     = trim($_POST['fecha_alta'] ?? '') ?: date('Y-m-d');

  if (!in_array($estado, ['activo','inactivo'], true)) $estado = 'activo';
  if ($nombre === '' || $categoria_id === null || $unidad_id === null) {
    echo json_encode(['status'=>'error','message'=>'Nombre, Categoría y Unidad son obligatorios.']); exit;
  }

  $dup = db_query(
    'SELECT id FROM productos WHERE (sku IS NOT NULL AND sku <> "" AND sku = ?) OR (nombre = ? AND IFNULL(categoria_id,0)=IFNULL(?,0) AND IFNULL(marca_id,0)=IFNULL(?,0)) LIMIT 1',
    [$sku, $nombre, $categoria_id, $marca_id]
  )->fetch();
  if ($dup) { echo json_encode(['status'=>'error','message'=>'Ya existe un producto con ese SKU o mismo nombre/categoría/marca.']); exit; }

  db_query(
    'INSERT INTO productos (nombre, descripcion, categoria_id, marca_id, proveedor_id, costo_unitario, stock_actual, ubicacion_id, unidad_id, sku, stock_minimo, estado, fecha_alta)
     VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)',
    [$nombre,$descripcion,$categoria_id,$marca_id,$proveedor_id,$costo_unitario,$stock_actual,$ubicacion_id,$unidad_id,$sku,$stock_minimo,$estado,$fecha_alta]
  );
  $id = (int)db()->lastInsertId();

  echo json_encode(['status'=>'success','message'=>'Producto creado.','id'=>$id]);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['status'=>'error','message'=>'Error al crear producto.']);
}
