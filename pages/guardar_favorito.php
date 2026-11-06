<?php
require_once(__DIR__ . "/../config/sesion.php");
iniciarSesionSegura();
require_once(__DIR__ . "/../conexion.php");
require_once(__DIR__ . "/../config/config.php");

if (!isset($_SESSION["id_usuario"])) {
    header("Location: " . url("pages/login.php"));
    exit();
}
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    header("Allow: POST");
    exit("Método no permitido.");
}

$token = $_POST["csrf_token"] ?? "";
$idLugar = filter_input(INPUT_POST, "id_lugar", FILTER_VALIDATE_INT);
$accion = $_POST["accion"] ?? "";
$volver = $_POST["volver"] ?? "";
if (
    !is_string($token)
    || empty($_SESSION["csrf_favorito"])
    || !hash_equals($_SESSION["csrf_favorito"], $token)
    || !$idLugar
    || $idLugar < 1
    || !in_array($accion, ["guardar", "quitar"], true)
    || !in_array($volver, ["lugar", "favoritos"], true)
) {
    http_response_code(400);
    exit("La solicitud para guardar el lugar no es válida.");
}

$stmtExiste = $conexion->prepare("SELECT 1 FROM LUGAR WHERE ID_LUGAR = ? LIMIT 1");
$stmtExiste->bind_param("i", $idLugar);
$stmtExiste->execute();
$existe = $stmtExiste->get_result()->num_rows > 0;
$stmtExiste->close();
if (!$existe) {
    http_response_code(404);
    exit("No se encontró ese lugar.");
}

$idUsuario = (int) $_SESSION["id_usuario"];
if ($accion === "guardar") {
    $stmtGuardar = $conexion->prepare(
        "INSERT IGNORE INTO USUARIO_LUGAR_GUARDADO (ID_USUARIO, ID_LUGAR) VALUES (?, ?)"
    );
    $stmtGuardar->bind_param("ii", $idUsuario, $idLugar);
    $stmtGuardar->execute();
    $stmtGuardar->close();
    $_SESSION["flash_favorito"] = "Lugar guardado en tu ruta.";
} else {
    $stmtQuitar = $conexion->prepare(
        "DELETE FROM USUARIO_LUGAR_GUARDADO WHERE ID_USUARIO = ? AND ID_LUGAR = ?"
    );
    $stmtQuitar->bind_param("ii", $idUsuario, $idLugar);
    $stmtQuitar->execute();
    $stmtQuitar->close();
    $_SESSION["flash_favorito"] = "Lugar quitado de tu ruta.";
}

if ($volver === "favoritos") {
    header("Location: " . url("pages/favoritos.php"));
} else {
    header("Location: " . url("pages/lugar.php?id=" . $idLugar));
}
exit();
