<?php
session_start();
require 'db.php';

echo '<style>
    .reparacion-realizada {
        background-color: #A00909; 
        color: white;
        padding: 10px; 
        margin: 10px auto; 
        width: 30%; 
        border-radius: 8px; 
        text-align: left;
    }

    .service-realizado {
        background-color: #008000; 
        color: white;
        padding: 10px; 
        margin: 10px auto; 
        width: 30%; 
        border-radius: 8px;
        text-align: left;
    }

    @media (max-width: 768px) {
        .reparacion-realizada, .service-realizado {
            width: 100%;
            font-size: 14px;
            padding: 8px;
        }
    }
</style>';

if (!isset($_GET['numero_serie']) || empty($_GET['numero_serie'])) {
    echo "<p style='color: red;'>Debe ingresar un número de serie.</p>";
    exit;
}

$search_value = $_GET['numero_serie'];
$consulta_clientes = 'SELECT * FROM clientes WHERE numero_serie = ?';
$consulta_cliente_ice = 'SELECT * FROM clientes_ice WHERE numero_serie = ?';
$consulta_clientes_s = 'SELECT * FROM clientes_s WHERE numero_serie = ?';

$stmt_clientes = $pdo->prepare($consulta_clientes);
$stmt_clientes->execute([$search_value]);
$cliente = $stmt_clientes->fetch();

if (!$cliente) {
    $stmt_cliente_ice = $pdo->prepare($consulta_cliente_ice);
    $stmt_cliente_ice->execute([$search_value]);
    $cliente = $stmt_cliente_ice->fetch();

    if (!$cliente) {
        $stmt_clientes_s = $pdo->prepare($consulta_clientes_s);
        $stmt_clientes_s->execute([$search_value]);
        $cliente = $stmt_clientes_s->fetch();
    }
}

if ($cliente) {
    function formatDate($date) {
        return isset($date) ? DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y') : "No disponible";
    }

    $fechaInstalacion = formatDate($cliente['fecha_instalacion']);
    $fechaVencimientoGarantia = formatDate($cliente['fecha_vencimiento_garantia']);
    
    $estadoGarantia = "NO DISPONIBLE";
    $styleEstado = 'color: black;';
    
    if (isset($cliente['fecha_vencimiento_garantia'])) {
        $fechaVencimiento = DateTime::createFromFormat('Y-m-d', $cliente['fecha_vencimiento_garantia']);
        $hoy = new DateTime();

        if ($fechaVencimiento >= $hoy) {
            $styleEstado = 'color: green;';
            $estadoGarantia = "VIGENTE";
        } else {
            $styleEstado = 'color: red;';
            $estadoGarantia = "EXPIRADA";
        }
    }

    echo "<p><strong>Nombre:</strong> " . htmlspecialchars($cliente['nombre']) . "</p>";
    echo "<p><strong>Número de Serie:</strong> " . htmlspecialchars($cliente['numero_serie']) . "</p>";
    echo "<p><strong>Fecha de Instalación:</strong> " . htmlspecialchars($fechaInstalacion) . "</p>";
    echo "<p><strong>Fecha de Vencimiento de Garantía:</strong> " . htmlspecialchars($fechaVencimientoGarantia) . "</p>";
    echo "<p><strong>Estado de la Garantía:</strong> <span style='$styleEstado'><strong>" . htmlspecialchars($estadoGarantia) . "</strong></span></p>";

    if (isset($_SESSION['user_id'])) {
        $stmt_horas_uso = $pdo->prepare('SELECT horas_uso FROM servicios_reparaciones WHERE numero_serie = ? AND horas_uso >= 0 ORDER BY fecha_servicio DESC LIMIT 1');
        $stmt_horas_uso->execute([$search_value]);
        $ultima_reparacion = $stmt_horas_uso->fetch();
        
        $horasUso = $ultima_reparacion ? $ultima_reparacion['horas_uso'] : "No hay horas registradas";
        echo "<p><strong>Horas de Uso del Motor:</strong> " . htmlspecialchars($horasUso) . "</p>";

        $stmt_fecha_proximo_service = $pdo->prepare('SELECT fecha_servicio FROM servicios_reparaciones WHERE numero_serie = ? AND tipo_servicio = "Service anual" ORDER BY fecha_servicio DESC LIMIT 1');
        $stmt_fecha_proximo_service->execute([$search_value]);
        $ultimo_service_anual = $stmt_fecha_proximo_service->fetch();
    
        if ($ultimo_service_anual) {
            $ultimo_service_date = new DateTime($ultimo_service_anual['fecha_servicio']);
            $proximo_service_date = $ultimo_service_date->add(new DateInterval('P1Y'));
            $proximo_service_formateada = $proximo_service_date->format('d-m-Y');
            echo "<p><strong>Próximo Servicio:</strong> " . htmlspecialchars($proximo_service_formateada) . "</p>";
        } else {
            echo "<p><strong>Próximo Servicio:</strong> No disponible</p>";
        }

        $stmt_servicios = $pdo->prepare('SELECT * FROM servicios_reparaciones WHERE numero_serie = ? ORDER BY fecha_servicio DESC');
        $stmt_servicios->execute([$cliente['numero_serie']]);
        $servicios = $stmt_servicios->fetchAll();

        if ($servicios) {
            echo "<hr><p><strong>TRABAJOS REALIZADOS:</strong></p>";
            foreach ($servicios as $servicio) {
                $fondoColor = ($servicio['tipo_servicio'] === 'Reparación') ? 'reparacion-realizada' : 'service-realizado';
                $fechaServicio = formatDate($servicio['fecha_servicio']);

                echo "<div class='$fondoColor'>";
                echo "<p><strong>Tipo de Servicio:</strong> " . htmlspecialchars($servicio['tipo_servicio']) . "</p>";
                echo "<p><strong>Fecha:</strong> " . htmlspecialchars($fechaServicio) . "</p>";
                echo "<p><strong>Descripción:</strong> " . htmlspecialchars($servicio['descripcion']) . "</p>";
                echo "<p><strong>Costo:</strong> $" . htmlspecialchars($servicio['costo']) . "</p>";
                echo "<p><strong>Técnico:</strong> " . htmlspecialchars($servicio['tecnico']) . "</p>";
                echo "<p><strong>Horas de Uso:</strong> " . htmlspecialchars($servicio['horas_uso']) . ".hs</p>";
                echo "<hr></div>";
            }
        } else {
            echo "<p>No se encontraron servicios asociados.</p>";
        }
    }
} else {
    echo "<p style='color: red;'>Número de serie no encontrado.</p>";
}
?>