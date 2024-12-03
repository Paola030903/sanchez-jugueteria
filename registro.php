<?php
// Incluye la lógica de conexión y registro aquí, o simplemente usa el archivo anterior.

include 'conexion.php'; // Asumiendo que tienes todo el código de conexión aquí.
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <style>
        /* Tu CSS aquí */
    </style>
</head>
<body>
    <center>
    <div class="container">
        <h1>Registrarse</h1>
        <form method="POST" action="">
            <label for="NOMBRE">Nombre:</label>
            <input type="text" name="NOMBRE" required>
            <br><br>
            
            <label for="CONTRASENA">Contrasena:</label>
            <input type="password" name="pass" required>
            <br><br>

            <label for="CORREO">Correo:</label>
            <input type="email" name="CORREO" required>
            
<br><br>
            <input type="submit" value="Registrarse">
        </form>
    </div>
    </center>
    <?php 
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nombre = $_POST['NOMBRE'];
        $correo = $_POST['CORREO'];
        $contrasena = $_POST['pass'];
    
        // Consulta para insertar el nuevo usuario
        $sql = "INSERT INTO unicornio (NOMBRE, CORREO, CONTRASENA) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
    
        // Comprobar si la preparación fue exitosa
        if ($stmt) {
            // Corregido: se pasan los 3 parámetros correctamente
            $stmt->bind_param("sss", $nombre, $correo, $contrasena);
    
            if ($stmt->execute()) {
                echo "Registro exitoso. <a href='juguete.php'>Inicia sesión aquí</a>";
            } else {
                echo "Error en el registro: " . $stmt->error;
            }
        } else {
            echo "Error en la preparación de la consulta: " . $conn->error;
        }
    }
    ?>
</body>
</html>
