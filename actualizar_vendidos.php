<?php
// Conexión a la base de datos
include 'conex.php';

// Verificar que el formulario fue enviado a través de una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el ID del producto y la cantidad vendida del formulario
    $id = intval($_POST['id']);
    $vendidos = intval($_POST['vendidos']);
    
    // Preparar y ejecutar la consulta de actualización
    $sql = "UPDATE productos SET vendidos = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $vendidos, $id);

    if ($stmt->execute()) {
        // Redirigir a la página de productos con un mensaje de éxito
        header("Location: productos.php?mensaje=actualizacion_exitosa");
    } else {
        // Redirigir a la página de productos con un mensaje de error
        header("Location: productos.php?mensaje=error_actualizacion");
    }

    $stmt->close();
} else {
    // Si se accede al archivo sin una solicitud POST, redirige a la página de productos
    header("Location: productos.php");
}

$conn->close();
?>
