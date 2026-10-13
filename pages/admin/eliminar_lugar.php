<?php

session_start();
require_once("../../conexion.php");

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 1) {
    die("Acceso denegado.");
}

$id_lugar = $_GET["id"] ?? null;

if (!$id_lugar) {
    die("Lugar no encontrado.");
}

// Quita primero las referencias y sus eventos, y finalmente el lugar.
foreach ([
    "DELETE FROM EVENTO WHERE ID_LUGAR = ?",
    "DELETE FROM LUGAR_CATEGORIA WHERE ID_LUGAR = ?",
    "DELETE FROM LUGAR WHERE ID_LUGAR = ?",
] as $sql) {
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_lugar);
    $stmt->execute();
}


header("Location: lugares.php");

exit();

?>