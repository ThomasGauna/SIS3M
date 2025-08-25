<?php
session_start();
require 'db.php';

$error = ''; // Variable para el mensaje de error

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['nombre_usuario'];
    $contraseña = $_POST['contraseña'];
    $hashedPassword = md5($contraseña);

    $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE username = ? AND password = ?');
    $stmt->execute([$username, $hashedPassword]);
    $usuario = $stmt->fetch();

    if ($usuario) {
        // Inicio de sesión exitoso
        $_SESSION['user_id'] = $usuario['id'];
        $_SESSION['role'] = $usuario['role'];
        header('Location: index.php');
        exit;
    } else {
        // Datos incorrectos
        $error = 'Datos incorrectos. Por favor, inténtalo de nuevo.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Iniciar sesión</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.css">
    <style>
        .login-form {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 50px;
        }

        .login-form input[type="text"],
        .login-form input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0 20px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .login-form button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            width: 100%;
            border-radius: 4px;
        }

        .login-form button:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            font-size: 14px;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="login-form">
        <h2>Iniciar sesión</h2>
        <form method="post">
            <input type="text" name="nombre_usuario" placeholder="Nombre de usuario" required>
            <input type="password" name="contraseña" placeholder="Contraseña" required>
            <button type="submit">Iniciar sesión</button>
            <?php if ($error): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>

</html>
