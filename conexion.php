<?php

// Credenciales locales de MySQL para el entorno de desarrollo con XAMPP.
$servidor = "localhost";
$usuario = "root";
$password = "";
$baseDatos = "proyectoutu";

// Comparte una única conexión con los scripts que incluyen este archivo.
$conexion = new mysqli($servidor, $usuario, $password, $baseDatos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if (!$conexion->set_charset("utf8mb4")) {
    die("Error al configurar la codificación de la base de datos: " . $conexion->error);
}