<?php
// Conectar a la base de datos
include 'conex.php';

// Verificar si se ha recibido el ID del producto a eliminar
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Eliminar el producto
    $sql = "DELETE FROM productos WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: productos.php"); // Redirige de vuelta a la lista de productos
        exit();
    } else {
        echo "Error al eliminar el producto: " . $conn->error;
    }
    
    $stmt->close();
}
$conn->close();
?>
