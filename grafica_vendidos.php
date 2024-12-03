<?php
// Conexión a la base de datos
include 'conex.php';

// Obtener datos de los productos
$sql = "SELECT nombre, cantidad, vendidos FROM productos";
$result = $conn->query($sql);

$productos = [];
$cantidades = [];
$vendidos = [];

while ($row = $result->fetch_assoc()) {
    $productos[] = $row['nombre'];
    $cantidades[] = $row['cantidad'];
    $vendidos[] = $row['vendidos'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gráfica de Productos Vendidos y Disponibles</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        h2 {
            color: #333;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .chart-container {
            width: 80%;
            max-width: 600px;
        }
    </style>
</head>
<body>
    <h2>Gráfica de Productos Vendidos y Disponibles</h2>
    <div class="chart-container">
        <canvas id="graficaVendidos"></canvas>
    </div>
    <a href="productos.php" class="btn">Volver a Productos</a>

    <script>
        const ctx = document.getElementById('graficaVendidos').getContext('2d');
        
        // Obtener los datos de PHP y asignarlos a JavaScript
        const productos = <?php echo json_encode($productos); ?>;
        const cantidades = <?php echo json_encode($cantidades); ?>;
        const vendidos = <?php echo json_encode($vendidos); ?>;

        // Crear la gráfica
        new Chart(ctx, {
            type: 'bar', // Tipo de gráfico
            data: {
                labels: productos, // Nombres de productos
                datasets: [
                    {
                        label: 'Cantidad Disponible',
                        data: cantidades, // Cantidades disponibles
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Cantidad Vendida',
                        data: vendidos, // Cantidades vendidas
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Cantidad'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Productos'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        enabled: true
                    }
                }
            }
        });
    </script>
</body>
</html>
