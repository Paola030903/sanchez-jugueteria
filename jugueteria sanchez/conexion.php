<?php
$host = 'localhost'; // Tu host de base de datos
$user = 'root'; // Tu usuario de base de datos
$password = ''; // Tu contraseña de base de datos
$db = 'juguete'; // Nombre de la base de datos
// Crear conexión
$conn = new mysqli($host, $user, $password, $db, );

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

/* Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['NOMBRE'];
    $correo = $_POST['CORREO'];
    $contrasena = $_POST['contrasena']; // Guardar contraseña sin encriptar

    // Consulta para insertar el nuevo usuario
    $sql = "INSERT INTO unicornio (NOMBRE, CORREO, CONTRA SENA) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Comprobar si la preparación fue exitosa
    if ($stmt) {
        // Corregido: se pasan los 3 parámetros correctamente
        $stmt->bind_param("sss", $nombre, $contrasena, $correo);

        if ($stmt->execute()) {
            echo "Registro exitoso. <a href='juguete.php'>Inicia sesión aquí</a>";
        } else {
            echo "Error en el registro: " . $stmt->error;
        }
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
    }
}*/


?>







