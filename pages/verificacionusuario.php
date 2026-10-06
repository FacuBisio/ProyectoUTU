<?php

session_start();

require_once("../conexion.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    header("Allow: POST");
    exit("Método no permitido.");
}

$correo = $_POST["correo"] ?? "";
$contrasena = $_POST["contrasena"] ?? "";

if (!is_string($correo) || !is_string($contrasena) || $correo === "" || $contrasena === "") {
    header("Location: login.php?error=invalid");
    exit();
}

$correo = trim($correo);

$stmt = $conexion->prepare(
    "SELECT ID_USUARIO, ID_ROL, NOMBRE, CONTRASENA, CORREO
     FROM USUARIO
     WHERE CORREO = ?
     LIMIT 1"
);
$stmt->bind_param("s", $correo);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();

if (!$usuario) {
    header("Location: login.php?error=invalid");
    exit();
}

$contrasenaGuardada = $usuario["CONTRASENA"];
$esHash = password_get_info($contrasenaGuardada)["algo"] !== null;
$contrasenaValida = $esHash
    ? password_verify($contrasena, $contrasenaGuardada)
    : hash_equals($contrasenaGuardada, $contrasena);

if (!$contrasenaValida) {
    header("Location: login.php?error=invalid");
    exit();
}

if (!$esHash) {
    $nuevoHash = password_hash($contrasena, PASSWORD_DEFAULT);
    $actualizarContrasena = $conexion->prepare(
        "UPDATE USUARIO SET CONTRASENA = ? WHERE ID_USUARIO = ?"
    );
    $actualizarContrasena->bind_param("si", $nuevoHash, $usuario["ID_USUARIO"]);
    $actualizarContrasena->execute();
}

session_regenerate_id(true);
$_SESSION["id_usuario"] = $usuario["ID_USUARIO"];
$_SESSION["id_rol"] = $usuario["ID_ROL"];
$_SESSION["nombre"] = $usuario["NOMBRE"];
$_SESSION["correo"] = $usuario["CORREO"];

if ((int) $usuario["ID_ROL"] === 1) {
    header("Location: admin/panel.php");
} else {
    header("Location: ../index.php");
}
exit();

?>
