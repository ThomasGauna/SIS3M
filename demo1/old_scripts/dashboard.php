<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_value'])) {
    $search_value = $_POST['search_value'];
    
    $stmt = $pdo->prepare('SELECT * FROM clientes WHERE numero_serie = ? OR id = ?');
    $stmt->execute([$search_value, $search_value]);
    $cliente = $stmt->fetch();

    if ($cliente) {
        $cliente_info = "
            <h3>Información del Cliente</h3>
            <p>Nombre: {$cliente['nombre']}</p>
            <p>Número de Serie: {$cliente['numero_serie']}</p>
            <p>Fecha de Instalación: {$cliente['fecha_instalacion']}</p>
            <p>Fecha de Vencimiento de Garantía: {$cliente['fecha_vencimiento_garantia']}</p>
            <p>Estado de la Garantía: {$cliente['estado_garantia']}</p>
            <p>Horas de Uso del Motor: {$cliente['horas_uso_motor']}</p>
            <p>Fecha del Próximo Service: (calcular aquí)</p>
        ";
    } else {
        $cliente_info = "<p>Cliente no encontrado.</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h1>Dashboard</h1>
    
    <!-- Formulario de Búsqueda -->
    <form method="POST" action="dashboard.php">
        <input type="text" name="search_value" placeholder="Número de Serie o Cliente" required>
        <button type="submit">Buscar</button>
    </form>

    <!-- Mostrar Resultados de Búsqueda -->
    <?php
    if (isset($cliente_info)) {
        echo $cliente_info;
    }
    ?>

    <!-- Información según el rol del usuario -->
    <?php if ($role === 'Cliente'): ?>
        <?php
        $stmt = $pdo->prepare('SELECT * FROM clientes WHERE id = (SELECT cliente_id FROM usuarios WHERE id = ?)');
        $stmt->execute([$user_id]);
        $cliente = $stmt->fetch();
        ?>
        <h3>Información del Cliente</h3>
        <p>Nombre: <?= $cliente['nombre'] ?></p>
        <p>Número de Serie: <?= $cliente['numero_serie'] ?></p>
        <p>Fecha de Instalación: <?= $cliente['fecha_instalacion'] ?></p>
        <p>Fecha de Vencimiento de Garantía: <?= $cliente['fecha_vencimiento_garantia'] ?></p>
        <p>Estado de la Garantía: <?= $cliente['estado_garantia'] ?></p>
        <p>Horas de Uso del Motor: <?= $cliente['horas_uso_motor'] ?></p>
        <p>Fecha del Próximo Service: (calcular aquí)</p>
    <?php elseif ($role === 'Tecnico'): ?>
        <h3>Clientes</h3>
        <?php
        $stmt = $pdo->query('SELECT * FROM clientes');
        while ($cliente = $stmt->fetch()): ?>
            <p>Nombre: <?= $cliente['nombre'] ?></p>
            <p>Número de Serie: <?= $cliente['numero_serie'] ?></p>
            <p>Fecha de Instalación: <?= $cliente['fecha_instalacion'] ?></p>
            <p>Fecha de Vencimiento de Garantía: <?= $cliente['fecha_vencimiento_garantia'] ?></p>
            <p>Estado de la Garantía: <?= $cliente['estado_garantia'] ?></p>
            <p>Horas de Uso del Motor: <?= $cliente['horas_uso_motor'] ?></p>
            <p>Fecha del Próximo Service: (calcular aquí)</p>
            <hr>
        <?php endwhile; ?>
    <?php endif; ?>
</body>
</html>
