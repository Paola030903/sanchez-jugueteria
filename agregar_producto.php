<?php
// Conexión a la base de datos
include 'conex.php';
// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Recibir datos del formulario
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];


    // Procesar la imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imagenTemp = $_FILES['imagen']['tmp_name'];
        $imagenNombre = $_FILES['imagen']['name'];
        $imagenTipo = $_FILES['imagen']['type'];
        $imagenContenido = file_get_contents($imagenTemp);  // Guardar la imagen en formato binario (BLOB)

        // Insertar el producto en la base de datos
        $sql = "INSERT INTO productos (nombre, precio, cantidad, imagen) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdis", $nombre, $precio, $cantidad, $imagenContenido);

        if ($stmt->execute()) {
            header('location: productos.php');
            exit;
        } else {
            echo "Error al agregar el producto: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error: No se puedo cargar la imagen.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto</title>
</head>
<body>

    <div class="form-container">
        <h2>Agregar Producto</h2>
        <form action="agregar_producto.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nombre">Nombre del producto:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="precio">Precio:</label>
                <input type="number" id="precio" name="precio" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="cantidad">Cantidad:</label>
                <input type="number" id="cantidad" name="cantidad" required>
            </div>
            <div class="form-group">
                <label for="imagen">Subir imagen del producto:</label>
                <input type="file" id="imagen" name="imagen" accept="image/*" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Agregar Producto">
            </div>
        </form>
    </div>

    <div id="mensaje" style="margin-top: 20px;">
    <?php
        // Mostrar el mensaje si existe
        if (!empty($mensaje)) {
            echo $mensaje;
        }
    ?>
</div>    

</body>
</html>