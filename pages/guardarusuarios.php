<?php

session_start();

require_once("../conexion.php");

$nombre = $_POST["nombre"] ?? "";
$correo = $_POST["correo"] ?? "";
$contrasena = $_POST["contrasena"] ?? "";
$telefono = $_POST["telefono"] ?? "";

if (
    !is_string($nombre)
    || !is_string($correo)
    || !is_string($contrasena)
    || !is_string($telefono)
    || $nombre === ""
    || !filter_var($correo, FILTER_VALIDATE_EMAIL)
    || strlen($contrasena) < 8
    || $telefono === ""
) {
    http_response_code(400);
    exit("Revisá los datos ingresados. La contraseña debe tener al menos 8 caracteres.");
}

$nombre = trim($nombre);
$correo = trim($correo);
$telefono = trim($telefono);

if ($nombre === "" || !filter_var($correo, FILTER_VALIDATE_EMAIL) || $telefono === "") {
    http_response_code(400);
    exit("Revisá el nombre, el correo y el teléfono ingresados.");
}

$stmt = $conexion->prepare("SELECT ID_USUARIO FROM USUARIO WHERE CORREO = ? LIMIT 1");
$stmt->bind_param("s", $correo);
$stmt->execute();

if ($stmt->get_result()->num_rows > 0) {
    http_response_code(409);
    exit("Ese correo ya está registrado.");
}

$hashContrasena = password_hash($contrasena, PASSWORD_DEFAULT);
$conexion->begin_transaction();

try {
    $stmtUsuario = $conexion->prepare(
        "INSERT INTO USUARIO (ID_ROL, NOMBRE, CONTRASENA, CORREO)
         VALUES (3, ?, ?, ?)"
    );
    $stmtUsuario->bind_param("sss", $nombre, $hashContrasena, $correo);
    $stmtUsuario->execute();
    $idUsuario = $conexion->insert_id;

    $stmtTelefono = $conexion->prepare(
        "INSERT INTO USUARIO_TELEFONO (ID_USUARIO, TELEFONO) VALUES (?, ?)"
    );
    $stmtTelefono->bind_param("is", $idUsuario, $telefono);
    $stmtTelefono->execute();

    $conexion->commit();
} catch (mysqli_sql_exception $error) {
    $conexion->rollback();
    error_log("No se pudo registrar el usuario: " . $error->getMessage());
    http_response_code(500);
    exit("No se pudo completar el registro. Intentá nuevamente.");
}

session_regenerate_id(true);
$_SESSION["id_usuario"] = $idUsuario;
$_SESSION["nombre"] = $nombre;
$_SESSION["correo"] = $correo;
$_SESSION["id_rol"] = 3;

header("Location: ../index.php");
exit();

?>