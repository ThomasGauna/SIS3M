<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json; charset=utf-8');

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'GET') {
  echo json_encode(['status'=>'error','message'=>'Método no permitido']); exit;
}

$q = trim($_GET['q'] ?? '');
$params = [];
$where = ' (estado IS NULL OR estado="activo") ';

if ($q !== '') {
  $where .= ' AND (nombre LIKE ? OR apellido LIKE ? OR CONCAT(nombre," ",apellido) LIKE ? OR documento_nro LIKE ?)';
  $like = "%$q%";
  $params = [$like, $like, $like, $like];
}

$sql = "SELECT id, tipo, nombre, apellido
        FROM clientes
        WHERE $where
        ORDER BY nombre, apellido
        LIMIT 500";

$rows = db_query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
$items = [];
foreach ($rows as $r) {
  $label = ($r['tipo'] === 'persona')
    ? trim(($r['nombre'] ?? '') . ' ' . ($r['apellido'] ?? ''))
    : ($r['nombre'] ?? '');
  if ($label === '') $label = 'Cliente #'.$r['id'];
  $items[] = ['id' => (int)$r['id'], 'nombre' => $label];
}

echo json_encode(['status'=>'success','items'=>$items], JSON_UNESCAPED_UNICODE);
