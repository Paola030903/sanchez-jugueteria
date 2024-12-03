<?php
// Conectar a la base de datos
include 'conexion.php'; // Asegúrate de tener el archivo de conexión aquí

// Verificar si se ha pasado un ID de usuario
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Preparar la consulta para eliminar al usuario
    $sql = "DELETE FROM unicornio WHERE ID = $id";
    
    if ($conn->query($sql) === TRUE) {
        echo "Usuario eliminado correctamente";
    } else {
        echo "Error al eliminar el usuario: " . $conn->error;
    }
    
    // Redirigir de vuelta al panel después de la eliminación
    header("Location: usuarios.php");
    exit();
} else {
    echo "ID de usuario no especificado.";
}
?>
