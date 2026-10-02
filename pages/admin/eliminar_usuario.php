<?php

session_start();

require_once("../../conexion.php");


if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 1) {

    die("Acceso denegado");

}


$id_usuario = $_GET["id"];


// Evitar eliminarse a sí mismo
if ($id_usuario == $_SESSION["id_usuario"]) {

    die("No puedes eliminar tu propio usuario.");

}


// Eliminar usuario

$sql = "DELETE FROM usuario WHERE id_usuario = ?";

$conexion->begin_transaction();

try {

    // Eliminar comentarios del usuario
    $sql = "DELETE FROM comentario WHERE ID_USUARIO = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();

    // Eliminar eventos del usuario
    $sql = "DELETE FROM evento WHERE ID_USUARIO = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();

    // Eliminar usuario
    $sql = "DELETE FROM usuario WHERE ID_USUARIO = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();

    $conexion->commit();

    header("Location: usuarios.php");
    exit();

} catch (mysqli_sql_exception $e) {

    $conexion->rollback();

    echo "No se pudo eliminar el usuario.";
}


if($stmt->execute()){

    header("Location: usuarios.php");

}else{

    echo "Error al eliminar: " . $conexion->error;

}


exit();

?>