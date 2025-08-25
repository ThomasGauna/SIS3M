<!DOCTYPE html>
<html>
<head>
    <title>Crear Ticket</title>
</head>
<body>
    <h2>Enviar un nuevo ticket de soporte</h2>
    <form action="guardar_ticket.php" method="POST">
        <label>Nombre:</label><br>
        <input type="text" name="nombre" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Mensaje:</label><br>
        <textarea name="mensaje" rows="5" required></textarea><br><br>

        <button type="submit">Enviar ticket</button>
    </form>
</body>
</html>