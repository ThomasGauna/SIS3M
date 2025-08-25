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

$query = "SELECT id, nombre FROM roles ORDER BY nombre ASC";
$resultado = $conexion->query($query);

if ($resultado) {
    $roles = [];
    while ($row = $resultado->fetch_assoc()) {
        $roles[] = $row;
    }
    echo json_encode(["status" => "success", "roles" => $roles]);
} else {
    echo json_encode(["status" => "error", "message" => "Error al obtener los roles."]);
}

$conexion->close();
