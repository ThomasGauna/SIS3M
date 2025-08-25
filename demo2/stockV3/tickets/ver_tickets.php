<?php
require_once "conexion.php";

$resultado = $conn->query("SELECT * FROM tickets ORDER BY fecha DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tickets Recibidos</title>
</head>
<body>
    <h2>Listado de Tickets</h2>
    <table border="1" cellpadding="10">
        <tr>
            <th>Nombre</th>
            <th>Email</th>
            <th>Mensaje</th>
            <th>Fecha</th>
            <th>Estado</th>
        </tr>
        <?php while ($row = $resultado->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row["nombre"]) ?></td>
            <td><?= htmlspecialchars($row["email"]) ?></td>
            <td><?= nl2br(htmlspecialchars($row["mensaje"])) ?></td>
            <td><?= $row["fecha"] ?></td>
            <td><?= $row["estado"] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php
$conn->close();