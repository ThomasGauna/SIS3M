<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'] ?? '';
if (!in_array($method, ['DELETE','POST'], true)) {
  echo json_encode(['status'=>'error','message'=>'Método no permitido.']); exit;
}

try {
  $d = $method==='DELETE' ? (json_decode(file_get_contents('php://input'), true) ?? []) : $_POST;
  $id = isset($d['id']) ? (int)$d['id'] : 0;
  if ($id<=0) { echo json_encode(['status'=>'error','message'=>'ID inválido.']); exit; }

  $del = db_query('DELETE FROM clientes WHERE id = ?', [$id])->rowCount();
  echo json_encode(['status'=>'success','message'=>$del>0?'Cliente eliminado.':'Cliente no encontrado.','deleted'=>$del]);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['status'=>'error','message'=>'Error al eliminar cliente.']);
}
