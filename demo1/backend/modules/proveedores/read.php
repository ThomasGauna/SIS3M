<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json');

try {
    $q = trim($_GET['q'] ?? '');
    if ($q !== '') {
        $rows = db_query(
            'SELECT id, nombre, cuit, telefono, email, estado
             FROM proveedores
             WHERE nombre LIKE ? OR cuit LIKE ? OR email LIKE ?
             ORDER BY nombre',
            ['%'.$q.'%', '%'.$q.'%', '%'.$q.'%']
        )->fetchAll();
    } else {
        $rows = db_query(
            'SELECT id, nombre, cuit, telefono, email, estado
             FROM proveedores
             ORDER BY nombre'
        )->fetchAll();
    }

    echo json_encode(['status'=>'success','proveedores'=>$rows]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['status'=>'error','message'=>'Error al obtener proveedores.']);
}
