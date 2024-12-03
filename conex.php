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

?>







