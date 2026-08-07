<?php

session_start();

require_once("../../conexion.php");


if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 3) {

    die("Acceso denegado");

}


$id_usuario = $_POST["id_usuario"];
$id_rol = $_POST["id_rol"];


$sql = "UPDATE usuario 
        SET id_rol = ?
        WHERE id_usuario = ?";


$stmt = $conexion->prepare($sql);

$stmt->bind_param(
    "ii",
    $id_rol,
    $id_usuario
);


if($stmt->execute()){

    header("Location: usuarios.php");

}else{

    echo "Error al actualizar";

}


exit();

?>