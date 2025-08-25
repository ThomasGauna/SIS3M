<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json');

try {
    $q = trim($_GET['q'] ?? '');
    if ($q !== '') {
        $rows = db_query(
            'SELECT id, nombre, descripcion FROM categorias WHERE nombre LIKE ? OR descripcion LIKE ? ORDER BY nombre',
            ['%'.$q.'%', '%'.$q.'%']
        )->fetchAll();
    } else {
        $rows = db_query('SELECT id, nombre, descripcion FROM categorias ORDER BY nombre')->fetchAll();
    }

    echo json_encode(['status' => 'success', 'categorias' => $rows]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['status'=>'error','message'=>'Error al obtener categorías.']);
}
