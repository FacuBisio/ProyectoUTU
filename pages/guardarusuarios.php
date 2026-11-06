<?php

require_once(__DIR__ . "/../config/sesion.php");
iniciarSesionSegura();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    header("Allow: POST");
    exit("Método no permitido.");
}

$token = $_POST["csrf_token"] ?? "";
if (
    !is_string($token)
    || empty($_SESSION["csrf_registro"])
    || !hash_equals($_SESSION["csrf_registro"], $token)
) {
    http_response_code(400);
    exit("La solicitud venció o no es válida. Recarga la página e inténtalo de nuevo.");
}

require_once("../conexion.php");

// Valida los campos antes de crear la cuenta y su teléfono asociado.
$nombre = $_POST["nombre"] ?? "";
$correo = $_POST["correo"] ?? "";
$contrasena = $_POST["contrasena"] ?? "";
$telefono = $_POST["telefono"] ?? "";

if (
    !is_string($nombre)
    || !is_string($correo)
    || !is_string($contrasena)
    || !is_string($telefono)
) {
    http_response_code(400);
    exit("Revisa los datos enviados.");
}

$nombre = trim($nombre);
$correo = trim($correo);
$telefono = trim($telefono);

if (
    $nombre === ""
    || mb_strlen($nombre, "UTF-8") > 100
    || !filter_var($correo, FILTER_VALIDATE_EMAIL)
    || mb_strlen($correo, "UTF-8") > 150
    || strlen($contrasena) < 8
    || strlen($contrasena) > 72
    || $telefono === ""
    || mb_strlen($telefono, "UTF-8") > 30
) {
    http_response_code(400);
    exit("Revisa el nombre, correo y teléfono; la contraseña debe tener entre 8 y 72 bytes.");
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

// Guarda los datos relacionados en una sola transacción.
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
    if ($error->getCode() === 1062) {
        http_response_code(409);
        exit("Ese correo ya está registrado.");
    }
    error_log("No se pudo registrar el usuario: " . $error->getMessage());
    http_response_code(500);
    exit("No se pudo completar el registro. Inténtalo de nuevo.");
}

// Inicia la sesión del nuevo usuario solo después de confirmar la transacción.
session_regenerate_id(true);
unset($_SESSION["csrf_registro"]);
$_SESSION["id_usuario"] = $idUsuario;
$_SESSION["nombre"] = $nombre;
$_SESSION["correo"] = $correo;
$_SESSION["id_rol"] = 3;

header("Location: ../index.php");
exit();

?>