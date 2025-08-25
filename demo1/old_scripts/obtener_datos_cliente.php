<?php
// Configuración de la base de datos
$host = 'localhost'; 
$usuario = 'mungenet_cliente'; 
$contraseña = 'kkh2*Jl0wD'; 
$base_de_datos = 'mungenet_cliente'; 

// Conexión a la base de datos
$conexion = new mysqli($host, $usuario, $contraseña, $base_de_datos);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

if (isset($_GET['numero_serie'])) {
    $numeroSerie = $conexion->real_escape_string($_GET['numero_serie']);

    // Consultar los datos del cliente usando el número de serie
    $consultaCliente = "SELECT nombre, numero_cliente, numero_serie, fecha_instalacion, fecha_vencimiento, estado_garantia, horas_uso, fecha_proximo_service 
                        FROM clientes 
                        WHERE numero_serie = '$numeroSerie'";
    $resultadoCliente = $conexion->query($consultaCliente);

    if ($resultadoCliente->num_rows > 0) {
        $cliente = $resultadoCliente->fetch_assoc();

        // Mostrar los datos del cliente en HTML
        echo "<p><strong>Nombre:</strong> {$cliente['nombre']}</p>";
        echo "<p><strong>Número de Cliente:</strong> {$cliente['numero_cliente']}</p>";
        echo "<p><strong>Número de Serie:</strong> {$cliente['numero_serie']}</p>";
        echo "<p><strong>Fecha de Instalación:</strong> {$cliente['fecha_instalacion']}</p>";
        echo "<p><strong>Fecha de Vencimiento:</strong> {$cliente['fecha_vencimiento']}</p>";
        echo "<p><strong>Estado de Garantía:</strong> {$cliente['estado_garantia']}</p>";
        echo "<p><strong>Horas de Uso:</strong> {$cliente['horas_uso']}</p>";
        echo "<p><strong>Fecha del Próximo Servicio:</strong> {$cliente['fecha_proximo_service']}</p>";
    } else {
        echo "<p>No se encontraron datos para el número de serie proporcionado.</p>";
    }
}

// Cerrar la conexión
$conexion->close();
?>
