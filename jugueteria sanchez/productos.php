<?php
// Conexión a la base de datos
include 'conex.php';

$sql = "SELECT id, nombre, precio, cantidad, imagen, vendidos FROM productos";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .container {
            padding: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-bottom: 10px;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .form-actualizar {
            display: inline;
        }
        .alert {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            margin-bottom: 15px;
        }
        .alert.error {
            background-color: #f44336;
        }
        img {
            max-width: 100px;
            height: auto;
        }
        .header-buttons {
            margin-top: 20px;
        }
    </style>
    ";






   
    echo "<h2>JUGUETRIA SANCHEZ</h2>";

    echo "<table>
            <tr>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Existencias</th>
                <th>Vendidos</th>
                <th>Imagen</th>
                <th>Actualizar</th>
                <th>Eliminar</th>
            </tr>";
    
    while ($fila = $result->fetch_assoc()) {
        $imagenBase64 = !empty($fila['imagen']) ? base64_encode($fila['imagen']) : '';
        $srcImagen = $imagenBase64 ? "data:image/jpeg;base64," . $imagenBase64 : "ruta/a/imagen/default.jpg";

        echo "<tr>
        <td>{$fila['nombre']}</td>
        <td>\${$fila['precio']}</td>
        <td>{$fila['cantidad']}</td>
        <td>
            <form action='actualizar_vendidos.php' method='POST' class='form-actualizar'>
                <input type='hidden' name='id' value='{$fila['id']}'>
                <input type='number' name='vendidos' value='{$fila['vendidos']}' min='0' style='width: 50px;'>
                <input type='submit' value='Actualizar' class='btn'>
            </form>
        </td>
        <td><img src='$srcImagen' alt='Imagen del producto'></td>
        <td><a href='actualizar_producto.php?id={$fila['id']}' class='btn'>Actualizar</a></td>
        <td><a href='eliminar_producto.php?id={$fila['id']}' class='btn btn-eliminar' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este producto?\");'>Eliminar</a></td>
    </tr>";
    }
    
    echo "</table>";

    echo "<div class='header-buttons'>
             <a class='btn' href='usuarios.php'>Regresar</a>
             <a class='btn' href='agregar_producto.php'>Agregar Producto</a>
             <a class='btn' href='grafica_vendidos.php'>Ver Gráfica de Productos Vendidos</a>
             <a class='btn' href='reporte_ventas.php'>Reporte de ventas</a>
              <a class='btn' href='DONA3D.HTML'>FIGURA 3D</a>
        </div>";
} else {
    echo "<p>No hay productos disponibles.</p>";
}





$conn->close();
?>
