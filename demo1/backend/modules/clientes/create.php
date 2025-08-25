<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json');

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
  echo json_encode(['status'=>'error','message'=>'Método no permitido.']); exit;
}

try {
  $tipo   = ($_POST['tipo'] ?? 'persona') === 'empresa' ? 'empresa' : 'persona';
  $nombre = trim($_POST['nombre'] ?? '');
  $apellido = trim($_POST['apellido'] ?? '');
  $documento_tipo = $_POST['documento_tipo'] ?? null;
  $documento_nro  = trim($_POST['documento_nro'] ?? '');
  $email   = trim($_POST['email'] ?? '');
  $telefono= trim($_POST['telefono'] ?? '');
  $estado  = in_array(($_POST['estado'] ?? 'activo'), ['activo','inactivo'], true) ? $_POST['estado'] : 'activo';
  $notas   = ($_POST['notas'] ?? null) ?: null;

  if ($nombre === '') { echo json_encode(['status'=>'error','message'=>'El nombre es obligatorio.']); exit; }

  if ($documento_tipo && $documento_nro !== '') {
    $dup = db_query('SELECT id FROM clientes WHERE documento_tipo = ? AND documento_nro = ? LIMIT 1',
      [$documento_tipo, $documento_nro])->fetch();
    if ($dup) { echo json_encode(['status'=>'error','message'=>'Documento ya registrado.']); exit; }
  }

  db_tx(function() use ($tipo,$nombre,$apellido,$documento_tipo,$documento_nro,$email,$telefono,$estado,$notas,&$newId) {
    db_query(
      'INSERT INTO clientes (tipo,nombre,apellido,documento_tipo,documento_nro,email,telefono,estado,notas)
       VALUES (?,?,?,?,?,?,?,?,?)',
      [$tipo,$nombre,$apellido,$documento_tipo,$documento_nro,$email,$telefono,$estado,$notas]
    );
    $newId = (int) db()->lastInsertId();

    $dir = [
      'etiqueta'  => trim($_POST['dir_etiqueta'] ?? ''),
      'direccion' => trim($_POST['dir_direccion'] ?? ''),
      'localidad' => trim($_POST['dir_localidad'] ?? ''),
      'provincia' => trim($_POST['dir_provincia'] ?? ''),
      'pais'      => trim($_POST['dir_pais'] ?? 'Argentina'),
      'cp'        => trim($_POST['dir_cp'] ?? '')
    ];
    if ($dir['direccion'] !== '') {
      db_query(
        'INSERT INTO cliente_direcciones (cliente_id,etiqueta,direccion,localidad,provincia,pais,cp,es_principal,estado)
         VALUES (?,?,?,?,?,?,?,1,"activo")',
        [$newId,$dir['etiqueta'],$dir['direccion'],$dir['localidad'],$dir['provincia'],$dir['pais'],$dir['cp']]
      );
    }

    $cont = [
      'nombre'  => trim($_POST['cont_nombre'] ?? ''),
      'cargo'   => trim($_POST['cont_cargo'] ?? ''),
      'email'   => trim($_POST['cont_email'] ?? ''),
      'telefono'=> trim($_POST['cont_telefono'] ?? '')
    ];
    if ($cont['nombre'] !== '' || $cont['email'] !== '' || $cont['telefono'] !== '') {
      db_query(
        'INSERT INTO cliente_contactos (cliente_id,nombre,cargo,email,telefono,es_principal,estado)
         VALUES (?,?,?,?,?,1,"activo")',
        [$newId,$cont['nombre'],$cont['cargo'],$cont['email'],$cont['telefono']]
      );
    }
  });

  echo json_encode(['status'=>'success','message'=>'Cliente creado.','id'=>$newId]);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['status'=>'error','message'=>'Error al crear cliente.']);
}
