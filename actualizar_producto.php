<?php
include 'conex.php'; // Archivo de conexión

// Verificar si se ha enviado el ID del producto
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Consulta para obtener los datos del producto con el ID proporcionado
    $query = "SELECT nombre, precio, cantidad, imagen FROM productos WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Obtener los datos del producto
        $row = $result->fetch_assoc();
    } else {
        echo "Producto no encontrado.";
        exit();
    }
} else {
    echo "ID de producto no proporcionado.";
    exit();
}

// Verificar si se ha enviado el formulario para actualizar los datos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];

    // Procesar la imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imagenTemp = $_FILES['imagen']['tmp_name'];
        $imagenContenido = file_get_contents($imagenTemp);  // Guardar la imagen en formato binario (BLOB)

        // Consulta para actualizar los datos del producto
        $update_query = "UPDATE productos SET nombre = ?, precio = ?, cantidad = ?, imagen = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("sdssi", $nombre, $precio, $cantidad, $imagenContenido, $id);
    } else {
        // Si no se carga una nueva imagen, actualizamos solo los otros campos
        $update_query = "UPDATE productos SET nombre = ?, precio = ?, cantidad = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("sdii", $nombre, $precio, $cantidad, $id);
    }

    if ($stmt->execute()) {
        // Redirigir de nuevo a la lista de productos
        header("Location: productos.php");
        exit();
    } else {
        echo "Error al actualizar los datos: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Producto</title>
</head>
<body>

    <form method="POST" enctype="multipart/form-data">
        <h2>Actualizar Producto</h2>
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($row['nombre']); ?>" required>

        <label for="precio">Precio:</label>
        <input type="number" id="precio" name="precio" step="0.01" value="<?php echo htmlspecialchars($row['precio']); ?>" required>

        <label for="cantidad">Cantidad:</label>
        <input type="number" id="cantidad" name="cantidad" value="<?php echo htmlspecialchars($row['cantidad']); ?>" required>

        <label for="imagen">Imagen:</label>
        <input type="file" id="imagen" name="imagen">

        <input type="submit" value="Actualizar">

        <!-- Botón para regresar a listado_productos.php -->
        <a href="alta_producto.php" class="back-button">Regresar</a>
    </form>

</body>
</html>
