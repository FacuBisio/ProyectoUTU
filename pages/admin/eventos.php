<?php
session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../login.php");
    exit();
}

if ((int) ($_SESSION["id_rol"] ?? 0) !== 1) {
    http_response_code(403);
    exit("Acceso denegado. Esta sección es solo para administradores.");
}

require_once("../../conexion.php");

if (empty($_SESSION["csrf_admin_eventos"])) {
    $_SESSION["csrf_admin_eventos"] = bin2hex(random_bytes(32));
}

function escaparAdminEvento($valor)
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, "UTF-8");
}

function redirigirAdminEventos($tipo, $mensaje, $eventoId = null)
{
    $_SESSION["flash_admin_eventos"] = ["tipo" => $tipo, "mensaje" => $mensaje];
    $destino = "eventos.php";
    if ($eventoId !== null) {
        $destino .= "?editar=" . (int) $eventoId;
    }
    header("Location: " . $destino);
    exit();
}

function rutaPortadaAdminEvento($imagen)
{
    if (
        !is_string($imagen)
        || preg_match('~^assets/img/eventos/[a-f0-9]{32}\.(?:jpg|png|webp)$~', $imagen) !== 1
    ) {
        return null;
    }

    return __DIR__ . "/../../" . $imagen;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST["csrf_token"] ?? "";
    if (!is_string($token) || !hash_equals($_SESSION["csrf_admin_eventos"], $token)) {
        http_response_code(400);
        exit("La solicitud venció o no es válida. Recargá la página e intentá nuevamente.");
    }

    $accion = $_POST["accion"] ?? "";
    $idRecibido = $_POST["id_evento"] ?? null;
    $eventoId = is_string($idRecibido) ? filter_var($idRecibido, FILTER_VALIDATE_INT) : false;
    if (!$eventoId || $eventoId < 1 || !in_array($accion, ["editar", "eliminar"], true)) {
        http_response_code(400);
        exit("Los datos enviados no son válidos.");
    }

    $stmtExistente = $conexion->prepare(
        "SELECT ID_EVENTO, ID_LUGAR, IMAGEN FROM EVENTO WHERE ID_EVENTO = ?"
    );
    $stmtExistente->bind_param("i", $eventoId);
    $stmtExistente->execute();
    $eventoExistente = $stmtExistente->get_result()->fetch_assoc();
    $stmtExistente->close();
    if (!$eventoExistente) {
        redirigirAdminEventos("error", "El evento ya no está disponible.");
    }

    if ($accion === "eliminar") {
        $stmtEliminar = $conexion->prepare("DELETE FROM EVENTO WHERE ID_EVENTO = ?");
        $stmtEliminar->bind_param("i", $eventoId);
        $stmtEliminar->execute();
        $eliminado = $stmtEliminar->affected_rows > 0;
        $stmtEliminar->close();

        if (!$eliminado) {
            redirigirAdminEventos("error", "No se pudo eliminar el evento.");
        }

        $rutaImagenAnterior = rutaPortadaAdminEvento($eventoExistente["IMAGEN"]);
        if ($rutaImagenAnterior !== null && is_file($rutaImagenAnterior) && !unlink($rutaImagenAnterior)) {
            error_log("No se pudo eliminar la portada del evento eliminado: " . $eventoId);
        }
        redirigirAdminEventos("exito", "El evento y sus interacciones fueron eliminados.");
    }

    $nombre = $_POST["nombre"] ?? null;
    $descripcion = $_POST["descripcion"] ?? null;
    $direccion = $_POST["direccion"] ?? null;
    $fecha = $_POST["fecha"] ?? null;
    $horaInicio = $_POST["hora_inicio"] ?? null;
    $horaFin = $_POST["hora_fin"] ?? null;
    $latitudRecibida = $_POST["latitud"] ?? null;
    $longitudRecibida = $_POST["longitud"] ?? null;
    if (
        !is_string($nombre)
        || !is_string($descripcion)
        || !is_string($direccion)
        || !is_string($fecha)
        || !is_string($horaInicio)
        || !is_string($horaFin)
        || !is_string($latitudRecibida)
        || !is_string($longitudRecibida)
    ) {
        redirigirAdminEventos("error", "Revisá los datos enviados.", $eventoId);
    }

    $nombre = trim($nombre);
    $descripcion = trim($descripcion);
    $direccion = trim($direccion);
    $fechaEvento = DateTime::createFromFormat("!Y-m-d", $fecha);
    $fechaValida = $fechaEvento && $fechaEvento->format("Y-m-d") === $fecha;
    $horaInicioValida = preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $horaInicio) === 1;
    $horaFinValida = preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $horaFin) === 1;
    $latitudRecibida = trim($latitudRecibida);
    $longitudRecibida = trim($longitudRecibida);
    $tieneLatitud = $latitudRecibida !== "";
    $tieneLongitud = $longitudRecibida !== "";
    $latitud = $tieneLatitud && is_numeric($latitudRecibida) ? (float) $latitudRecibida : null;
    $longitud = $tieneLongitud && is_numeric($longitudRecibida) ? (float) $longitudRecibida : null;
    $coordenadasValidas = $tieneLatitud
        && $tieneLongitud
        && $latitud !== null
        && $longitud !== null
        && $latitud >= -90
        && $latitud <= 90
        && $longitud >= -180
        && $longitud <= 180;
    $ubicacionValida = $direccion !== ""
        || $coordenadasValidas
        || $eventoExistente["ID_LUGAR"] !== null;

    if (
        $nombre === ""
        || mb_strlen($nombre, "UTF-8") > 150
        || mb_strlen($descripcion, "UTF-8") > 2000
        || mb_strlen($direccion, "UTF-8") > 255
        || (($tieneLatitud || $tieneLongitud) && !$coordenadasValidas)
        || !$ubicacionValida
        || !$fechaValida
        || !$horaInicioValida
        || !$horaFinValida
        || $horaFin <= $horaInicio
    ) {
        redirigirAdminEventos("error", "Revisá el título, la ubicación, la fecha y el horario del evento.", $eventoId);
    }

    $imagenEvento = $eventoExistente["IMAGEN"];
    $archivoImagen = $_FILES["imagen_portada"] ?? null;
    if (is_array($archivoImagen) && ($archivoImagen["error"] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
        if (
            !isset($archivoImagen["error"], $archivoImagen["size"], $archivoImagen["tmp_name"])
            || !is_string($archivoImagen["tmp_name"])
        ) {
            redirigirAdminEventos("error", "El archivo de portada no es válido.", $eventoId);
        }
        if ($archivoImagen["error"] !== UPLOAD_ERR_OK) {
            redirigirAdminEventos("error", "No se pudo recibir la imagen. Elegí otra e intentá nuevamente.", $eventoId);
        }
        if ($archivoImagen["size"] < 1 || $archivoImagen["size"] > 5 * 1024 * 1024) {
            redirigirAdminEventos("error", "La imagen debe pesar como máximo 5 MB.", $eventoId);
        }
        if (!is_uploaded_file($archivoImagen["tmp_name"])) {
            redirigirAdminEventos("error", "El archivo de portada no es válido.", $eventoId);
        }

        $informacionImagen = @getimagesize($archivoImagen["tmp_name"]);
        $detectorMime = new finfo(FILEINFO_MIME_TYPE);
        $mimeImagen = $detectorMime->file($archivoImagen["tmp_name"]);
        $mimesPermitidos = ["image/jpeg" => "jpg", "image/png" => "png", "image/webp" => "webp"];
        if (
            !$informacionImagen
            || !isset($mimesPermitidos[$mimeImagen])
            || $informacionImagen["mime"] !== $mimeImagen
        ) {
            redirigirAdminEventos("error", "La portada debe ser una imagen JPG, PNG o WebP.", $eventoId);
        }

        $carpetaImagenes = __DIR__ . "/../../assets/img/eventos";
        if (!is_dir($carpetaImagenes) && !mkdir($carpetaImagenes, 0755, true) && !is_dir($carpetaImagenes)) {
            error_log("No se pudo crear la carpeta de portadas administradas.");
            redirigirAdminEventos("error", "No se pudo guardar la imagen de portada.", $eventoId);
        }
        $nombreArchivo = bin2hex(random_bytes(16)) . "." . $mimesPermitidos[$mimeImagen];
        if (!move_uploaded_file($archivoImagen["tmp_name"], $carpetaImagenes . "/" . $nombreArchivo)) {
            error_log("No se pudo guardar la nueva portada del evento " . $eventoId);
            redirigirAdminEventos("error", "No se pudo guardar la imagen de portada.", $eventoId);
        }
        $imagenEvento = "assets/img/eventos/" . $nombreArchivo;
    } elseif (isset($_POST["quitar_portada"]) && $_POST["quitar_portada"] === "1") {
        $imagenEvento = null;
    }

    $dia = (int) $fechaEvento->format("d");
    $mes = (int) $fechaEvento->format("m");
    $anio = (int) $fechaEvento->format("Y");
    $stmtActualizar = $conexion->prepare(
        "UPDATE EVENTO
         SET NOMBRE = ?, DESCRIPCION = ?, DIRECCION = ?, LATITUD = ?, LONGITUD = ?,
             DIA = ?, MES = ?, ANIO = ?, HORA_INI = ?, HORA_FIN = ?, IMAGEN = ?
         WHERE ID_EVENTO = ?"
    );
    $stmtActualizar->bind_param(
        "sssddiiisssi",
        $nombre,
        $descripcion,
        $direccion,
        $latitud,
        $longitud,
        $dia,
        $mes,
        $anio,
        $horaInicio,
        $horaFin,
        $imagenEvento,
        $eventoId
    );

    try {
        $stmtActualizar->execute();
    } catch (mysqli_sql_exception $error) {
        if ($imagenEvento !== $eventoExistente["IMAGEN"]) {
            $rutaNueva = rutaPortadaAdminEvento($imagenEvento);
            if ($rutaNueva !== null && is_file($rutaNueva) && !unlink($rutaNueva)) {
                error_log("No se pudo limpiar una portada tras fallar la edición del evento " . $eventoId);
            }
        }
        error_log("No se pudo actualizar el evento " . $eventoId . ": " . $error->getMessage());
        redirigirAdminEventos("error", "No se pudieron guardar los cambios del evento.", $eventoId);
    }
    $stmtActualizar->close();

    if ($imagenEvento !== $eventoExistente["IMAGEN"]) {
        $rutaAnterior = rutaPortadaAdminEvento($eventoExistente["IMAGEN"]);
        if ($rutaAnterior !== null && is_file($rutaAnterior) && !unlink($rutaAnterior)) {
            error_log("No se pudo reemplazar la portada anterior del evento " . $eventoId);
        }
    }
    redirigirAdminEventos("exito", "Los cambios del evento fueron guardados.");
}

