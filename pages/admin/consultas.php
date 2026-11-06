<?php
require_once(__DIR__ . "/../../config/sesion.php");
iniciarSesionSegura();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../login.php");
    exit();
}

if ((int) ($_SESSION["id_rol"] ?? 0) !== 1) {
    http_response_code(403);
    exit("Acceso denegado.");
}

require_once("../../conexion.php");

if (empty($_SESSION["csrf_admin_consultas"])) {
    $_SESSION["csrf_admin_consultas"] = bin2hex(random_bytes(32));
}

$estadosConsulta = ["Pendiente", "En seguimiento", "Respondida"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST["csrf_token"] ?? "";
    $idConsultaRecibido = $_POST["id_consulta"] ?? null;
    $accion = $_POST["accion"] ?? "";
    $idConsulta = is_string($idConsultaRecibido)
        ? filter_var($idConsultaRecibido, FILTER_VALIDATE_INT)
        : false;

    if (
        !is_string($token)
        || !hash_equals($_SESSION["csrf_admin_consultas"], $token)
        || !$idConsulta
        || $idConsulta < 1
    ) {
        http_response_code(400);
        exit("La solicitud venció o no es válida. Recarga la página e inténtalo de nuevo.");
    }

    if ($accion === "actualizar_estado") {
        $estado = $_POST["estado"] ?? "";
        if (!is_string($estado) || !in_array($estado, $estadosConsulta, true)) {
            http_response_code(400);
            exit("El estado seleccionado no es válido.");
        }

        try {
            $stmtEstado = $conexion->prepare("UPDATE CONTACTO_CONSULTA SET ESTADO = ? WHERE ID_CONSULTA = ?");
            $stmtEstado->bind_param("si", $estado, $idConsulta);
            $stmtEstado->execute();
        } catch (mysqli_sql_exception $error) {
            error_log("No se pudo actualizar el estado de la consulta {$idConsulta}: " . $error->getMessage());
            http_response_code(500);
            exit("No se pudo actualizar el estado de la consulta. Inténtalo de nuevo.");
        }

        $_SESSION["flash_admin_consultas"] = $stmtEstado->affected_rows > 0
            ? "El estado de la consulta fue actualizado."
            : "El estado no cambió o la consulta ya no está disponible.";
        header("Location: consultas.php");
        exit();
    }

    if ($accion !== "eliminar") {
        http_response_code(400);
        exit("La acción solicitada no es válida.");
    }

    try {
        $stmtEliminar = $conexion->prepare("DELETE FROM CONTACTO_CONSULTA WHERE ID_CONSULTA = ?");
        $stmtEliminar->bind_param("i", $idConsulta);
        $stmtEliminar->execute();
    } catch (mysqli_sql_exception $error) {
        error_log("No se pudo eliminar la consulta {$idConsulta}: " . $error->getMessage());
        http_response_code(500);
        exit("No se pudo eliminar la consulta. Inténtalo de nuevo.");
    }

    $_SESSION["flash_admin_consultas"] = $stmtEliminar->affected_rows > 0
        ? "La consulta fue eliminada."
        : "La consulta ya no estaba disponible.";
    header("Location: consultas.php");
    exit();
}

$resultado = $conexion->query(
    "SELECT ID_CONSULTA, NOMBRE, CORREO, MENSAJE, FECHA, ESTADO
     FROM CONTACTO_CONSULTA
     ORDER BY FECHA DESC, ID_CONSULTA DESC"
);
$consultas = $resultado->fetch_all(MYSQLI_ASSOC);
$mensajeEstado = $_SESSION["flash_admin_consultas"] ?? "";
unset($_SESSION["flash_admin_consultas"]);

function escaparConsulta($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, "UTF-8");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#163d30">
    <title>Consultas de contacto | GoSalto</title>
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
            <a href="comentarios.php">Comentarios</a>
            <a href="consultas.php" aria-current="page">Consultas</a>
        </nav>
        <a class="admin-crud-salir" href="../logout.php">Cerrar sesión</a>
    </header>

    <main class="admin-crud-contenido">
        <div class="admin-crud-migas"><a href="panel.php">Panel</a><span>/</span><span>Consultas</span></div>
        <section class="admin-crud-encabezado">
            <div>
                <p class="admin-crud-etiqueta">Mensajes de la comunidad</p>
                <h1>Consultas de contacto</h1>
                <p>Revisa los mensajes recibidos desde el formulario para organizadores.</p>
            </div>
            <span class="admin-crud-total"><?= number_format(count($consultas), 0, ",", ".") ?> consultas</span>
        </section>

        <?php if ($mensajeEstado !== ""): ?>
            <div class="admin-crud-aviso admin-crud-aviso--exito" role="status"><?= escaparConsulta($mensajeEstado) ?></div>
        <?php endif; ?>

        <section class="admin-crud-comentarios-lista" aria-label="Consultas recibidas">
            <?php if (!$consultas): ?>
                <div class="admin-crud-tabla-card admin-crud-comentarios-vacio">
                    <h2>Todavía no hay consultas</h2>
                    <p>Los nuevos mensajes enviados desde el sitio aparecerán aquí.</p>
                </div>
            <?php else: ?>
                <?php foreach ($consultas as $consulta): ?>
                    <?php $idConsulta = (int) $consulta["ID_CONSULTA"]; ?>
                    <article class="admin-crud-comentario">
                        <header class="admin-crud-comentario-meta">
                            <span class="admin-crud-avatar" aria-hidden="true"><?= escaparConsulta(mb_strtoupper(mb_substr($consulta["NOMBRE"], 0, 1, "UTF-8"), "UTF-8")) ?></span>
                            <div class="admin-crud-comentario-autor">
                                <strong><?= escaparConsulta($consulta["NOMBRE"]) ?></strong>
                                <a href="mailto:<?= escaparConsulta($consulta["CORREO"]) ?>"><?= escaparConsulta($consulta["CORREO"]) ?></a>
                                <time datetime="<?= escaparConsulta($consulta["FECHA"]) ?>"><?= escaparConsulta(date("d/m/Y · H:i", strtotime($consulta["FECHA"]))) ?></time>
                            </div>
                        </header>
                        <p><?= nl2br(escaparConsulta($consulta["MENSAJE"])) ?></p>
                        <form class="admin-consulta-estado" method="POST" action="consultas.php">
                            <input type="hidden" name="csrf_token" value="<?= escaparConsulta($_SESSION["csrf_admin_consultas"]) ?>">
                            <input type="hidden" name="id_consulta" value="<?= $idConsulta ?>">
                            <input type="hidden" name="accion" value="actualizar_estado">
                            <label for="estado-consulta-<?= $idConsulta ?>">Estado</label>
                            <select id="estado-consulta-<?= $idConsulta ?>" name="estado">
                                <?php foreach ($estadosConsulta as $estado): ?>
                                    <option value="<?= escaparConsulta($estado) ?>" <?= $consulta["ESTADO"] === $estado ? "selected" : "" ?>><?= escaparConsulta($estado) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button class="admin-crud-accion editar" type="submit">Guardar estado</button>
                        </form>
                        <form method="POST" action="consultas.php" onsubmit="return confirm('¿Eliminar esta consulta?')">
                            <input type="hidden" name="csrf_token" value="<?= escaparConsulta($_SESSION["csrf_admin_consultas"]) ?>">
                            <input type="hidden" name="id_consulta" value="<?= $idConsulta ?>">
                            <input type="hidden" name="accion" value="eliminar">
                            <button class="admin-crud-accion eliminar" type="submit">Eliminar consulta</button>
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
