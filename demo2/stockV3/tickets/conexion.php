<?php
$host = "localhost";
$usuario = "root";
$contrasena = "";
$base_datos = "sistema_tickets";

$conn = new mysqli($host, $usuario, $contrasena, $base_datos);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>