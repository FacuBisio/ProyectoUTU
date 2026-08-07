<?php

session_start();

require_once("../../conexion.php");


if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 3) {

    die("Acceso denegado.");

}


$id_lugar = $_GET["id"];



$sql = "DELETE FROM lugar WHERE id_lugar = ?";


$stmt = $conexion->prepare($sql);


$stmt->bind_param(
    "i",
    $id_lugar
);



if($stmt->execute()){

    header("Location: lugares.php");

}else{

    echo "Error al eliminar: ".$conexion->error;

}


exit();

?>