$flash = $_SESSION["flash_admin_eventos"] ?? null;
unset($_SESSION["flash_admin_eventos"]);
$idEditarRecibido = $_GET["editar"] ?? null;
$idEditar = is_string($idEditarRecibido) ? filter_var($idEditarRecibido, FILTER_VALIDATE_INT) : false;
if ($idEditarRecibido !== null && ($idEditar === false || $idEditar < 1)) {
    http_response_code(400);
    exit("El identificador del evento no es válido.");
}
$eventoEditar = null;
if ($idEditar !== false && $idEditar !== null) {
    $stmtEditar = $conexion->prepare(
        "SELECT e.*, l.DIRECCION AS LUGAR_DIRECCION
         FROM EVENTO e
         LEFT JOIN LUGAR l ON l.ID_LUGAR = e.ID_LUGAR
         WHERE e.ID_EVENTO = ?"
    );
    $stmtEditar->bind_param("i", $idEditar);
    $stmtEditar->execute();
    $eventoEditar = $stmtEditar->get_result()->fetch_assoc();
    $stmtEditar->close();
    if (!$eventoEditar) {
        $flash = ["tipo" => "error", "mensaje" => "El evento que querés editar ya no está disponible."];
    }
}

$resultadoEventos = $conexion->query(
    "SELECT e.ID_EVENTO, e.NOMBRE, e.DESCRIPCION, e.DIA, e.MES, e.ANIO,
            e.HORA_INI, e.HORA_FIN, e.DIRECCION, e.LATITUD, e.LONGITUD, e.IMAGEN,
            u.NOMBRE AS AUTOR, l.DIRECCION AS LUGAR_DIRECCION, l.IMAGEN AS LUGAR_IMAGEN,
            (SELECT COUNT(*) FROM EVENTO_COMENTARIO ec WHERE ec.ID_EVENTO = e.ID_EVENTO) AS COMENTARIOS,
            (SELECT COUNT(*) FROM EVENTO_REACCION er WHERE er.ID_EVENTO = e.ID_EVENTO) AS REACCIONES
     FROM EVENTO e
     LEFT JOIN USUARIO u ON u.ID_USUARIO = e.ID_USUARIO
     LEFT JOIN LUGAR l ON l.ID_LUGAR = e.ID_LUGAR
     ORDER BY e.ID_EVENTO DESC"
);
$eventos = $resultadoEventos->fetch_all(MYSQLI_ASSOC);
$cantidadEventos = count($eventos);
$eventoFecha = $eventoEditar
    ? sprintf("%04d-%02d-%02d", (int) ($eventoEditar["ANIO"] ?: date("Y")), (int) $eventoEditar["MES"], (int) $eventoEditar["DIA"])
    : "";
