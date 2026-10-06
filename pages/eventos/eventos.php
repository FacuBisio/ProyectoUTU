<?php
session_start();
require_once("../../conexion.php");
require_once("../../config/config.php");

$usuarioId = isset($_SESSION["id_usuario"]) ? (int) $_SESSION["id_usuario"] : null;
$estaAutenticado = $usuarioId !== null;

if (empty($_SESSION["csrf_eventos"])) {
    $_SESSION["csrf_eventos"] = bin2hex(random_bytes(32));
}

function escaparEvento($valor)
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, "UTF-8");
}

function volverAEventos($mensaje, $tipo = "error", $eventoId = null)
{
    $_SESSION["flash_eventos"] = ["mensaje" => $mensaje, "tipo" => $tipo];
    $fragmento = $eventoId ? "#evento-" . (int) $eventoId : "#publicar";
    header("Location: eventos.php" . $fragmento);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!$estaAutenticado) {
        volverAEventos("Iniciá sesión para participar de la comunidad.");
    }

    $token = $_POST["csrf_token"] ?? "";
    if (!is_string($token) || !hash_equals($_SESSION["csrf_eventos"], $token)) {
        volverAEventos("La sesión del formulario venció. Actualizá la página e intentá nuevamente.");
    }

    $accion = $_POST["accion"] ?? "";
    if ($accion === "reaccionar") {
        $eventoId = filter_var($_POST["id_evento"] ?? null, FILTER_VALIDATE_INT);
        $tipo = $_POST["tipo"] ?? "";
        if (!$eventoId || !in_array($tipo, ["1", "-1"], true)) {
            volverAEventos("La reacción enviada no es válida.");
        }

        $stmtExiste = $conexion->prepare("SELECT ID_EVENTO FROM EVENTO WHERE ID_EVENTO = ?");
        $stmtExiste->bind_param("i", $eventoId);
        $stmtExiste->execute();
        $existe = $stmtExiste->get_result()->num_rows > 0;
        $stmtExiste->close();
        if (!$existe) {
            volverAEventos("La publicación ya no está disponible.");
        }

        $tipo = (int) $tipo;
        $stmtReaccion = $conexion->prepare(
            "SELECT TIPO FROM EVENTO_REACCION WHERE ID_EVENTO = ? AND ID_USUARIO = ?"
        );
        $stmtReaccion->bind_param("ii", $eventoId, $usuarioId);
        $stmtReaccion->execute();
        $reaccionActual = $stmtReaccion->get_result()->fetch_assoc();
        $stmtReaccion->close();

        if ($reaccionActual && (int) $reaccionActual["TIPO"] === $tipo) {
            $stmtCambio = $conexion->prepare(
                "DELETE FROM EVENTO_REACCION WHERE ID_EVENTO = ? AND ID_USUARIO = ?"
            );
            $stmtCambio->bind_param("ii", $eventoId, $usuarioId);
        } else {
            $stmtCambio = $conexion->prepare(
                "INSERT INTO EVENTO_REACCION (ID_EVENTO, ID_USUARIO, TIPO)
                 VALUES (?, ?, ?)
                 ON DUPLICATE KEY UPDATE TIPO = VALUES(TIPO), FECHA = CURRENT_TIMESTAMP"
            );
            $stmtCambio->bind_param("iii", $eventoId, $usuarioId, $tipo);
        }
        $stmtCambio->execute();
        $stmtCambio->close();
        volverAEventos("Tu reacción quedó actualizada.", "exito", $eventoId);
    }

    if ($accion === "comentar") {
        $eventoId = filter_var($_POST["id_evento"] ?? null, FILTER_VALIDATE_INT);
        $comentario = $_POST["comentario"] ?? "";
        if (!$eventoId || !is_string($comentario)) {
            volverAEventos("El comentario enviado no es válido.");
        }
        $comentario = trim($comentario);
        if ($comentario === "" || mb_strlen($comentario, "UTF-8") > 1000) {
            volverAEventos("El comentario debe tener entre 1 y 1000 caracteres.", "error", $eventoId);
        }

        $stmtComentario = $conexion->prepare(
            "INSERT INTO EVENTO_COMENTARIO (ID_EVENTO, ID_USUARIO, COMENTARIO) VALUES (?, ?, ?)"
        );
        $stmtComentario->bind_param("iis", $eventoId, $usuarioId, $comentario);
        try {
            $stmtComentario->execute();
        } catch (mysqli_sql_exception $error) {
            error_log("No se pudo guardar el comentario del evento: " . $error->getMessage());
            volverAEventos("No se pudo publicar el comentario. Intentá nuevamente.");
        }
        $stmtComentario->close();
        volverAEventos("Tu comentario se publicó correctamente.", "exito", $eventoId);
    }

    if ($accion === "publicar") {
        $nombre = $_POST["nombre"] ?? "";
        $descripcion = $_POST["descripcion"] ?? "";
        $direccion = $_POST["direccion"] ?? "";
        $latitudRecibida = $_POST["latitud"] ?? "";
        $longitudRecibida = $_POST["longitud"] ?? "";
        $fecha = $_POST["fecha"] ?? "";
        $horaInicio = $_POST["hora_inicio"] ?? "";
        $horaFin = $_POST["hora_fin"] ?? "";

        if (
            !is_string($nombre)
            || !is_string($descripcion)
            || !is_string($direccion)
            || !is_string($latitudRecibida)
            || !is_string($longitudRecibida)
            || !is_string($fecha)
            || !is_string($horaInicio)
            || !is_string($horaFin)
        ) {
            volverAEventos("Revisá los datos de la publicación.");
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
        $ubicacionValida = $direccion !== "" || $coordenadasValidas;

        if (
            $nombre === ""
            || mb_strlen($nombre, "UTF-8") > 150
            || mb_strlen($descripcion, "UTF-8") > 2000
            || mb_strlen($direccion, "UTF-8") > 255
            || (($tieneLatitud || $tieneLongitud) && !$coordenadasValidas)
            || !$ubicacionValida
            || !$fechaValida
            || $fechaEvento->format("Y-m-d") < date("Y-m-d")
            || !$horaInicioValida
            || !$horaFinValida
            || $horaFin <= $horaInicio
        ) {
            volverAEventos("Completá el título, una dirección o ubicación marcada en el mapa, la fecha y un horario válido. La descripción admite hasta 2000 caracteres.");
        }

        $imagenEvento = null;
        $archivoImagen = $_FILES["imagen_portada"] ?? null;
        if (is_array($archivoImagen) && ($archivoImagen["error"] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            if (
                !isset($archivoImagen["error"], $archivoImagen["size"], $archivoImagen["tmp_name"])
                || !is_string($archivoImagen["tmp_name"])
            ) {
                volverAEventos("El archivo de portada no es válido.");
            }
            if ($archivoImagen["error"] !== UPLOAD_ERR_OK) {
                volverAEventos("No se pudo recibir la imagen. Elegí otra e intentá nuevamente.");
            }
            if ($archivoImagen["size"] < 1 || $archivoImagen["size"] > 5 * 1024 * 1024) {
                volverAEventos("La imagen debe pesar como máximo 5 MB.");
            }
            if (!is_uploaded_file($archivoImagen["tmp_name"])) {
                volverAEventos("El archivo de portada no es válido.");
            }

            $informacionImagen = @getimagesize($archivoImagen["tmp_name"]);
            $detectorMime = new finfo(FILEINFO_MIME_TYPE);
            $mimeImagen = $detectorMime->file($archivoImagen["tmp_name"]);
            $mimesPermitidos = [
                "image/jpeg" => "jpg",
                "image/png" => "png",
                "image/webp" => "webp",
            ];
            if (
                !$informacionImagen
                || !isset($mimesPermitidos[$mimeImagen])
                || $informacionImagen["mime"] !== $mimeImagen
            ) {
                volverAEventos("La portada debe ser una imagen JPG, PNG o WebP.");
            }

            $carpetaImagenes = __DIR__ . "/../../assets/img/eventos";
            if (!is_dir($carpetaImagenes) && !mkdir($carpetaImagenes, 0755, true) && !is_dir($carpetaImagenes)) {
                error_log("No se pudo crear la carpeta de portadas de eventos.");
                volverAEventos("No se pudo guardar la imagen de portada. Intentá nuevamente.");
            }
            $nombreArchivo = bin2hex(random_bytes(16)) . "." . $mimesPermitidos[$mimeImagen];
            if (!move_uploaded_file($archivoImagen["tmp_name"], $carpetaImagenes . "/" . $nombreArchivo)) {
                error_log("No se pudo mover una imagen cargada para un evento.");
                volverAEventos("No se pudo guardar la imagen de portada. Intentá nuevamente.");
            }
            $imagenEvento = "assets/img/eventos/" . $nombreArchivo;
        }

        $dia = (int) $fechaEvento->format("d");
        $mes = (int) $fechaEvento->format("m");
        $anio = (int) $fechaEvento->format("Y");
        $stmtPublicacion = $conexion->prepare(
            "INSERT INTO EVENTO
                (ID_USUARIO, ID_LUGAR, NOMBRE, DESCRIPCION, HORA_INI, HORA_FIN, DIA, MES, ANIO, DIRECCION, LATITUD, LONGITUD, IMAGEN)
             VALUES (?, NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmtPublicacion->bind_param(
            "issssiiisdds",
            $usuarioId,
            $nombre,
            $descripcion,
            $horaInicio,
            $horaFin,
            $dia,
            $mes,
            $anio,
            $direccion,
            $latitud,
            $longitud,
            $imagenEvento
        );

        try {
            $stmtPublicacion->execute();
            $eventoNuevo = $conexion->insert_id;
        } catch (mysqli_sql_exception $error) {
            if ($imagenEvento !== null) {
                unlink(__DIR__ . "/../../" . $imagenEvento);
            }
            error_log("No se pudo publicar el evento: " . $error->getMessage());
            volverAEventos("No se pudo publicar el evento. Intentá nuevamente.");
        }
        $stmtPublicacion->close();
        volverAEventos("¡Tu publicación ya está en el feed!", "exito", $eventoNuevo);
    }

    volverAEventos("La acción solicitada no es válida.");
}

$flash = $_SESSION["flash_eventos"] ?? null;
unset($_SESSION["flash_eventos"]);

$usuarioActualSql = $estaAutenticado ? (string) $usuarioId : "0";
$sqlEventos = "
    SELECT e.ID_EVENTO, e.ID_USUARIO, e.ID_LUGAR, e.NOMBRE, e.DESCRIPCION,
           e.HORA_INI, e.HORA_FIN, e.DIA, e.MES, e.ANIO,
           e.DIRECCION AS EVENTO_DIRECCION, e.LATITUD AS EVENTO_LATITUD,
           e.LONGITUD AS EVENTO_LONGITUD, e.IMAGEN AS EVENTO_IMAGEN,
           u.NOMBRE AS AUTOR, l.NOMBRE AS LUGAR, l.DIRECCION AS LUGAR_DIRECCION,
           l.IMAGEN AS LUGAR_IMAGEN,
           (SELECT COUNT(*) FROM EVENTO_REACCION r WHERE r.ID_EVENTO = e.ID_EVENTO AND r.TIPO = 1) AS ME_GUSTA,
           (SELECT COUNT(*) FROM EVENTO_REACCION r WHERE r.ID_EVENTO = e.ID_EVENTO AND r.TIPO = -1) AS NO_ME_GUSTA,
           (SELECT r.TIPO FROM EVENTO_REACCION r WHERE r.ID_EVENTO = e.ID_EVENTO AND r.ID_USUARIO = $usuarioActualSql LIMIT 1) AS MI_REACCION
    FROM EVENTO e
    INNER JOIN USUARIO u ON u.ID_USUARIO = e.ID_USUARIO
    LEFT JOIN LUGAR l ON l.ID_LUGAR = e.ID_LUGAR
    ORDER BY e.ID_EVENTO DESC";
$resultadoEventos = $conexion->query($sqlEventos);
$eventos = [];
$idsEventos = [];
while ($evento = $resultadoEventos->fetch_assoc()) {
    $eventos[] = $evento;
    $idsEventos[] = (int) $evento["ID_EVENTO"];
}

$comentariosPorEvento = [];
if ($idsEventos) {
    $idsSql = implode(",", $idsEventos);
    $resultadoComentarios = $conexion->query(
        "SELECT c.ID_EVENTO, c.COMENTARIO, c.FECHA, u.NOMBRE AS AUTOR
         FROM EVENTO_COMENTARIO c
         INNER JOIN USUARIO u ON u.ID_USUARIO = c.ID_USUARIO
         WHERE c.ID_EVENTO IN ($idsSql)
         ORDER BY c.FECHA ASC, c.ID_COMENTARIO ASC"
    );
    while ($comentario = $resultadoComentarios->fetch_assoc()) {
        $comentariosPorEvento[(int) $comentario["ID_EVENTO"]][] = $comentario;
    }
}

$fechaMinima = date("Y-m-d");
$fechaPredeterminada = date("Y-m-d", strtotime("+1 day"));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#123c30">
    <title>Eventos y comunidad | GoSalto</title>
    <link rel="stylesheet" href="../../assets/css/var.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/componentes.css">
    <link rel="stylesheet" href="../../assets/css/accesibilidad.css?v=5">
    <link rel="stylesheet" href="../../assets/css/eventos.css?v=5">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
    <?php include("../../includes/navbar.php"); ?>

    <main class="eventos-pagina">
        <header class="eventos-portada">
            <div class="eventos-portada-contenido">
                <span class="eventos-etiqueta"><i class="fa-solid fa-sparkles" aria-hidden="true"></i> La ciudad se encuentra acá</span>
                <h1>Lo que pasa en Salto, <span>se comparte.</span></h1>
                <p>Descubrí planes, compartí tus eventos y conectá con quienes también disfrutan la ciudad.</p>
                <a href="#publicaciones" class="eventos-bajar">Explorar publicaciones <i class="fa-solid fa-arrow-down" aria-hidden="true"></i></a>
            </div>
            <div class="eventos-portada-decoracion" aria-hidden="true"><i class="fa-solid fa-location-dot"></i></div>
        </header>

        <div class="eventos-layout">
            <aside class="eventos-lateral">
                <div class="eventos-lateral-tarjeta">
                    <span class="eventos-lateral-icono"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></span>
                    <h2>La agenda de Salto</h2>
                    <p>Planes, encuentros y actividades para vivir la ciudad con otros ojos.</p>
                    <a href="#publicar">Compartí un evento <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
                </div>
                <div class="eventos-lateral-tarjeta eventos-lateral-tarjeta--suave">
                    <strong><?= count($eventos) ?></strong>
                    <span>publicaciones en la comunidad</span>
                </div>
            </aside>

            <section class="eventos-feed" id="publicaciones" aria-label="Publicaciones de eventos">
                <?php if ($flash): ?>
                    <div class="eventos-aviso eventos-aviso--<?= escaparEvento($flash["tipo"]) ?>" role="<?= $flash["tipo"] === "error" ? "alert" : "status" ?>">
                        <?= escaparEvento($flash["mensaje"]) ?>
                    </div>
                <?php endif; ?>

                <section class="evento-compositor" id="publicar" aria-labelledby="titulo-publicar">
                    <div class="evento-compositor-encabezado">
                        <span class="evento-avatar" aria-hidden="true">
                            <?= $estaAutenticado ? escaparEvento(mb_strtoupper(mb_substr($_SESSION["nombre"], 0, 1, "UTF-8"), "UTF-8")) : '<i class="fa-regular fa-user"></i>' ?>
                        </span>
                        <div>
                            <h2 id="titulo-publicar"><?= $estaAutenticado ? "¿Qué plan tenés para Salto?" : "Sumate a la comunidad" ?></h2>
                            <p><?= $estaAutenticado ? "Publicá un evento para que todos puedan descubrirlo." : "Iniciá sesión para publicar, reaccionar y comentar." ?></p>
                        </div>
                    </div>
                    <?php if ($estaAutenticado): ?>
                        <form class="evento-formulario" method="POST" enctype="multipart/form-data" action="eventos.php#publicar">
                            <input type="hidden" name="csrf_token" value="<?= escaparEvento($_SESSION["csrf_eventos"]) ?>">
                            <input type="hidden" name="accion" value="publicar">

                            <label for="evento-nombre">Título del evento</label>
                            <input id="evento-nombre" type="text" name="nombre" maxlength="150" placeholder="Ej.: Música en la costanera" required>

                            <label for="evento-descripcion">Contale a la comunidad <span>opcional</span></label>
                            <textarea id="evento-descripcion" name="descripcion" rows="3" maxlength="2000" placeholder="¿Qué va a pasar? ¿Por qué no hay que perdérselo?"></textarea>

                            <fieldset class="evento-ubicacion">
                                <legend>¿Dónde se realiza? <span>Marcá el mapa o escribí la dirección</span></legend>
                                <label for="evento-direccion">Dirección o referencia <span>opcional si marcás el mapa</span></label>
                                <input id="evento-direccion" type="text" name="direccion" maxlength="255" placeholder="Ej.: Plaza Artigas, Salto">
                                <div class="evento-mapa-ayuda"><i class="fa-solid fa-hand-pointer" aria-hidden="true"></i> Tocá el mapa para marcar el punto exacto del evento.</div>
                                <div id="mapaEvento" class="evento-mapa" aria-label="Elegí la ubicación del evento en el mapa"></div>
                                <input id="evento-latitud" type="hidden" name="latitud">
                                <input id="evento-longitud" type="hidden" name="longitud">
                                <div class="evento-mapa-estado">
                                    <span id="evento-mapa-estado" role="status">Podés marcar el mapa, escribir una dirección o completar ambas opciones.</span>
                                    <button type="button" id="limpiar-ubicacion-evento" hidden>Quitar marcador</button>
                                </div>
                            </fieldset>

                            <div class="evento-portada-campo">
                                <label for="evento-imagen">Imagen de portada <span>opcional · JPG, PNG o WebP · hasta 5 MB</span></label>
                                <input id="evento-imagen" type="file" name="imagen_portada" accept="image/jpeg,image/png,image/webp">
                                <div id="evento-imagen-preview" class="evento-imagen-preview" hidden>
                                    <img id="evento-imagen-preview-img" alt="Vista previa de la portada">
                                    <button type="button" id="quitar-imagen-evento" aria-label="Quitar imagen seleccionada"><i class="fa-solid fa-xmark" aria-hidden="true"></i></button>
                                </div>
                            </div>

                            <div class="evento-formulario-fila">
                                <div>
                                    <label for="evento-fecha">Fecha</label>
                                    <input id="evento-fecha" type="date" name="fecha" min="<?= escaparEvento($fechaMinima) ?>" value="<?= escaparEvento($fechaPredeterminada) ?>" required>
                                </div>
                            </div>

                            <div class="evento-formulario-fila evento-formulario-fila--horas">
                                <div>
                                    <label for="evento-hora-inicio">Desde</label>
                                    <input id="evento-hora-inicio" type="time" name="hora_inicio" value="18:00" required>
                                </div>
                                <div>
                                    <label for="evento-hora-fin">Hasta</label>
                                    <input id="evento-hora-fin" type="time" name="hora_fin" value="20:00" required>
                                </div>
                            </div>

                            <button class="evento-publicar" type="submit">Publicar evento <i class="fa-solid fa-paper-plane" aria-hidden="true"></i></button>
                        </form>
                    <?php else: ?>
                        <div class="evento-invitado-acciones">
                            <a href="../login.php">Iniciar sesión</a>
                            <a href="../register.php">Crear cuenta</a>
                        </div>
                    <?php endif; ?>
                </section>

                <?php if (!$eventos): ?>
                    <div class="eventos-vacio">
                        <span><i class="fa-regular fa-calendar-plus" aria-hidden="true"></i></span>
                        <h2>El feed está esperando su primer plan</h2>
                        <p>Cuando alguien comparta un evento, lo vas a encontrar acá.</p>
                    </div>
                <?php endif; ?>

                <?php foreach ($eventos as $evento): ?>
                    <?php
                    $eventoId = (int) $evento["ID_EVENTO"];
                    $direccionEvento = trim((string) ($evento["EVENTO_DIRECCION"] ?? ""));
                    if ($direccionEvento === "") {
                        $direccionEvento = trim((string) ($evento["LUGAR_DIRECCION"] ?? ""));
                    }
                    $nombreLugar = trim((string) ($evento["LUGAR"] ?? ""));
                    $latitudEvento = $evento["EVENTO_LATITUD"];
                    $longitudEvento = $evento["EVENTO_LONGITUD"];
                    $tieneCoordenadasEvento = is_numeric($latitudEvento) && is_numeric($longitudEvento);
                    $textoUbicacionEvento = $direccionEvento !== ""
                        ? $direccionEvento
                        : ($tieneCoordenadasEvento ? "Ubicación marcada en el mapa" : ($nombreLugar !== "" ? $nombreLugar : "Ubicación a confirmar"));
                    $imagen = trim((string) ($evento["EVENTO_IMAGEN"] ?? ""));
                    if ($imagen === "") {
                        $imagen = trim((string) ($evento["LUGAR_IMAGEN"] ?? ""));
                    }
                    $rutaImagen = $imagen !== "" ? __DIR__ . "/../../" . ltrim($imagen, "/\\") : "";
                    $imagenSrc = $imagen !== "" && is_file($rutaImagen)
                        ? "../../" . ltrim($imagen, "/\\")
                        : "../../assets/img/imagen-principal.jpeg";
                    $fechaTexto = sprintf(
                        "%02d/%02d%s",
                        (int) $evento["DIA"],
                        (int) $evento["MES"],
                        $evento["ANIO"] ? "/" . (int) $evento["ANIO"] : ""
                    );
                    $miReaccion = $evento["MI_REACCION"] === null ? 0 : (int) $evento["MI_REACCION"];
                    ?>
                    <article class="evento-post" id="evento-<?= $eventoId ?>">
                        <header class="evento-post-encabezado">
                            <span class="evento-avatar" aria-hidden="true"><?= escaparEvento(mb_strtoupper(mb_substr($evento["AUTOR"], 0, 1, "UTF-8"), "UTF-8")) ?></span>
                            <div class="evento-post-autor">
                                <strong><?= escaparEvento($evento["AUTOR"]) ?></strong>
                                <span><i class="fa-solid fa-location-dot" aria-hidden="true"></i> <?= escaparEvento($textoUbicacionEvento) ?></span>
                            </div>
                            <span class="evento-fecha-publicacion"><i class="fa-regular fa-calendar" aria-hidden="true"></i> <?= escaparEvento($fechaTexto) ?></span>
                        </header>

                        <?php if ($imagenSrc !== ""): ?>
                            <div class="evento-post-imagen">
                                <img src="<?= escaparEvento($imagenSrc) ?>" alt="Portada de <?= escaparEvento($evento["NOMBRE"]) ?>" loading="lazy">
                                <span><i class="fa-regular fa-clock" aria-hidden="true"></i> <?= escaparEvento(substr((string) $evento["HORA_INI"], 0, 5)) ?>–<?= escaparEvento(substr((string) $evento["HORA_FIN"], 0, 5)) ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="evento-post-contenido">
                            <h2><?= escaparEvento($evento["NOMBRE"]) ?></h2>
                            <?php if (!empty($evento["DESCRIPCION"])): ?>
                                <p class="evento-post-descripcion"><?= nl2br(escaparEvento($evento["DESCRIPCION"])) ?></p>
                            <?php endif; ?>
                            <?php if ($tieneCoordenadasEvento): ?>
                                <?php $urlMapaEvento = "https://www.google.com/maps/search/?api=1&query=" . rawurlencode($latitudEvento . "," . $longitudEvento); ?>
                            <?php elseif ($direccionEvento !== ""): ?>
                                <?php $urlMapaEvento = "https://www.google.com/maps/search/?api=1&query=" . rawurlencode($direccionEvento . ", Salto, Uruguay"); ?>
                            <?php else: ?>
                                <?php $urlMapaEvento = ""; ?>
                            <?php endif; ?>
                            <p class="evento-post-direccion">
                                <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                                <?php if ($urlMapaEvento !== ""): ?>
                                    <a href="<?= escaparEvento($urlMapaEvento) ?>" target="_blank" rel="noopener noreferrer"><?= escaparEvento($textoUbicacionEvento) ?> <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i></a>
                                <?php else: ?>
                                    <?= escaparEvento($textoUbicacionEvento) ?>
                                <?php endif; ?>
                            </p>
                        </div>

                        <div class="evento-reacciones">
                            <?php if ($estaAutenticado): ?>
                                <form method="POST" action="eventos.php#evento-<?= $eventoId ?>">
                                    <input type="hidden" name="csrf_token" value="<?= escaparEvento($_SESSION["csrf_eventos"]) ?>">
                                    <input type="hidden" name="accion" value="reaccionar">
                                    <input type="hidden" name="id_evento" value="<?= $eventoId ?>">
                                    <input type="hidden" name="tipo" value="1">
                                    <button class="evento-reaccion <?= $miReaccion === 1 ? "evento-reaccion--activa" : "" ?>" type="submit" aria-label="Me gusta, <?= (int) $evento["ME_GUSTA"] ?> reacciones" aria-pressed="<?= $miReaccion === 1 ? "true" : "false" ?>">
                                        <i class="fa-regular fa-heart" aria-hidden="true"></i><span><?= (int) $evento["ME_GUSTA"] ?></span>
                                    </button>
                                </form>
                                <form method="POST" action="eventos.php#evento-<?= $eventoId ?>">
                                    <input type="hidden" name="csrf_token" value="<?= escaparEvento($_SESSION["csrf_eventos"]) ?>">
                                    <input type="hidden" name="accion" value="reaccionar">
                                    <input type="hidden" name="id_evento" value="<?= $eventoId ?>">
                                    <input type="hidden" name="tipo" value="-1">
                                    <button class="evento-reaccion <?= $miReaccion === -1 ? "evento-reaccion--activa evento-reaccion--negativa" : "" ?>" type="submit" aria-label="No me gusta, <?= (int) $evento["NO_ME_GUSTA"] ?> reacciones" aria-pressed="<?= $miReaccion === -1 ? "true" : "false" ?>">
                                        <i class="fa-regular fa-face-frown" aria-hidden="true"></i><span><?= (int) $evento["NO_ME_GUSTA"] ?></span>
                                    </button>
                                </form>
                            <?php else: ?>
                                <span class="evento-reaccion evento-reaccion--solo"><i class="fa-regular fa-heart" aria-hidden="true"></i> <?= (int) $evento["ME_GUSTA"] ?></span>
                                <span class="evento-reaccion evento-reaccion--solo"><i class="fa-regular fa-face-frown" aria-hidden="true"></i> <?= (int) $evento["NO_ME_GUSTA"] ?></span>
                            <?php endif; ?>
                            <span class="evento-conteo-comentarios"><i class="fa-regular fa-comment" aria-hidden="true"></i> <?= count($comentariosPorEvento[$eventoId] ?? []) ?> comentarios</span>
                        </div>

                        <section class="evento-comentarios" aria-label="Comentarios de <?= escaparEvento($evento["NOMBRE"]) ?>">
                            <?php foreach (($comentariosPorEvento[$eventoId] ?? []) as $comentario): ?>
                                <p class="evento-comentario"><strong><?= escaparEvento($comentario["AUTOR"]) ?></strong> <?= nl2br(escaparEvento($comentario["COMENTARIO"])) ?></p>
                            <?php endforeach; ?>

                            <?php if ($estaAutenticado): ?>
                                <form class="evento-comentario-formulario" method="POST" action="eventos.php#evento-<?= $eventoId ?>">
                                    <input type="hidden" name="csrf_token" value="<?= escaparEvento($_SESSION["csrf_eventos"]) ?>">
                                    <input type="hidden" name="accion" value="comentar">
                                    <input type="hidden" name="id_evento" value="<?= $eventoId ?>">
                                    <label class="sr-only" for="comentario-evento-<?= $eventoId ?>">Escribí un comentario</label>
                                    <input id="comentario-evento-<?= $eventoId ?>" type="text" name="comentario" maxlength="1000" placeholder="Sumá algo a la conversación..." required>
                                    <button type="submit" aria-label="Publicar comentario"><i class="fa-solid fa-arrow-up" aria-hidden="true"></i></button>
                                </form>
                            <?php else: ?>
                                <p class="evento-comentario-login"><a href="../login.php">Iniciá sesión</a> para comentar esta publicación.</p>
                            <?php endif; ?>
                        </section>
                    </article>
                <?php endforeach; ?>
            </section>

            <aside class="eventos-lateral eventos-lateral--derecha">
                <div class="eventos-lateral-tarjeta">
                    <h2>Una comunidad que se mueve</h2>
                    <p>Encontrá una idea para hoy, descubrí un nuevo rincón o invitá a otros a tu próximo plan.</p>
                    <a href="#publicar">Compartir un plan <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
                </div>
            </aside>
        </div>
    </main>

    <?php include("../../includes/footer.php"); ?>
    <?php include("../../includes/chat-widget.php"); ?>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="../../assets/js/evento-formulario.js?v=1" defer></script>
</body>
</html>
