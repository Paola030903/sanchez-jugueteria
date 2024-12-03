<?php
// Parámetros de conexión
$servername = "localhost";  // o el nombre de tu servidor de base de datos
$username = "root";   // tu nombre de usuario de la base de datos
$password = ""; // tu contraseña de la base de datos
$dbname = "juguete"; // el nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// A partir de aquí, ya puedes usar $conn para realizar consultas a la base de datos
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .container {
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-bottom: 10px; /* Añadido para margen entre botones */
        }
        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<div class="header">
        <h1>Panel de Altas de Usuarios</h1>
        <p>Bienvenido, Administrador</p>
    </div>
    
    <div class="container">
        <h2>Gestión de Usuarios</h2>

        <table>
            <tr>
                <th>ID</th>
                <th>Nombre de Usuario</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
            <?php
            // Mostrar usuarios
            $sql_usuarios = "SELECT * FROM unicornio";
            $result_usuarios = $conn->query($sql_usuarios);
            if ($result_usuarios->num_rows > 0) {
                while ($row = $result_usuarios->fetch_assoc()) {
                    echo "<tr id='fila-usuario-{$row['ID']}'>
                        <td>{$row['ID']}</td>
                        <td id='nombre-{$row['ID']}'>{$row['NOMBRE']}</td>
                        <td id='correo-{$row['ID']}'>{$row['CORREO']}</td>
                        <td id='rol-{$row['ID']}'>{$row['rol']}</td>
                        <td>
                            <a class='btn' href='#' onclick='mostrarFormulario({$row['ID']})'>Actualizar</a>
                            <a class='btn' href='#' onclick='return confirmarEliminacion({$row['ID']}, \"{$row['NOMBRE']}\")'>Eliminar</a>
                        </td>
                    </tr>
                    <tr id='form-usuario-{$row['ID']}' class='form-actualizar'>
                        <td colspan='5'>
                            <form id='formActualizar{$row['ID']}' onsubmit='return actualizarUsuario({$row['ID']});'>
                                <input type='hidden' name='id' value='{$row['ID']}'>
                                <label for='nombre'>Nombre:</label>
                                <input type='text' id='input-nombre-{$row['ID']}' name='nombre' value='{$row['NOMBRE']}' required><br><br>
                                <label for='correo'>Email:</label>
                                <input type='email' id='input-correo-{$row['ID']}' name='correo' value='{$row['CORREO']}' required><br><br>
                                <label for='rol'>Rol:</label>
                                <input type='text' id='input-rol-{$row['ID']}' name='rol' value='{$row['rol']}' required><br><br>
                                <button type='submit' class='btn'>Guardar Cambios</button>
                                <button type='button' class='btn' onclick='ocultarFormulario({$row['ID']})'>Cancelar</button>
                            </form>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No hay usuarios registrados.</td></tr>";
            }
            ?>
        </table>

        <a class="btn" href="productos.php">Ir a Productos</a>
        <a class="btn" href="juguete.php">cerrar sesión</a>
        
    </div>

    <script>
    // Mostrar el formulario de actualización de un usuario específico
    function mostrarFormulario(id) {
        document.getElementById('fila-usuario-' + id).style.display = 'none';
        document.getElementById('form-usuario-' + id).style.display = 'table-row';
    }

    // Ocultar el formulario de actualización y mostrar la fila original
    function ocultarFormulario(id) {
        document.getElementById('fila-usuario-' + id).style.display = 'table-row';
        document.getElementById('form-usuario-' + id).style.display = 'none';
    }

    // Actualizar usuario sin recargar la página
    function actualizarUsuario(id) {
        const nombre = document.getElementById('input-nombre-' + id).value;
        const correo = document.getElementById('input-correo-' + id).value;
        const rol = document.getElementById('input-rol-' + id).value;

        const formData = new FormData();
        formData.append('id', id);
        formData.append('nombre', nombre);
        formData.append('correo', correo);
        formData.append('rol', rol);

        // Enviar los datos con fetch (AJAX)
        fetch('actualizar_usuario.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            // Actualizar la tabla con los nuevos datos sin recargar la página
            document.getElementById('nombre-' + id).innerText = nombre;
            document.getElementById('correo-' + id).innerText = correo;
            document.getElementById('rol-' + id).innerText = rol;

            // Ocultar el formulario y mostrar la fila actualizada
            ocultarFormulario(id);

            alert('Usuario actualizado correctamente');
        })
        .catch(error => {
            alert('Error al actualizar el usuario');
            console.error('Error:', error);
        });

        return false; // Prevenir el envío del formulario por defecto
    }

    // Confirmación para eliminar usuario
    function confirmarEliminacion(id, nombre) {
        const confirmacion = confirm(`¿Estás seguro de que deseas eliminar al usuario "${nombre}"?`);
        if (confirmacion) {
            window.location.href = `eliminar_usuario.php?id=${id}`;
        } else {
            return false;
        }
    }
    </script>



</body>
</html>