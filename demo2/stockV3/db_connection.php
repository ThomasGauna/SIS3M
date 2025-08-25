<?php
$servername = "localhost:3308";
$username = "root";
$password = "";
$dbname = "todgninf_product";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

function obtenerProductos($conn, $categoria) {
    $stmt = $conn->prepare("SELECT * FROM productos WHERE categoria = :categoria");
    $stmt->bindParam(':categoria', $categoria);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function actualizarStock($conn, $codigo, $cantidad) {
    $stmt = $conn->prepare("UPDATE productos SET stock = stock + :cantidad WHERE codigo = :codigo");
    $stmt->bindParam(':codigo', $codigo);
    $stmt->bindParam(':cantidad', $cantidad);
    $stmt->execute();
    return "Stock actualizado correctamente.";
}

function verificarUsuario($conn, $username, $password) {
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $usuarioEncontrado = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuarioEncontrado) {
        echo "<pre>";
        print_r($usuarioEncontrado);
        echo "</pre>";

        echo "Contraseña ingresada: $password<br>";
        echo "Hash en BD: " . $usuarioEncontrado['password'] . "<br>";

        if (password_verify($password, $usuarioEncontrado['password'])) {
            session_start();
            $_SESSION['usuario'] = $usuarioEncontrado['username'];
            header("Location: stock.php");
            exit;
        } else {
            echo "Contraseña incorrecta (falló password_verify)";
            return false;
        }
    } else {
        echo "Usuario no encontrado en la base de datos";
        return false;
    }
}

?>