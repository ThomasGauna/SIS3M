<?php
$host = 'localhost:3308';
$db = 'trimod_bdd';
$user = 'root';
$pass = '';

header('Content-Type: application/json');

$conexion = new mysqli($host, $user, $pass, $db);
if ($conexion->connect_error) {
    echo json_encode(["status" => "error", "message" => "Error de conexión a la base de datos."]);
    exit();
}

$query = "
    SELECT u.id, u.nombre, u.apellido
    FROM usuarios u
    JOIN roles r ON u.rol_id = r.id
    WHERE r.nombre = 'tecnico'
";

$resultado = $conexion->query($query);

if ($resultado) {
    $tecnicos = [];

    while ($fila = $resultado->fetch_assoc()) {
        $tecnicos[] = $fila;
    }

    echo json_encode(["status" => "success", "tecnicos" => $tecnicos]);
} else {
    echo json_encode(["status" => "error", "message" => "No se pudo obtener los técnicos."]);
}

$conexion->close();
