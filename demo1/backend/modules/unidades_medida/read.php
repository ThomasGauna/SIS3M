<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json');

try {
    $q = trim($_GET['q'] ?? '');
    if ($q !== '') {
        $rows = db_query(
            'SELECT id, nombre, abreviatura FROM unidades_medida
             WHERE nombre LIKE ? OR abreviatura LIKE ?
             ORDER BY nombre',
            ['%'.$q.'%', '%'.$q.'%']
        )->fetchAll();
    } else {
        $rows = db_query(
            'SELECT id, nombre, abreviatura FROM unidades_medida ORDER BY nombre'
        )->fetchAll();
    }

    header('Cache-Control: public, max-age=600');
    echo json_encode(['status'=>'success','unidades'=>$rows]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['status'=>'error','message'=>'Error al obtener unidades de medida.']);
}
