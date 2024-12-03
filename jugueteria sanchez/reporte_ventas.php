<?php 
session_start();

// Conexión a la base de datos
$host = "localhost";
$user = "root";
$password = "";
$database = "juguete";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si se enviaron las fechas para el rango del reporte
if (isset($_POST['mes_inicio']) && isset($_POST['mes_fin'])) {
    $mesInicio = $_POST['mes_inicio'] . "-01"; // Primer día del mes de inicio
    $mesFin = date("Y-m-t", strtotime($_POST['mes_fin'] . "-01")); // Último día del mes de fin

    // Consulta combinada para obtener el reporte de ventas y veces agregado al carrito dentro del rango de fechas seleccionado
    $sql = "SELECT 
                p.nombre AS producto, 
                SUM(c.cantidad) AS cantidad_vendida, 
                COUNT(c.id) AS veces_agregado,
                SUM(c.cantidad * p.precio) AS ingresos_totales,
                DATE(c.fecha) AS fecha
            FROM 
                compras c
            JOIN 
                productos p ON c.producto_id = p.id
            WHERE 
                c.fecha BETWEEN '$mesInicio' AND '$mesFin'
            GROUP BY 
                p.nombre, DATE(c.fecha)
            ORDER BY 
                fecha DESC, cantidad_vendida DESC";

    $result = $conn->query($sql);

    // Verificar si la consulta fue exitosa
    if (!$result) {
        echo "Error en la consulta: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ventas - MyStoreComicxManga</title>
    <link rel="stylesheet" href="./../assets/css/reporte_ventas_styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Reporte de Ventas</h1>
        </header>

        <!-- Formulario para seleccionar el rango de fechas -->
        <form method="post">
            <label for="mes_inicio">Mes de inicio (YYYY-MM):</label>
            <input type="month" name="mes_inicio" required>
            
            <label for="mes_fin">Mes de fin (YYYY-MM):</label>
            <input type="month" name="mes_fin" required>
            
            <input type="submit" value="Generar Reporte">
        </form>

        <!-- Mostrar el reporte solo si hay datos -->
        <?php if (isset($result) && $result->num_rows > 0): ?>
            <h2>Reporte de Ventas y Productos Agregados al Carrito</h2>
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad Vendida</th>
                        <th>Veces Agregado al Carrito</th>
                        <th>Ingresos Totales</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['producto']) ?></td>
                            <td><?= htmlspecialchars($row['cantidad_vendida']) ?></td>
                            <td><?= htmlspecialchars($row['veces_agregado']) ?></td>
                            <td>$<?= number_format($row['ingresos_totales'], 2) ?></td>
                            <td><?= htmlspecialchars($row['fecha']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php elseif (isset($result)): ?>
            <p>No hay datos disponibles para el período seleccionado.</p>
        <?php endif; ?>

        <button onclick="window.location.href='productos.php'">Regresar</button>
    </div>
</body>
</html>
