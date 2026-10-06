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
        $lugarId = filter_var($_POST["id_lugar"] ?? null, FILTER_VALIDATE_INT);
        $fecha = $_POST["fecha"] ?? "";
        $horaInicio = $_POST["hora_inicio"] ?? "";
        $horaFin = $_POST["hora_fin"] ?? "";

        if (
            !is_string($nombre)
            || !is_string($descripcion)
            || !is_string($fecha)
            || !is_string($horaInicio)
            || !is_string($horaFin)
        ) {
            volverAEventos("Revisá los datos de la publicación.");
        }

        $nombre = trim($nombre);
        $descripcion = trim($descripcion);
        $fechaEvento = DateTime::createFromFormat("!Y-m-d", $fecha);
        $fechaValida = $fechaEvento && $fechaEvento->format("Y-m-d") === $fecha;
        $horaInicioValida = preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $horaInicio) === 1;
        $horaFinValida = preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $horaFin) === 1;

        if (
            $nombre === ""
            || mb_strlen($nombre, "UTF-8") > 150
            || mb_strlen($descripcion, "UTF-8") > 2000
            || !$lugarId
            || !$fechaValida
            || $fechaEvento->format("Y-m-d") < date("Y-m-d")
            || !$horaInicioValida
            || !$horaFinValida
            || $horaFin <= $horaInicio
        ) {
            volverAEventos("Completá el título, lugar, fecha y horario. La descripción puede tener hasta 2000 caracteres y el horario de fin debe ser posterior al de inicio.");
        }

        $stmtLugar = $conexion->prepare("SELECT ID_LUGAR FROM LUGAR WHERE ID_LUGAR = ?");
        $stmtLugar->bind_param("i", $lugarId);
        $stmtLugar->execute();
        $lugarExiste = $stmtLugar->get_result()->num_rows > 0;
        $stmtLugar->close();
        if (!$lugarExiste) {
            volverAEventos("Elegí un lugar válido para el evento.");
        }

        $dia = (int) $fechaEvento->format("d");
        $mes = (int) $fechaEvento->format("m");
        $anio = (int) $fechaEvento->format("Y");
        $stmtPublicacion = $conexion->prepare(
            "INSERT INTO EVENTO (ID_USUARIO, ID_LUGAR, NOMBRE, DESCRIPCION, HORA_INI, HORA_FIN, DIA, MES, ANIO)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmtPublicacion->bind_param(
            "iissssiii",
            $usuarioId,
            $lugarId,
            $nombre,
            $descripcion,
            $horaInicio,
            $horaFin,
            $dia,
            $mes,
            $anio
        );

        try {
            $stmtPublicacion->execute();
            $eventoNuevo = $conexion->insert_id;
        } catch (mysqli_sql_exception $error) {
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

$lugares = [];
$resultadoLugares = $conexion->query("SELECT ID_LUGAR, NOMBRE, DIRECCION FROM LUGAR ORDER BY NOMBRE");
while ($lugar = $resultadoLugares->fetch_assoc()) {
    $lugares[] = $lugar;
}

$usuarioActualSql = $estaAutenticado ? (string) $usuarioId : "0";
$sqlEventos = "
    SELECT e.ID_EVENTO, e.ID_USUARIO, e.ID_LUGAR, e.NOMBRE, e.DESCRIPCION,
           e.HORA_INI, e.HORA_FIN, e.DIA, e.MES, e.ANIO,
           u.NOMBRE AS AUTOR, l.NOMBRE AS LUGAR, l.DIRECCION, l.IMAGEN,
           (SELECT COUNT(*) FROM EVENTO_REACCION r WHERE r.ID_EVENTO = e.ID_EVENTO AND r.TIPO = 1) AS ME_GUSTA,
           (SELECT COUNT(*) FROM EVENTO_REACCION r WHERE r.ID_EVENTO = e.ID_EVENTO AND r.TIPO = -1) AS NO_ME_GUSTA,
           (SELECT r.TIPO FROM EVENTO_REACCION r WHERE r.ID_EVENTO = e.ID_EVENTO AND r.ID_USUARIO = $usuarioActualSql LIMIT 1) AS MI_REACCION
    FROM EVENTO e
    INNER JOIN USUARIO u ON u.ID_USUARIO = e.ID_USUARIO
    INNER JOIN LUGAR l ON l.ID_LUGAR = e.ID_LUGAR
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
    <link rel="stylesheet" href="../../assets/css/eventos.css?v=4">
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
                        <form class="evento-formulario" method="POST" action="eventos.php#publicar">
                            <input type="hidden" name="csrf_token" value="<?= escaparEvento($_SESSION["csrf_eventos"]) ?>">
                            <input type="hidden" name="accion" value="publicar">

                            <label for="evento-nombre">Título del evento</label>
                            <input id="evento-nombre" type="text" name="nombre" maxlength="150" placeholder="Ej.: Música en la costanera" required>

                            <label for="evento-descripcion">Contale a la comunidad <span>opcional</span></label>
                            <textarea id="evento-descripcion" name="descripcion" rows="3" maxlength="2000" placeholder="¿Qué va a pasar? ¿Por qué no hay que perdérselo?"></textarea>

                            <div class="evento-formulario-fila">
                                <div>
                                    <label for="evento-lugar">Lugar</label>
                                    <select id="evento-lugar" name="id_lugar" required>
                                        <option value="">Elegí dónde</option>
                                        <?php foreach ($lugares as $lugar): ?>
                                            <option value="<?= (int) $lugar["ID_LUGAR"] ?>"><?= escaparEvento($lugar["NOMBRE"]) ?> · <?= escaparEvento($lugar["DIRECCION"]) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
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
                    $imagen = trim((string) ($evento["IMAGEN"] ?? ""));
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
                                <span><i class="fa-solid fa-location-dot" aria-hidden="true"></i> <?= escaparEvento($evento["LUGAR"]) ?></span>
                            </div>
                            <span class="evento-fecha-publicacion"><i class="fa-regular fa-calendar" aria-hidden="true"></i> <?= escaparEvento($fechaTexto) ?></span>
                        </header>

                        <?php if ($imagenSrc !== ""): ?>
                            <div class="evento-post-imagen">
                                <img src="<?= escaparEvento($imagenSrc) ?>" alt="<?= escaparEvento($evento["LUGAR"]) ?>" loading="lazy">
                                <span><i class="fa-regular fa-clock" aria-hidden="true"></i> <?= escaparEvento(substr((string) $evento["HORA_INI"], 0, 5)) ?>–<?= escaparEvento(substr((string) $evento["HORA_FIN"], 0, 5)) ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="evento-post-contenido">
                            <h2><?= escaparEvento($evento["NOMBRE"]) ?></h2>
                            <?php if (!empty($evento["DESCRIPCION"])): ?>
                                <p class="evento-post-descripcion"><?= nl2br(escaparEvento($evento["DESCRIPCION"])) ?></p>
                            <?php endif; ?>
                            <p class="evento-post-direccion"><i class="fa-solid fa-location-dot" aria-hidden="true"></i> <?= escaparEvento($evento["DIRECCION"]) ?></p>
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
</body>
</html>
