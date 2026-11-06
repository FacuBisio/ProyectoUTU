<?php

$entorno = getenv("APP_ENV") ?: "development";
$servidor = getenv("DB_HOST") ?: "localhost";
$baseDatos = getenv("DB_NAME") ?: "proyectoutu";

if ($entorno === "production") {
    $usuario = getenv("DB_USER");
    $password = getenv("DB_PASSWORD");
    if (!is_string($usuario) || $usuario === "" || !is_string($password) || $password === "") {
        error_log("Falta configurar DB_USER o DB_PASSWORD para el entorno de producción.");
        http_response_code(500);
        exit("El servicio de datos no está configurado.");
    }
} else {
    // Valores predeterminados para la instalación local de XAMPP.
    $usuario = getenv("DB_USER") ?: "root";
    $password = getenv("DB_PASSWORD");
    $password = is_string($password) ? $password : "";
}

try {
    // Comparte una única conexión con los scripts que incluyen este archivo.
    $conexion = new mysqli($servidor, $usuario, $password, $baseDatos);
    if ($conexion->connect_error) {
        throw new mysqli_sql_exception($conexion->connect_error);
    }
    if (!$conexion->set_charset("utf8mb4")) {
        throw new mysqli_sql_exception($conexion->error);
    }
} catch (mysqli_sql_exception $error) {
    error_log("No se pudo inicializar la conexión MySQL: " . $error->getMessage());
    http_response_code(500);
    exit("No se pudo conectar con el servicio de datos.");
}