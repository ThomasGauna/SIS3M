<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json');

try {
  $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
  if ($id<=0) { echo json_encode(['status'=>'error','message'=>'ID inválido.']); exit; }

  $row = db_query('SELECT * FROM ubicaciones WHERE id = ?', [$id])->fetch();
  if (!$row) { echo json_encode(['status'=>'error','message'=>'Ubicación no encontrada.']); exit; }

  echo json_encode(['status'=>'success','ubicacion'=>$row]);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['status'=>'error','message'=>'Error al obtener ubicación.']);
}
