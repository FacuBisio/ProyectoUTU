<?php
session_start();

require_once("../config/config.php");
require_once("../conexion.php");

function volverAComentarios($ruta)
{
    if (!is_string($ruta)) {
        $ruta = BASE_URL;
    }

    $rutaAnalizada = parse_url($ruta);
    $rutaLocal = $rutaAnalizada === false ? '' : ($rutaAnalizada['path'] ?? '');

    if (
        $rutaAnalizada === false
        || strpos($rutaLocal, BASE_URL) !== 0
        || strpos($ruta, "\r") !== false
        || strpos($ruta, "\n") !== false
    ) {
        $ruta = BASE_URL;
    }

    header("Location: " . $ruta . "#comentarios");
    exit;
}

$destino = $_POST['return_to'] ?? BASE_URL;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Método no permitido.');
}

if (!isset($_SESSION['id_usuario'])) {
    $_SESSION['flash_comentario'] = [
        'tipo' => 'error',
        'mensaje' => 'Iniciá sesión para publicar un comentario.',
    ];
    volverAComentarios($destino);
}

$tokenEnviado = $_POST['csrf_token'] ?? '';
if (
    !is_string($tokenEnviado)
    || empty($_SESSION['csrf_comentario'])
    || !hash_equals($_SESSION['csrf_comentario'], $tokenEnviado)
) {
    $_SESSION['flash_comentario'] = [
        'tipo' => 'error',
        'mensaje' => 'No se pudo validar el envío. Recargá la página e intentá nuevamente.',
    ];
    volverAComentarios($destino);
}

$textoEnviado = $_POST['comentario'] ?? '';
if (!is_string($textoEnviado)) {
    $_SESSION['flash_comentario'] = [
        'tipo' => 'error',
        'mensaje' => 'El comentario enviado no es válido.',
    ];
    volverAComentarios($destino);
}

$texto = trim($textoEnviado);
if (!mb_check_encoding($texto, 'UTF-8')) {
    $_SESSION['flash_comentario'] = [
        'tipo' => 'error',
        'mensaje' => 'El comentario debe contener texto válido.',
    ];
    volverAComentarios($destino);
}

$longitud = mb_strlen($texto, 'UTF-8');
if ($longitud === 0 || $longitud > 2000) {
    $_SESSION['flash_comentario'] = [
        'tipo' => 'error',
        'mensaje' => 'El comentario debe tener entre 1 y 2000 caracteres.',
    ];
    volverAComentarios($destino);
}

try {
    $stmt = $conexion->prepare(
        "INSERT INTO COMENTARIO (ID_USUARIO, COMENTARIO) VALUES (?, ?)"
    );
    $idUsuario = (int) $_SESSION['id_usuario'];
    $stmt->bind_param('is', $idUsuario, $texto);
    $stmt->execute();

    $_SESSION['flash_comentario'] = [
        'tipo' => 'exito',
        'mensaje' => '¡Tu comentario se publicó correctamente!',
    ];
} catch (mysqli_sql_exception $error) {
    error_log("No se pudo guardar el comentario: " . $error->getMessage());
    $_SESSION['flash_comentario'] = [
        'tipo' => 'error',
        'mensaje' => 'No se pudo publicar el comentario. Intentá nuevamente más tarde.',
    ];
}

volverAComentarios($destino);
