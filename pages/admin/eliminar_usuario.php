<?php

session_start();

require_once("../../conexion.php");


if (!isset($_SESSION["id_usuario"]) || (int) ($_SESSION["id_rol"] ?? 0) !== 1) {
    http_response_code(403);
    exit("Acceso denegado.");
}

$id_usuario = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if ($id_usuario === false || $id_usuario === null) {
    header("Location: usuarios.php?error=usuario_no_valido");
    exit();
}

if ((int) $id_usuario === (int) $_SESSION["id_usuario"]) {
    header("Location: usuarios.php?error=auto_eliminacion");
    exit();
}

$consultaRol = $conexion->prepare("SELECT id_rol FROM usuario WHERE id_usuario = ? LIMIT 1");
$consultaRol->bind_param("i", $id_usuario);
$consultaRol->execute();
$resultadoRol = $consultaRol->get_result()->fetch_assoc();

if (!$resultadoRol || (int) $resultadoRol["id_rol"] === 1) {
    header("Location: usuarios.php?error=rol_protegido");
    exit();
}

// Elimina las dependencias antes que la cuenta para conservar la integridad referencial.
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

exit();

?>