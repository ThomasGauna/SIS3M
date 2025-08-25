<?php
declare(strict_types=1);
require_once __DIR__ . '/../../../config/db.php';
header('Content-Type: application/json');

$cliente_id = intval($_GET['cliente_id'] ?? 0);
if ($cliente_id <= 0) { echo json_encode(['status'=>'error','message'=>'ID inválido']); exit; }

$stmt = db()->prepare(
  'SELECT id, etiqueta, direccion, localidad, provincia, pais, cp, es_principal
     FROM cliente_direcciones
    WHERE cliente_id = ?
    ORDER BY es_principal DESC, id DESC'
);
$stmt->execute([$cliente_id]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['status'=>'success','direcciones'=>$rows], JSON_UNESCAPED_UNICODE);
