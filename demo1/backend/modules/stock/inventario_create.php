<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json');

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
  echo json_encode(['status'=>'error','message'=>'Método no permitido.']); exit;
}

try {
  $titulo       = trim($_POST['titulo'] ?? '');
  $ubicacion_id = isset($_POST['ubicacion_id']) && $_POST['ubicacion_id']!=='' ? (int)$_POST['ubicacion_id'] : null;
  $creado_por   = isset($_POST['creado_por']) && $_POST['creado_por']!=='' ? (int)$_POST['creado_por'] : null;

  if ($titulo==='') { echo json_encode(['status'=>'error','message'=>'Título obligatorio.']); exit; }

  db_query(
    'INSERT INTO inventarios (titulo,estado,ubicacion_id,creado_por) VALUES (?, "abierto", ?, ?)',
    [$titulo,$ubicacion_id,$creado_por]
  );
  $id = (int)db()->lastInsertId();

  echo json_encode(['status'=>'success','message'=>'Inventario abierto.','id'=>$id]);
} catch (Throwable $e) {
  http_response_code(400);
  echo json_encode(['status'=>'error','message'=>'Error al abrir inventario.']);
}
