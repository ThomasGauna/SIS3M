<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';
ini_set('display_errors','0'); ini_set('html_errors','0');
header('Content-Type: application/json; charset=utf-8');

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'GET') {
  echo json_encode(['status'=>'error','message'=>'Método no permitido']); exit;
}

try {
  $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
  if ($id <= 0) { echo json_encode(['status'=>'error','message'=>'ID inválido']); exit; }

  $pdo = db();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // --- CLIENTE ---
  // Si en tu tabla CLIENTES dejaste 'estado', mantené esta línea.
  // Si lo eliminaste también en clientes, QUITA 'estado' del SELECT de abajo.
  $cli = db_query('
    SELECT id, tipo, nombre, apellido, documento_tipo, documento_nro,
           email, telefono, estado, notas,
           DATE_FORMAT(created_at, "%Y-%m-%d") AS fecha_alta,
           DATE_FORMAT(updated_at, "%Y-%m-%d") AS updated_at
    FROM clientes
    WHERE id = ?
    LIMIT 1
  ', [$id])->fetch(PDO::FETCH_ASSOC);

  if (!$cli) {
    echo json_encode(['status'=>'error','message'=>'Cliente no encontrado']); exit;
  }

  // --- DIRECCIÓN PRINCIPAL ---
  // REMOVIDO 'estado' (la columna ya no existe)
  $dir = db_query('
    SELECT id, etiqueta, direccion, localidad, provincia, pais, cp, es_principal
    FROM cliente_direcciones
    WHERE cliente_id = ? AND es_principal = 1
    ORDER BY id DESC
    LIMIT 1
  ', [$id])->fetch(PDO::FETCH_ASSOC) ?: null;

  // --- CONTACTO PRINCIPAL ---
  // REMOVIDO 'estado' (la columna ya no existe)
  $con = db_query('
    SELECT id, nombre, cargo, email, telefono, es_principal
    FROM cliente_contactos
    WHERE cliente_id = ? AND es_principal = 1
    ORDER BY id DESC
    LIMIT 1
  ', [$id])->fetch(PDO::FETCH_ASSOC) ?: null;

  echo json_encode([
    'status' => 'success',
    'message'=> 'OK',
    'cliente' => $cli,
    'direccion_principal' => $dir,
    'contacto_principal'  => $con
  ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode([
    'status'=>'error',
    'message'=>'Error interno en show.php',
    'error'=>$e->getMessage()
  ]);
}