$eventoDireccion = $eventoEditar
    ? trim((string) ($eventoEditar["DIRECCION"] ?: $eventoEditar["LUGAR_DIRECCION"]))
    : "";
$imagenActual = $eventoEditar && !empty($eventoEditar["IMAGEN"])
    ? $eventoEditar["IMAGEN"]
    : ($eventoEditar["LUGAR_IMAGEN"] ?? "");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#163d30">
    <title>Administrar eventos | GoSalto</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="../../assets/css/admin-panel.css?v=2">
    <link rel="stylesheet" href="../../assets/css/admin-crud.css?v=3">
</head>
<body>
<div class="admin-crud">
    <header class="admin-crud-nav">
        <a class="admin-crud-marca" href="../../index.php"><span>G</span> GoSalto <small>Administración</small></a>
        <nav aria-label="Administración">
            <a href="panel.php">Panel</a>
            <a href="usuarios.php">Usuarios</a>
            <a href="lugares.php">Lugares</a>
            <a href="eventos.php" aria-current="page">Eventos</a>
            <a href="comentarios.php">Comentarios</a>
        </nav>
        <a class="admin-crud-salir" href="../logout.php">Cerrar sesión</a>
    </header>

    <main class="admin-crud-contenido">
        <div class="admin-crud-migas"><a href="panel.php">Panel</a><span>/</span><span>Eventos</span></div>
        <section class="admin-crud-encabezado">
            <div>
                <p class="admin-crud-etiqueta">Publicaciones de la comunidad</p>
                <h1>Administrar eventos</h1>
                <p>Revisá, editá o quitá eventos creados por las personas de GoSalto.</p>
            </div>
            <a class="admin-crud-boton-principal" href="../eventos/eventos.php"><span aria-hidden="true">↗</span> Ver el feed</a>
        </section>

        <?php if ($flash): ?>
            <div class="admin-crud-aviso admin-crud-aviso--<?= escaparAdminEvento($flash["tipo"]) ?>" role="<?= $flash["tipo"] === "error" ? "alert" : "status" ?>">
                <?= escaparAdminEvento($flash["mensaje"]) ?>
            </div>
        <?php endif; ?>

        <?php if ($eventoEditar): ?>
            <section class="admin-crud-form-card admin-evento-form-card" aria-labelledby="titulo-editar-evento">
                <div class="admin-crud-form-intro">
                    <span class="admin-crud-form-icono" aria-hidden="true">▤</span>
                    <div>
                        <h2 id="titulo-editar-evento">Editar evento #<?= (int) $eventoEditar["ID_EVENTO"] ?></h2>
                        <p>Actualizá la información que aparece en el feed.</p>
                    </div>
                </div>
                <form class="admin-crud-form" method="POST" enctype="multipart/form-data" action="eventos.php">
                    <input type="hidden" name="csrf_token" value="<?= escaparAdminEvento($_SESSION["csrf_admin_eventos"]) ?>">
                    <input type="hidden" name="accion" value="editar">
                    <input type="hidden" name="id_evento" value="<?= (int) $eventoEditar["ID_EVENTO"] ?>">
                    <div class="admin-crud-campos">
                        <div class="admin-crud-campo admin-crud-campo--ancho">
                            <label for="evento-nombre">Título <span>*</span></label>
                            <input id="evento-nombre" type="text" name="nombre" maxlength="150" value="<?= escaparAdminEvento($eventoEditar["NOMBRE"]) ?>" required>
                        </div>
                        <div class="admin-crud-campo admin-crud-campo--ancho">
                            <label for="evento-descripcion">Descripción</label>
                            <textarea id="evento-descripcion" name="descripcion" maxlength="2000"><?= escaparAdminEvento($eventoEditar["DESCRIPCION"] ?? "") ?></textarea>
                        </div>
                        <div class="admin-crud-campo admin-crud-campo--ancho">
                            <label for="evento-direccion">Dirección o referencia</label>
                            <input id="evento-direccion" type="text" name="direccion" maxlength="255" value="<?= escaparAdminEvento($eventoDireccion) ?>" placeholder="Ej.: Plaza Artigas, Salto">
                            <small>Podés dejarla vacía si el evento tiene coordenadas o está asociado a un lugar del catálogo.</small>
                        </div>
                        <div class="admin-crud-campo">
                            <label for="evento-latitud">Latitud</label>
                            <input id="evento-latitud" type="number" name="latitud" min="-90" max="90" step="0.0000001" value="<?= escaparAdminEvento($eventoEditar["LATITUD"] ?? "") ?>" placeholder="-31.3833000">
                        </div>
                        <div class="admin-crud-campo">
                            <label for="evento-longitud">Longitud</label>
                            <input id="evento-longitud" type="number" name="longitud" min="-180" max="180" step="0.0000001" value="<?= escaparAdminEvento($eventoEditar["LONGITUD"] ?? "") ?>" placeholder="-57.9667000">
                        </div>
                        <div class="admin-crud-campo">
                            <label for="evento-fecha">Fecha <span>*</span></label>
                            <input id="evento-fecha" type="date" name="fecha" value="<?= escaparAdminEvento($eventoFecha) ?>" required>
                        </div>
                        <div class="admin-crud-campo">
                            <label for="evento-hora-inicio">Desde <span>*</span></label>
                            <input id="evento-hora-inicio" type="time" name="hora_inicio" value="<?= escaparAdminEvento(substr((string) $eventoEditar["HORA_INI"], 0, 5)) ?>" required>
                        </div>
                        <div class="admin-crud-campo">
                            <label for="evento-hora-fin">Hasta <span>*</span></label>
                            <input id="evento-hora-fin" type="time" name="hora_fin" value="<?= escaparAdminEvento(substr((string) $eventoEditar["HORA_FIN"], 0, 5)) ?>" required>
                        </div>
                        <div class="admin-crud-campo admin-crud-campo--ancho">
                            <label for="evento-imagen">Reemplazar portada</label>
                            <input id="evento-imagen" type="file" name="imagen_portada" accept="image/jpeg,image/png,image/webp">
                            <small>JPG, PNG o WebP, hasta 5 MB. Dejá el campo vacío para conservar la portada actual.</small>
                            <?php if (!empty($eventoEditar["IMAGEN"])): ?>
                                <label class="admin-evento-quitar-portada">
                                    <input type="checkbox" name="quitar_portada" value="1">
                                    Quitar portada propia del evento
                                </label>
                            <?php elseif ($imagenActual !== ""): ?>
                                <small>La imagen actual se toma del lugar asociado y no se modificará.</small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="admin-crud-form-acciones">
                        <a class="admin-crud-boton-secundario" href="eventos.php">Cancelar</a>
                        <button class="admin-crud-boton-principal" type="submit">Guardar cambios</button>
                    </div>
                </form>
            </section>
        <?php endif; ?>

        <section class="admin-crud-tabla-card" aria-labelledby="titulo-listado">
            <div class="admin-crud-tabla-encabezado">
                <div>
                    <h2 id="titulo-listado">Eventos publicados</h2>
                    <p>Administrá los detalles y la actividad de cada publicación.</p>
                </div>
                <span class="admin-crud-total"><?= number_format($cantidadEventos, 0, ",", ".") ?> eventos</span>
            </div>
            <div class="admin-crud-tabla-scroll">
                <table class="admin-crud-tabla admin-eventos-tabla">
                    <thead>
                        <tr>
                            <th scope="col">Evento</th>
                            <th scope="col">Autor</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Actividad</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$eventos): ?>
                            <tr><td class="admin-crud-vacio" colspan="5">Todavía no hay eventos publicados.</td></tr>
                        <?php else: ?>
                            <?php foreach ($eventos as $evento): ?>
                                <?php
                                $eventoId = (int) $evento["ID_EVENTO"];
                                $portada = $evento["IMAGEN"] ?: ($evento["LUGAR_IMAGEN"] ?? "");
                                $fecha = sprintf("%02d/%02d%s", (int) $evento["DIA"], (int) $evento["MES"], $evento["ANIO"] ? "/" . (int) $evento["ANIO"] : "");
                                $ubicacion = trim((string) ($evento["DIRECCION"] ?: $evento["LUGAR_DIRECCION"]));
                                ?>
                                <tr id="evento-<?= $eventoId ?>">
                                    <td>
                                        <div class="admin-crud-lugar">
                                            <?php if ($portada !== ""): ?>
                                                <img src="../../<?= escaparAdminEvento(ltrim($portada, "/\\")) ?>" alt="" loading="lazy">
                                            <?php else: ?>
                                                <span class="admin-crud-sin-imagen" aria-hidden="true">▤</span>
                                            <?php endif; ?>
                                            <span><strong><?= escaparAdminEvento($evento["NOMBRE"]) ?></strong><small>ID #<?= $eventoId ?><?= $ubicacion !== "" ? " · " . escaparAdminEvento($ubicacion) : "" ?></small></span>
                                        </div>
                                    </td>
                                    <td><?= escaparAdminEvento($evento["AUTOR"] ?: "Usuario eliminado") ?></td>
                                    <td><?= escaparAdminEvento($fecha) ?><br><small><?= escaparAdminEvento(substr((string) $evento["HORA_INI"], 0, 5)) ?>–<?= escaparAdminEvento(substr((string) $evento["HORA_FIN"], 0, 5)) ?></small></td>
                                    <td><?= (int) $evento["REACCIONES"] ?> reacciones<br><small><?= (int) $evento["COMENTARIOS"] ?> comentarios</small></td>
                                    <td>
                                        <div class="admin-crud-acciones">
                                            <a class="admin-crud-accion editar" href="eventos.php?editar=<?= $eventoId ?>">Editar</a>
                                            <a class="admin-crud-accion" href="../eventos/eventos.php#evento-<?= $eventoId ?>">Ver</a>
                                            <form method="POST" action="eventos.php" onsubmit="return confirm('¿Seguro que querés eliminar este evento y sus comentarios y reacciones?')">
                                                <input type="hidden" name="csrf_token" value="<?= escaparAdminEvento($_SESSION["csrf_admin_eventos"]) ?>">
                                                <input type="hidden" name="accion" value="eliminar">
                                                <input type="hidden" name="id_evento" value="<?= $eventoId ?>">
                                                <button class="admin-crud-accion eliminar" type="submit">Eliminar</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <footer class="admin-crud-pie"><span>GoSalto <span aria-hidden="true">·</span> Panel de administración</span><a href="panel.php">Volver al panel <span aria-hidden="true">→</span></a></footer>
    </main>
</div>
<?php include("../../includes/chat-widget.php"); ?>
</body>
</html>
