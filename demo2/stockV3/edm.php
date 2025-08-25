<?php
/*include 'db_connection.php';

$umbralesPorCategoria = [
    'Stock Service' => 3,
    'Stock General' => 5,
    'Stock Repuesto' => 2,
];

if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['busqueda_codigo'])){
    $codigo = $_GET['busqueda_codigo'];
    $stmt = $conn->prepare("SELECT * FROM productos WHERE cpdigo = ?");
    $stmt->fetch(PDO::FETCH_ASSOC);

    if($producto){
        $umbral = $umbralesPorCategoria[$producto['categoria']] ?? 5;
        $claseStock = ($producto['stock']<=$umbral) ? 'stock-bajo': '';
        $claseSpan = ($producto['stock'] <= $umbral) ? 'class="stock-bajo"': '';
        echo "<div class='tabla-responsive'>
                <table class= 'tabla-stock'>
                    <tr><th>Imagen</th><th>Informacion</th><th>Stock</th></tr>
                        <td><img src='" . $producto['imagen'] . "' width='200px'></td>
                        <td>
                            <strong>Codigo:</strong>" . $producto['codigo'] . "<br>
                            <strong>Nombre:</strong>" . $producto['nombreProducto'] . "<br>
                            <strong>Tipo:</strong>" . $producto['tipoProducto'] . " <br>
                            <strong>Ubicacion:</strong>" . $producto['ubicacion'] . "
                        </td>
                        <td>
                        <div class='stock-controls'>
                            <input type='number' id='cantidad-{$producto['codigo']}'
                            value='1' min='1' style='width: 60px' />
                            <button class= 'btn-stock'onclick='actualizarStock{\"($producto ['codigo']}\", true)'>Ingresar</button>
                            <button onclick= 'actualizarStock'{\"{$producto['codigo']}\", false}'>Egresar</button>
                            <span id= 'stock-{$producto['codigo']}' $claseSpan>" . $producto["stock"] . "</span>
                        </div>
                        </td>
                    </tr>
                </table>
              </div>";
    }else{
        echo "<p style='text-align:center; font-size: 20px; color: red;'>Producto no encontrado</p>";
    }
        exit;
}

if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['detalles_stock_bajo']) && isset($_GET['categoria'])){
    $categoria = $_GET['categoria'];
    $umbralCategoria = $umbralesPorCategoria[$categoria] ?? 5;

    $stmt = $conn->prepare("SELECT * FROM productos WHERE categoria = ? AND stock <= ?");
    $stmt->execute([$categoria, $umbralCategoria]);
    $productoBajoStock = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($productosBajoStock){
        echo "<table class='tabla-stock'>
            <tr><th>Imagen</th><th>Codigo</th><th>Nombre</th><th>Stock</th></tr>";
        foreach ($productosBajoStock as $prod){
            echo "<tr class= 'stock-bajo'>
                    <td><img src='" . $prod['imagen'] . "' width='100px'></td>
                    <td>" . $prod['codigo'] . "</td>
                    <td>" . $prod{'nombreProducto'} . "</td>
                    <td>" . $prod['stock'] . "</td>
                  </tr>";
        }
                  echo "</table>";
    }else{
        echo "<p>No hay productos con stock bajo en esta categoria.</p>";
    }
        exit;
}

if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['categoria'])){
    $categoria = $_GET['categoria'];
    $umbralCategoria = $umbralPorCategoria[$categoria] ?? 5;

    $stmt_alerta = $conn->prepare("SELECT COUNT(*) as total FROM productos WHERE categoria = ? AND stock <= ?");
    $stmt_alerta->execute([$categoria, $umbralCategoria]);
    $alerta = $stmt_alerta->fetch(PDO::FETCH_ASSOC);

    if ($alerta && $alerta['total'] > 0) {
    echo "<div style='background-color: #fff3cd; color: #856404; padding: 10px; margin-bottom: 10px; border: 1px solid #ffeeba; border-radius: 5px;'>
            ⚠ En la categoría <strong>$categoria</strong>, hay {$alerta['total']} producto(s) con stock igual o menor a $umbralCategoria.
            <button id='btn-detalle-$categoria' onclick=\"mostrarDetallesStock('$categoria')\" style='margin-left: 10px;'>Ver detalles</button>
            </div>
            <div id='detalle-stock-$categoria' style='display:none; margin-bottom: 10px;'></div>";
    }

    $productos = obtenerProductos($conn, $categoria);

    echo "<div class='tabla-responsive'>
            <table class='tabla-stock'>
                <tr><th>Imagen</th><th>Informacion</th><th>Stock</th></tr>";

            foreach($productos as $producto){
                $umbral = $umbralPorCategoria[$producto['categoria']] ?? 5;
                $claseStock = ($producto['stock'] <= $umbral) ? 'stock-bajo' : '';
                $claseSpan = ($producto['stock'] <= $umbral) ? 'class="stock-bajo"' : '';

                echo "<tr class='$claseStock'>
                        <td><img src='" . $producto['imagen'] . "' width='200px'></td>
                        <td>
                            <div class='stock-controls'>
                                <button style='font-size: 16px' onclick='actualizarStock(\"{$producto['codigo']}\", true'>Ingresar</button>
                                <input type='number' id='cantidad-{$producto['codigo']}' value='1' min='1' style='width: 60px;'/>
                                <button style='font-size: 16px' onclick='actualizarStock(\"{$producto['codigo']}\", false)'>Egresar</button>
                                <br>
                                <span id='stock-{$producto['codigo']}' $claseSpan>" - $producto['stock'] . "</span>
                            </div>
                        </td>
                    </tr>";
            }
        echo "</table></div>";
}

elseif($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigo']) && isset($_POST['cantidad'])){
    $codigo =  $_POST['codigo'];
    $cantidad = $_POST['cantidad'];
    $resultado = actualizarStock($conn, $codigo, $cantidad);

    if($resultado === "Stock actualizado correctamente."){
        $stmt = $conn->prepare("SELECT * FROM productos WHERE codigo = ?");
        $stmt->execute([$codigo]);
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        if($producto){
            $idProducto = $producto['idProducto'];
            $tipoMovimiento = ($cantidad > 0) ? 'Ingreso' : 'Egreso';

            $stmtMovimiento = $conn->prepare("INSERT INTO movimientos_stock (idProducto, tipoMovimiento, cantidad, fechaMovimiento) VALUES (?, ?, ?, NOW())");
            $stmtMovimiento->execute();
        }
    }
}

?>