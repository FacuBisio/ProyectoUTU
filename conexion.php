<?php
$servidor = "localhost";
$usuario = "root";
$password = "";
$baseDatos = "proyectoutu";

$conexion = new mysqli($servidor, $usuario, $password, $baseDatos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if (!$conexion->set_charset("utf8mb4")) {
    die("Error al configurar la codificación de la base de datos: " . $conexion->error);
}
?>