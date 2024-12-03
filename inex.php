<?php
include "./conexion.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login y Registro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <img src="35293.jpg" width="400" height="400">
    <div class="container">
        <h2>"JUGUETERIA SANCHEZ"</h2>
        <form method="POST" action="">
            <label for="NOMBRE">Nombre de usuario:</label>
            <input type="text" id="NOMBRE" name="NOMBRE" required>
            <br>
            <label for="CONTRASENA">Contraseña:</label>
            <br>
            <input type="password" id="CONTRASENA" name="CONTRASENA" required>
            <br><br>
            <input type="submit" name="login" value="Iniciar Sesión">
        </form>
        <br>

        <form method="GET" action="registro.php">
            <input type="submit" value="Registrar Usuario">
        </form>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['login'])) {
            // Manejo de inicio de sesión
            $usuario = $_POST['NOMBRE'];
            $contrasena = $_POST['CONTRASENA'];

            // Consulta para verificar el usuario y la contraseña
            $sql = "SELECT * FROM unicornio WHERE NOMBRE = ? AND CONTRASENA = ?";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param('ss', $usuario, $contrasena);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    // Si el usuario y la contraseña son correctos
                    $row = $result->fetch_assoc();
                    
                    // Verificar el rol del usuario
                    if ($row['rol'] == 'admin') {
                        // Redirigir al panel de productos del administrador
                        header("Location: productos_admin.php");
                    } else {
                        // Redirigir al menú normal
                        header("Location: menu.php");
                    }
                    exit(); // Detener el script después de la redirección
                } else {
                    // Usuario o contraseña incorrectos
                    $error_message = "Nombre o contraseña incorrectos.";
                    echo "<p class='error'>$error_message</p>";
                }
            } else {
                // Error en la preparación de la consulta
                echo "<p class='error'>Error al preparar la consulta SQL.</p>";
            }
        }
    }
    ?>
</body>
</html>
