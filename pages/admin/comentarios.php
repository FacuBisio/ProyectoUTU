<?php
session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../login.php");
    exit();
}

if ((int) ($_SESSION["id_rol"] ?? 0) !== 1) {
    http_response_code(403);
    exit("Acceso denegado.");
}

require_once("../../conexion.php");

if (empty($_SESSION["csrf_admin_comentarios"])) {
    $_SESSION["csrf_admin_comentarios"] = bin2hex(random_bytes(32));
}

function redirigirComentarios($tipo, $mensaje)
{
    $_SESSION["flash_admin_comentarios"] = ["tipo" => $tipo, "mensaje" => $mensaje];
    header("Location: comentarios.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST["csrf_token"] ?? "";
    if (!is_string($token) || !hash_equals($_SESSION["csrf_admin_comentarios"], $token)) {
        http_response_code(400);
        exit("La solicitud venció o no es válida. Recargá la página e intentá nuevamente.");
    }

    $tipo = $_POST["tipo"] ?? "";
    $accion = $_POST["accion"] ?? "";
    $idComentarioRecibido = $_POST["id_comentario"] ?? null;
    $idComentario = is_string($idComentarioRecibido)
        ? filter_var($idComentarioRecibido, FILTER_VALIDATE_INT)
        : false;

    if (!in_array($tipo, ["salto", "evento"], true) || !in_array($accion, ["editar", "eliminar"], true) || !$idComentario || $idComentario < 1) {
        http_response_code(400);
        exit("Los datos enviados no son válidos.");
    }

    $tabla = $tipo === "salto" ? "COMENTARIO" : "EVENTO_COMENTARIO";

    if ($accion === "eliminar") {
        $stmt = $conexion->prepare("DELETE FROM {$tabla} WHERE ID_COMENTARIO = ?");
        $stmt->bind_param("i", $idComentario);
        $stmt->execute();

        redirigirComentarios("exito", $stmt->affected_rows > 0
            ? "El comentario fue eliminado."
            : "El comentario ya no estaba disponible.");
    }

    $texto = $_POST["comentario"] ?? "";
    $limite = $tipo === "evento" ? 1000 : 2000;
    if (!is_string($texto)) {
        redirigirComentarios("error", "El comentario enviado no es válido.");
    }

    $texto = trim($texto);
    if ($texto === "" || mb_strlen($texto, "UTF-8") > $limite) {
        redirigirComentarios("error", "El comentario debe tener entre 1 y {$limite} caracteres.");
    }

    $verificar = $conexion->prepare("SELECT 1 FROM {$tabla} WHERE ID_COMENTARIO = ?");
    $verificar->bind_param("i", $idComentario);
    $verificar->execute();
    if (!$verificar->get_result()->fetch_row()) {
        redirigirComentarios("error", "El comentario ya no está disponible.");
    }

    $stmt = $conexion->prepare("UPDATE {$tabla} SET COMENTARIO = ? WHERE ID_COMENTARIO = ?");
    $stmt->bind_param("si", $texto, $idComentario);
    $stmt->execute();

    redirigirComentarios("exito", "El comentario fue actualizado.");
}

$flash = $_SESSION["flash_admin_comentarios"] ?? null;
unset($_SESSION["flash_admin_comentarios"]);

$sqlComentarios = "
    SELECT
        'salto' AS TIPO,
        c.ID_COMENTARIO,
        c.COMENTARIO,
        c.FECHA,
        u.NOMBRE AS AUTOR,
        NULL AS PUBLICACION
    FROM COMENTARIO c
    LEFT JOIN USUARIO u ON u.ID_USUARIO = c.ID_USUARIO
    UNION ALL
    SELECT
        'evento' AS TIPO,
        ec.ID_COMENTARIO,
        ec.COMENTARIO,
        ec.FECHA,
        u.NOMBRE AS AUTOR,
        e.NOMBRE AS PUBLICACION
    FROM EVENTO_COMENTARIO ec
    LEFT JOIN USUARIO u ON u.ID_USUARIO = ec.ID_USUARIO
    LEFT JOIN EVENTO e ON e.ID_EVENTO = ec.ID_EVENTO
    ORDER BY FECHA DESC, ID_COMENTARIO DESC
";
$resultado = $conexion->query($sqlComentarios);
$comentarios = $resultado->fetch_all(MYSQLI_ASSOC);
$cantidadComentarios = count($comentarios);
$cantidadSalto = count(array_filter($comentarios, static function ($comentario) {
    return $comentario["TIPO"] === "salto";
}));
$cantidadEventos = $cantidadComentarios - $cantidadSalto;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#163d30">
    <title>Administrar comentarios | GoSalto</title>
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
            <a href="eventos.php">Eventos</a>
            <a href="comentarios.php" aria-current="page">Comentarios</a>
        </nav>
        <a class="admin-crud-salir" href="../logout.php">Cerrar sesión</a>
    </header>

    <main class="admin-crud-contenido">
        <div class="admin-crud-migas"><a href="panel.php">Panel</a><span>/</span><span>Comentarios</span></div>
        <section class="admin-crud-encabezado">
            <div>
                <p class="admin-crud-etiqueta">Conversaciones de la comunidad</p>
                <h1>Administrar comentarios</h1>
                <p>Revisá y mantené al día los comentarios de Salto y las publicaciones de eventos.</p>
            </div>
        </section>

        <?php if ($flash): ?>
            <div class="admin-crud-aviso admin-crud-aviso--<?= htmlspecialchars($flash["tipo"], ENT_QUOTES, "UTF-8") ?>" role="<?= $flash["tipo"] === "error" ? "alert" : "status" ?>">
                <?= htmlspecialchars($flash["mensaje"], ENT_QUOTES, "UTF-8") ?>
            </div>
        <?php endif; ?>

        <section class="admin-crud-comentarios-resumen" aria-label="Resumen de comentarios">
            <article><span class="admin-crud-comentarios-icono">☼</span><span><strong><?= number_format($cantidadSalto, 0, ",", ".") ?></strong><small>Comentarios sobre Salto</small></span></article>
            <article><span class="admin-crud-comentarios-icono eventos">▤</span><span><strong><?= number_format($cantidadEventos, 0, ",", ".") ?></strong><small>Comentarios en eventos</small></span></article>
        </section>

        <section class="admin-crud-comentarios-lista" aria-label="Comentarios publicados">
            <?php if (!$comentarios): ?>
                <div class="admin-crud-tabla-card admin-crud-comentarios-vacio">
                    <span aria-hidden="true">♡</span>
                    <h2>Todavía no hay comentarios</h2>
                    <p>Cuando la comunidad comparta sus experiencias, vas a poder gestionarlas desde acá.</p>
                </div>
            <?php else: ?>
                <?php foreach ($comentarios as $comentario): ?>
                    <?php
                    $tipo = $comentario["TIPO"];
                    $id = (int) $comentario["ID_COMENTARIO"];
                    $limite = $tipo === "evento" ? 1000 : 2000;
                    $autor = $comentario["AUTOR"] ?: "Usuario eliminado";
                    ?>
                    <article class="admin-crud-comentario">
                        <header class="admin-crud-comentario-meta">
                            <span class="admin-crud-avatar" aria-hidden="true"><?= htmlspecialchars(mb_strtoupper(mb_substr($autor, 0, 1, "UTF-8"), "UTF-8"), ENT_QUOTES, "UTF-8") ?></span>
                            <div class="admin-crud-comentario-autor">
                                <strong><?= htmlspecialchars($autor, ENT_QUOTES, "UTF-8") ?></strong>
                                <time datetime="<?= htmlspecialchars($comentario["FECHA"] ?? "", ENT_QUOTES, "UTF-8") ?>"><?= !empty($comentario["FECHA"]) ? htmlspecialchars(date("d/m/Y · H:i", strtotime($comentario["FECHA"])), ENT_QUOTES, "UTF-8") : "Fecha no disponible" ?></time>
                            </div>
                            <span class="admin-crud-comentario-tipo <?= $tipo ?>">
                                <?= $tipo === "evento" ? "Publicación de evento" : "Comentarios sobre Salto" ?>
                            </span>
                        </header>
                        <?php if ($tipo === "evento" && !empty($comentario["PUBLICACION"])): ?>
                            <p class="admin-crud-comentario-publicacion">En: <strong><?= htmlspecialchars($comentario["PUBLICACION"], ENT_QUOTES, "UTF-8") ?></strong></p>
                        <?php endif; ?>
                        <form class="admin-crud-comentario-form" method="POST" action="comentarios.php">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION["csrf_admin_comentarios"], ENT_QUOTES, "UTF-8") ?>">
                            <input type="hidden" name="tipo" value="<?= htmlspecialchars($tipo, ENT_QUOTES, "UTF-8") ?>">
                            <input type="hidden" name="id_comentario" value="<?= $id ?>">
                            <label class="admin-crud-sr-only" for="comentario-<?= $tipo ?>-<?= $id ?>">Editar comentario de <?= htmlspecialchars($autor, ENT_QUOTES, "UTF-8") ?></label>
                            <textarea id="comentario-<?= $tipo ?>-<?= $id ?>" name="comentario" maxlength="<?= $limite ?>" rows="3" required><?= htmlspecialchars($comentario["COMENTARIO"], ENT_QUOTES, "UTF-8") ?></textarea>
                            <div class="admin-crud-comentario-acciones">
                                <small>Hasta <?= $limite ?> caracteres</small>
                                <button class="admin-crud-accion editar" type="submit" name="accion" value="editar">Guardar cambios</button>
                                <button class="admin-crud-accion eliminar" type="submit" name="accion" value="eliminar" onclick="return confirm('¿Seguro que querés eliminar este comentario?')">Eliminar</button>
                            </div>
                        </form>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>

        <footer class="admin-crud-pie"><span>GoSalto <span aria-hidden="true">·</span> Panel de administración</span><a href="panel.php">Volver al panel <span aria-hidden="true">→</span></a></footer>
    </main>
</div>
<?php include("../../includes/chat-widget.php"); ?>
</body>
</html>
