<?php
session_start();
require 'db_connection.php';

$username = $_POST['username'];
$password = $_POST['password'];

if (verificarUsuario($conn, $username, $password)) {
    $_SESSION['usuario'] = $username;
    header("Location: stock.php");
    exit;
} else {
    $_SESSION['error'] = "Usuario o contraseña incorrectos.";
    header("Location: index.php");
    exit;
}
