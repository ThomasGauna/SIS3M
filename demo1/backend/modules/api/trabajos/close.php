<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../../config/db.php';

try {
  $id = (int)($_POST['id'] ?? 0);
  if ($id <= 0) throw new RuntimeException('ID inválido.');

  $sql = "UPDATE trabajos
          SET estado = 'finalizado',
              fecha_cierre = CURRENT_TIMESTAMP
          WHERE id = :id";
  $st = db()->prepare($sql);
  $st->execute([':id' => $id]);

  echo json_encode(['ok'=>true, 'data'=>['id'=>$id], 'message'=>'Trabajo finalizado']);
} catch (Throwable $e) {
  http_response_code(400);
  echo json_encode(['ok'=>false, 'error'=>$e->getMessage()]);
}
