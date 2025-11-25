<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json');

try {
    $q = trim($_GET['q'] ?? '');
    if ($q !== '') {
        $marcas = db_query(
            'SELECT id, nombre FROM marcas WHERE nombre LIKE ? ORDER BY nombre ASC',
            ['%' . $q . '%']
        )->fetchAll();
    } else {
        $marcas = db_query(
            'SELECT id, nombre FROM marcas ORDER BY nombre ASC'
        )->fetchAll();
    }

    echo json_encode(['status' => 'success', 'marcas' => $marcas]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['status'=>'error','message'=>'Error al obtener las marcas.']);
}

