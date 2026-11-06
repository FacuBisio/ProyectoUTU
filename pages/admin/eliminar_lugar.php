<?php

require_once(__DIR__ . "/../../config/sesion.php");
iniciarSesionSegura();
require_once("../../conexion.php");

if (!isset($_SESSION["id_usuario"]) || (int) ($_SESSION["id_rol"] ?? 0) !== 1) {
    http_response_code(403);
    exit("Acceso denegado.");
}

$token = $_POST["csrf_token"] ?? "";
if (
    $_SERVER["REQUEST_METHOD"] !== "POST"
    || !is_string($token)
    || empty($_SESSION["csrf_admin_lugares"])
    || !hash_equals($_SESSION["csrf_admin_lugares"], $token)
) {
    http_response_code($_SERVER["REQUEST_METHOD"] === "POST" ? 400 : 405);
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        header("Allow: POST");
    }
    exit("La solicitud venció o no es válida. Recarga la página e inténtalo de nuevo.");
}

$idLugarRecibido = $_POST["id_lugar"] ?? null;
$id_lugar = is_string($idLugarRecibido)
    ? filter_var($idLugarRecibido, FILTER_VALIDATE_INT)
    : false;

if (!$id_lugar || $id_lugar < 1) {
    http_response_code(400);
    exit("Lugar no encontrado.");
}

// Quita primero las referencias y sus eventos, y finalmente el lugar.
try {
    $conexion->begin_transaction();
    foreach ([
        "DELETE FROM EVENTO WHERE ID_LUGAR = ?",
        "DELETE FROM LUGAR_CATEGORIA WHERE ID_LUGAR = ?",
        "DELETE FROM LUGAR WHERE ID_LUGAR = ?",
    ] as $sql) {
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id_lugar);
        $stmt->execute();
    }
    $conexion->commit();
} catch (mysqli_sql_exception $error) {
    $conexion->rollback();
    error_log("No se pudo eliminar el lugar {$id_lugar}: " . $error->getMessage());
    http_response_code(500);
    exit("No se pudo eliminar el lugar. Inténtalo de nuevo.");
}


header("Location: lugares.php");

exit();

?>