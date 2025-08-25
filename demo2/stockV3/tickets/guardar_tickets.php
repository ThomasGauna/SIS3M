<?php
require_once "conexion.php";

$nombre = $_POST["nombre"];
$email = $_POST["email"];
$mensaje = $_POST["mensaje"];

$stmt = $conn->prepare("INSERT INTO tickets (nombre, email, mensaje) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $nombre, $email, $mensaje);
$stmt->execute();

$stmt->close();
$conn->close();

header("Location: ver_tickets.php");
exit;