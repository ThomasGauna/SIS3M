<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'] ?? '';
if (!in_array($method, ['PUT','POST'], true)) {
  echo json_encode(['status'=>'error','message'=>'Método no permitido.']); exit;
}

try {
  $d = $method==='PUT' ? (json_decode(file_get_contents('php://input'), true) ?? []) : $_POST;

  $id = isset($d['id']) ? (int)$d['id'] : 0;
  $tipo   = ($d['tipo'] ?? 'persona') === 'empresa' ? 'empresa' : 'persona';
  $nombre = trim($d['nombre'] ?? '');
  $apellido = trim($d['apellido'] ?? '');
  $documento_tipo = $d['documento_tipo'] ?? null;
  $documento_nro  = trim($d['documento_nro'] ?? '');
  $email   = trim($d['email'] ?? '');
  $telefono= trim($d['telefono'] ?? '');
  $estado  = in_array(($d['estado'] ?? 'activo'), ['activo','inactivo'], true) ? $d['estado'] : 'activo';
  $notas   = ($d['notas'] ?? null) ?: null;

  if ($id<=0 || $nombre==='') { echo json_encode(['status'=>'error','message'=>'ID y nombre son obligatorios.']); exit; }

  if ($documento_tipo && $documento_nro !== '') {
    $dup = db_query('SELECT id FROM clientes WHERE documento_tipo=? AND documento_nro=? AND id<>? LIMIT 1',
      [$documento_tipo,$documento_nro,$id])->fetch();
    if ($dup) { echo json_encode(['status'=>'error','message'=>'Documento ya registrado en otro cliente.']); exit; }
  }

  db_tx(function() use ($id,$tipo,$nombre,$apellido,$documento_tipo,$documento_nro,$email,$telefono,$estado,$notas,$d,&$aff) {
    $aff = db_query(
      'UPDATE clientes SET tipo=?, nombre=?, apellido=?, documento_tipo=?, documento_nro=?, email=?, telefono=?, estado=?, notas=? WHERE id=?',
      [$tipo,$nombre,$apellido,$documento_tipo,$documento_nro,$email,$telefono,$estado,$notas,$id]
    )->rowCount();

    $dir_direccion = trim($d['dir_direccion'] ?? '');
    if ($dir_direccion !== '' || !empty($d['dir_etiqueta']) || !empty($d['dir_localidad']) || !empty($d['dir_provincia']) || !empty($d['dir_pais']) || !empty($d['dir_cp'])) {
      $exists = db_query('SELECT id FROM cliente_direcciones WHERE cliente_id=? AND es_principal=1 LIMIT 1', [$id])->fetch();
      if ($exists) {
        db_query(
          'UPDATE cliente_direcciones SET etiqueta=?, direccion=?, localidad=?, provincia=?, pais=?, cp=?, estado="activo" WHERE id=?',
          [
            trim($d['dir_etiqueta'] ?? ''), $dir_direccion, trim($d['dir_localidad'] ?? ''), trim($d['dir_provincia'] ?? ''),
            trim($d['dir_pais'] ?? 'Argentina'), trim($d['dir_cp'] ?? ''), $exists['id']
          ]
        );
      } else {
        db_query(
          'INSERT INTO cliente_direcciones (cliente_id,etiqueta,direccion,localidad,provincia,pais,cp,es_principal,estado)
           VALUES (?,?,?,?,?,?,?,1,"activo")',
          [$id, trim($d['dir_etiqueta'] ?? ''), $dir_direccion, trim($d['dir_localidad'] ?? ''), trim($d['dir_provincia'] ?? ''), trim($d['dir_pais'] ?? 'Argentina'), trim($d['dir_cp'] ?? '')]
        );
      }
    }

    $cont_nombre = trim($d['cont_nombre'] ?? '');
    $cont_email  = trim($d['cont_email'] ?? '');
    $cont_tel    = trim($d['cont_telefono'] ?? '');
    if ($cont_nombre !== '' || $cont_email !== '' || $cont_tel !== '') {
      $exists = db_query('SELECT id FROM cliente_contactos WHERE cliente_id=? AND es_principal=1 LIMIT 1', [$id])->fetch();
      if ($exists) {
        db_query(
          'UPDATE cliente_contactos SET nombre=?, cargo=?, email=?, telefono=?, estado="activo" WHERE id=?',
          [$cont_nombre, trim($d['cont_cargo'] ?? ''), $cont_email, $cont_tel, $exists['id']]
        );
      } else {
        db_query(
          'INSERT INTO cliente_contactos (cliente_id,nombre,cargo,email,telefono,es_principal,estado)
           VALUES (?,?,?,?,?,1,"activo")',
          [$id, $cont_nombre, trim($d['cont_cargo'] ?? ''), $cont_email, $cont_tel]
        );
      }
    }
  });

  echo json_encode(['status'=>'success','message'=>$aff>0?'Cliente actualizado.':'Sin cambios.','updated'=>$aff]);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['status'=>'error','message'=>'Error al actualizar cliente.']);
}
