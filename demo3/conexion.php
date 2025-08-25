<?php
date_default_timezone_set('America/Argentina/Buenos_Aires');
$host = "localhost:3308";
$user = "root";
$pass = "";
$db = "sistema_multas";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>