<?php
// Incluir archivo de conexión a la base de datos
include "./conexion.php";
session_start();

// Verificar si el administrador ha iniciado sesión
if (!isset($_SESSION['admin_id'])) {
    header("Location:usuarios.php");
    exit();
}
?>



