<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json');

try {
  $q = trim($_GET['q'] ?? '');
  $sql = 'SELECT p.id, p.nombre, p.sku, p.costo_unitario, p.stock_actual, p.stock_minimo, p.estado,
                 c.nombre AS categoria, m.nombre AS marca, pr.nombre AS proveedor, u.abreviatura AS unidad
          FROM productos p
          LEFT JOIN categorias c ON c.id = p.categoria_id
          LEFT JOIN marcas m ON m.id = p.marca_id
          LEFT JOIN proveedores pr ON pr.id = p.proveedor_id
          LEFT JOIN unidades_medida u ON u.id = p.unidad_id';
  $rows = ($q !== '')
    ? db_query($sql.' WHERE p.nombre LIKE ? OR p.sku LIKE ? OR c.nombre LIKE ? ORDER BY p.nombre',
        ['%'.$q.'%','%'.$q.'%','%'.$q.'%'])->fetchAll()
    : db_query($sql.' ORDER BY p.nombre')->fetchAll();

  echo json_encode(['status'=>'success','productos'=>$rows]);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['status'=>'error','message'=>'Error al obtener productos.']);
}