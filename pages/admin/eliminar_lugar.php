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

/*
    Primero eliminamos los eventos
    que pertenecen al lugar.
*/

$sql = "DELETE FROM EVENTO WHERE ID_LUGAR = ?";

$stmt = $conexion->prepare($sql);

$stmt->bind_param(
    "i",
    $id_lugar
);

$stmt->execute();


/*
    Después eliminamos la relación
    entre lugar y categoría.
*/

$sql = "DELETE FROM LUGAR_CATEGORIA WHERE ID_LUGAR = ?";

$stmt = $conexion->prepare($sql);

$stmt->bind_param(
    "i",
    $id_lugar
);

$stmt->execute();


/*
    Finalmente eliminamos el lugar.
*/

$sql = "DELETE FROM LUGAR WHERE ID_LUGAR = ?";

$stmt = $conexion->prepare($sql);

$stmt->bind_param(
    "i",
    $id_lugar
);

$stmt->execute();


header("Location: lugares.php");

exit();

?>