<?php
// Conexión a la base de datos
include 'conexio.php';

$sql = "SELECT id, nombre, precio, cantidad, imagen FROM productos";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            background-size: cover;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: flex-end;
            min-height: 100vh;
        }
        .header-buttons {
            display: flex;
            justify-content: flex-end;
            width: 100%;
            padding: 20px;
        }
        table {
            width: 100%;
            max-width: 1000px;
            border-collapse: collapse;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            animation: fadeIn 1s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        table th, table td {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #007BFF;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            position: relative;
            letter-spacing: 1px;
            font-size: 18px;
        }
        table th:after {
            content: '';
            display: block;
            width: 100%;
            height: 4px;
            background-color: #0056b3;
            position: absolute;
            bottom: 0;
            left: 0;
            transition: background-color 0.3s ease;
        }
        table th:hover:after {
            background-color: #004085;
        }
        table td {
            font-size: 16px;
            background-color: #f9f9f9;
            transition: background-color 0.3s;
        }
        table tr:nth-child(even) td {
            background-color: #f2f2f2;
        }
        table tr:hover td {
            background-color: #e9f5ff;
            transition: background-color 0.3s;
        }
        img {
            border-radius: 10px;
            object-fit: cover;
            width: 80px;
            height: 80px;
            transition: transform 0.3s;
        }
        img:hover {
            transform: scale(1.1);
        }
        a {
            text-decoration: none;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            display: inline-block;
        }
        .btn-actualizar {
            background-color: #28a745;
        }
        .btn-actualizar:hover {
            background-color: #218838;
        }
        .btn-eliminar {
            background-color: #dc3545;
        }
        .btn-eliminar:hover {
            background-color: #c82333;
        }
        .btn-menu, .btn-agregar {
            background-color: #17a2b8;
            margin-left: 10px;
            padding: 12px 30px;
            font-size: 16px;
            border-radius: 5px;
            transition: transform 0.2s;
        }
        .btn-menu:hover, .btn-agregar:hover {
            background-color: #138496;
            transform: scale(1.05);
        }
    </style>
    ";

    // Contenedor para los botones
    echo "<div class='header-buttons'>
            <a class='btn-menu' href='menu.php'>Ir al Menú</a>
            <a class='btn-agregar' href='agregarproducto.php'>Agregar Producto</a>
        </div>";

    echo "<table>
            <tr>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Existencias</th>
                <th>Imagen</th>
                <th>Actualizar</th>
                <th>Eliminar</th>
            </tr>";
    
    while ($fila = $result->fetch_assoc()) {
        // Comprobar si la imagen existe y es un BLOB
        if (!empty($fila['imagen'])) {
            $imagenBase64 = base64_encode($fila['imagen']);
            $srcImagen = "data:image/jpeg;base64," . $imagenBase64;
        } else {
            $srcImagen = "ruta/a/imagen/default.jpg"; // Imagen por defecto
        }

        echo "<tr>
                <td>{$fila['nombre']}</td>
                <td>\${$fila['precio']}</td>
                <td>{$fila['cantidad']}</td>
                <td><img src='$srcImagen' alt='Imagen del producto'></td>
                <td><a class='btn-actualizar' href='actualizarproducto.php?id={$fila['id']}'>Actualizar</a></td>
                <td><a href='eliminarproducto.php?id={$fila['id']}' class='btn-eliminar' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este producto?\");'>Eliminar</a></td>
            </tr>";
    }
    
    echo "</table>";
} else {
    echo "<p>No hay productos disponibles.</p>";
}

$conn->close();
?>