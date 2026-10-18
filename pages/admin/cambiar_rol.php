<?php

session_start();

require_once("../../conexion.php");

if (!isset($_SESSION["id_usuario"]) || (int) ($_SESSION["id_rol"] ?? 0) !== 1) {
    http_response_code(403);
    exit("Acceso denegado.");
}

$idUsuario = filter_input(INPUT_POST, "id_usuario", FILTER_VALIDATE_INT);
$idRol = filter_input(INPUT_POST, "id_rol", FILTER_VALIDATE_INT);

if ($idUsuario === false || $idUsuario === null || $idRol === false || $idRol === null) {
    header("Location: usuarios.php?error=rol_no_valido");
    exit();
}

if ((int) $idUsuario === (int) $_SESSION["id_usuario"]) {
    header("Location: usuarios.php?error=rol_protegido");
    exit();
}

if (!in_array($idRol, [2, 3], true)) {
    header("Location: usuarios.php?error=rol_protegido");
    exit();
}

$consultaRolActual = $conexion->prepare("SELECT id_rol FROM usuario WHERE id_usuario = ? LIMIT 1");
$consultaRolActual->bind_param("i", $idUsuario);
$consultaRolActual->execute();
$rolActual = $consultaRolActual->get_result()->fetch_assoc();

if (!$rolActual || (int) $rolActual["id_rol"] === 1) {
    header("Location: usuarios.php?error=rol_protegido");
    exit();
}

$sql = "UPDATE usuario SET id_rol = ? WHERE id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $idRol, $idUsuario);

if ($stmt->execute()) {
    header("Location: usuarios.php");
    exit();
}

http_response_code(500);
exit("Error al actualizar el rol.");

?>