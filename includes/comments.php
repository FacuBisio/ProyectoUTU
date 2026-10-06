<?php
require_once(__DIR__ . "/../conexion.php");
require_once(__DIR__ . "/../config/config.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['csrf_comentario'])) {
    $_SESSION['csrf_comentario'] = bin2hex(random_bytes(32));
}

$flashComentario = $_SESSION['flash_comentario'] ?? null;
unset($_SESSION['flash_comentario']);

try {
    $resultadoComentarios = $conexion->query(
        "SELECT COMENTARIO.COMENTARIO, USUARIO.NOMBRE, COMENTARIO.FECHA
         FROM COMENTARIO
         INNER JOIN USUARIO ON COMENTARIO.ID_USUARIO = USUARIO.ID_USUARIO
         ORDER BY COMENTARIO.ID_COMENTARIO DESC"
    );
} catch (mysqli_sql_exception $error) {
    error_log("No se pudieron cargar los comentarios: " . $error->getMessage());
    $resultadoComentarios = false;
}
?>

<section class="comentarios" id="comentarios">
    <header class="comentarios-header">
        <span class="comentarios-etiqueta">La comunidad comparte</span>
        <h2>Comentarios sobre Salto</h2>
        <p>Experiencias, recomendaciones y recuerdos de quienes conocen la ciudad.</p>
    </header>

    <?php if ($flashComentario) { ?>
        <p class="comentarios-aviso comentarios-aviso--<?= htmlspecialchars($flashComentario['tipo'], ENT_QUOTES, 'UTF-8') ?>" role="status">
            <?= htmlspecialchars($flashComentario['mensaje'], ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php } ?>

    <div class="comentarios-contenido">
        <div class="comentario-formulario">
            <h3>Compartí tu experiencia</h3>

            <?php if (isset($_SESSION['id_usuario'])) { ?>
                <p class="comentario-sesion">
                    Publicando como <strong><?= htmlspecialchars($_SESSION['nombre'] ?? 'Usuario', ENT_QUOTES, 'UTF-8') ?></strong>
                </p>

                <form action="<?= htmlspecialchars(url('pages/guardar_comentario.php'), ENT_QUOTES, 'UTF-8') ?>" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_comentario'], ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" name="return_to" value="<?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? '/', ENT_QUOTES, 'UTF-8') ?>">

                    <label for="texto-comentario">Tu comentario</label>
                    <textarea
                        id="texto-comentario"
                        name="comentario"
                        maxlength="2000"
                        rows="5"
                        placeholder="¿Qué te gustó de Salto? Compartí tu recomendación..."
                        required
                    ></textarea>
                    <div class="comentario-formulario-pie">
                        <small>Hasta 2000 caracteres</small>
                        <button type="submit">Publicar comentario</button>
                    </div>
                </form>
            <?php } else { ?>
                <p>Iniciá sesión para compartir tu experiencia con la comunidad.</p>
                <div class="comentario-acceso">
                    <a href="<?= htmlspecialchars(url('pages/login.php'), ENT_QUOTES, 'UTF-8') ?>">Iniciar sesión</a>
                    <a href="<?= htmlspecialchars(url('pages/register.php'), ENT_QUOTES, 'UTF-8') ?>">Crear cuenta</a>
                </div>
            <?php } ?>
        </div>

        <div class="comentarios-lista">
            <?php if ($resultadoComentarios === false) { ?>
                <p class="comentarios-vacio">No se pudieron cargar los comentarios en este momento. Intentá nuevamente más tarde.</p>
            <?php } elseif ($resultadoComentarios->num_rows === 0) { ?>
                <p class="comentarios-vacio">Todavía no hay comentarios. ¡Sé la primera persona en compartir su experiencia!</p>
            <?php } else { ?>
                <?php while ($comentario = $resultadoComentarios->fetch_assoc()) { ?>
                    <article class="comentario">
                        <div class="comentario-avatar" aria-hidden="true">
                            <?= htmlspecialchars(mb_strtoupper(mb_substr($comentario['NOMBRE'], 0, 1, 'UTF-8'), 'UTF-8'), ENT_QUOTES, 'UTF-8') ?>
                        </div>
                        <div class="comentario-detalle">
                            <div class="comentario-meta">
                                <h3><?= htmlspecialchars($comentario['NOMBRE'], ENT_QUOTES, 'UTF-8') ?></h3>
                                <time datetime="<?= htmlspecialchars($comentario['FECHA'], ENT_QUOTES, 'UTF-8') ?>">
                                    <?= htmlspecialchars(date('d/m/Y · H:i', strtotime($comentario['FECHA'])), ENT_QUOTES, 'UTF-8') ?>
                                </time>
                            </div>
                            <p><?= nl2br(htmlspecialchars($comentario['COMENTARIO'], ENT_QUOTES, 'UTF-8')) ?></p>
                        </div>
                    </article>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</section>
