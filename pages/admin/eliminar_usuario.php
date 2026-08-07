<?php

session_start();

require_once("../../conexion.php");


if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 3) {

    die("Acceso denegado");

}


$id_usuario = $_GET["id"];


// Evitar eliminarse a sí mismo
if ($id_usuario == $_SESSION["id_usuario"]) {

    die("No puedes eliminar tu propio usuario.");

}


// Eliminar usuario

$sql = "DELETE FROM usuario WHERE id_usuario = ?";

$stmt = $conexion->prepare($sql);

$stmt->bind_param(
    "i",
    $id_usuario
);


if($stmt->execute()){

    header("Location: usuarios.php");

}else{

    echo "Error al eliminar: " . $conexion->error;

}


exit();

?>