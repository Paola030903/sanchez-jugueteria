<?php
include 'conex.php'; // Asegúrate de incluir tu archivo de conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del usuario
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $rol = $_POST['rol'];

    // Preparar la consulta SQL para actualizar el usuario
    $sql = "UPDATE unicornio SET NOMBRE = ?, CORREO = ?, rol = ? WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nombre, $correo, $rol, $id);

    // Ejecutar la consulta y comprobar si se actualizó correctamente
    if ($stmt->execute()) {
        echo "Usuario actualizado exitosamente.";
    } else {
        echo "Error al actualizar el usuario: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

