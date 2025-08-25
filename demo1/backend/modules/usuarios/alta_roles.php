<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


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
    $descripcion = isset($_POST['descripcion']) ? $conexion->real_escape_string($_POST['descripcion']) : null;

    $verificar = $conexion->prepare("SELECT id FROM roles WHERE nombre = ?");
    $verificar->bind_param("s", $nombre);
    $verificar->execute();
    $resultado = $verificar->get_result();

    if ($resultado->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "El rol ya existe."]);
    } else {
        $insertar = $conexion->prepare("INSERT INTO roles (nombre, descripcion) VALUES (?, ?)");
        $insertar->bind_param("ss", $nombre, $descripcion);

        if ($insertar->execute()) {
            echo json_encode(["status" => "success", "message" => "Rol registrado con éxito."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al registrar: " . $conexion->error]);
        }

        $insertar->close();
    }

    $verificar->close();
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}

$conexion->close();
