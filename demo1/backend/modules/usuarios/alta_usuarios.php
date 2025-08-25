<?php
$host = 'localhost:3308';
$db = 'trimod_bdd';
$user = 'root';
$pass = '';

header('Content-Type: application/json');

$conexion = new mysqli($host, $user, $pass, $db);
if ($conexion->connect_error) {
    echo json_encode(["status" => "error", "message" => "Error de conexión."]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $apellido = $conexion->real_escape_string($_POST['apellido']);
    $dni = $conexion->real_escape_string($_POST['dni']);
    $telefono = isset($_POST['telefono']) ? $conexion->real_escape_string($_POST['telefono']) : null;
    $email = isset($_POST['email']) ? $conexion->real_escape_string($_POST['email']) : null;
    $fechaIngreso = $conexion->real_escape_string($_POST['fecha_ingreso']);
    $observaciones = isset($_POST['observaciones']) ? $conexion->real_escape_string($_POST['observaciones']) : null;
    $rolId = $conexion->real_escape_string($_POST['rol_id']);

    $insertar = $conexion->prepare("INSERT INTO usuarios (nombre, apellido, dni, telefono, email, fecha_ingreso, observaciones, rol_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $insertar->bind_param("sssssssi", $nombre, $apellido, $dni, $telefono, $email, $fechaIngreso, $observaciones, $rolId);


    if ($insertar->execute()) {
        echo json_encode(["status" => "success", "message" => "Usuario registrado con éxito."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error al registrar: " . $conexion->error]);
    }

    $insertar->close();
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}

$conexion->close();

