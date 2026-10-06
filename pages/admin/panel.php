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

$consultaEstadisticas = $conexion->query(
    "SELECT
        (SELECT COUNT(*) FROM USUARIO) AS usuarios,
        (SELECT COUNT(*) FROM LUGAR) AS lugares,
        (SELECT COUNT(*) FROM EVENTO) AS eventos,
        (
            (SELECT COUNT(*) FROM COMENTARIO) +
            (SELECT COUNT(*) FROM EVENTO_COMENTARIO)
        ) AS comentarios"
);
$estadisticas = $consultaEstadisticas->fetch_assoc();
$nombreAdministrador = htmlspecialchars($_SESSION["nombre"] ?? "Administrador", ENT_QUOTES, "UTF-8");

function formatoEstadistica($valor)
{
    return number_format((int) $valor, 0, ",", ".");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#163d30">
    <title>Panel de administración | GoSalto</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="../../assets/css/admin-panel.css?v=2">
</head>
<body>
    <a class="salto-saltar" href="#contenido">Saltar al contenido</a>
    <div class="salto-panel">
        <aside class="salto-lateral" aria-label="Navegación de administración">
            <a class="salto-marca" href="../../index.php" aria-label="GoSalto, ir al inicio">
                <span class="salto-marca-icono" aria-hidden="true">G</span>
                <span>Go<span>Salto</span><small>Administración</small></span>
            </a>

            <div class="salto-lateral-seccion">Espacio de trabajo</div>
            <nav class="salto-menu">
                <a class="salto-menu-enlace activo" href="panel.php" aria-current="page">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="8" height="8" rx="2"/><rect x="13" y="3" width="8" height="5" rx="2"/><rect x="13" y="10" width="8" height="11" rx="2"/><rect x="3" y="13" width="8" height="8" rx="2"/></svg>
                    <span>Resumen</span>
                </a>
                <a class="salto-menu-enlace" href="usuarios.php">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M20 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    <span>Usuarios</span>
                </a>
                <a class="salto-menu-enlace" href="lugares.php">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg>
                    <span>Lugares</span>
                </a>
                <a class="salto-menu-enlace" href="eventos.php">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 11h18"/></svg>
                    <span>Eventos</span>
                </a>
                <a class="salto-menu-enlace" href="comentarios.php">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 11.5a8.5 8.5 0 0 1-12.7 7.4L3 21l2.1-5.3A8.5 8.5 0 1 1 21 11.5Z"/></svg>
                    <span>Comentarios</span>
                </a>
            </nav>

            <div class="salto-lateral-ayuda">
                <span class="salto-ayuda-icono" aria-hidden="true">✦</span>
                <strong>Tu ciudad, mejor conectada</strong>
                <p>Administrá la información que ayuda a descubrir Salto.</p>
                <a href="../../index.php">Volver a GoSalto <span aria-hidden="true">→</span></a>
            </div>

            <a class="salto-cerrar" href="../logout.php">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 17l5-5-5-5"/><path d="M15 12H3"/><path d="M12 3h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6"/></svg>
                Cerrar sesión
            </a>
        </aside>

        <main class="salto-contenido" id="contenido">
            <header class="salto-cabecera">
                <div>
                    <p class="salto-migas">GoSalto <span>/</span> Administración</p>
                    <p class="salto-fecha">Panel de control</p>
                </div>
                <div class="salto-perfil">
                    <span class="salto-avatar" aria-hidden="true"><?= htmlspecialchars(mb_strtoupper(mb_substr($_SESSION["nombre"] ?? "A", 0, 1, "UTF-8"), "UTF-8"), ENT_QUOTES, "UTF-8") ?></span>
                    <span><strong><?= $nombreAdministrador ?></strong><small>Administrador</small></span>
                </div>
            </header>

            <section class="salto-bienvenida" aria-labelledby="titulo-panel">
                <div class="salto-bienvenida-texto">
                    <span class="salto-etiqueta"><span aria-hidden="true"></span> Centro de administración</span>
                    <h1 id="titulo-panel">¡Hola, <?= $nombreAdministrador ?>!</h1>
                    <p>Gestioná GoSalto desde un solo lugar y mantené al día la información de la comunidad.</p>
                    <a class="salto-boton-principal" href="lugares.php">
                        Administrar lugares
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </a>
                </div>
                <div class="salto-bienvenida-ilustracion" aria-hidden="true">
                    <span class="salto-sol"></span>
                    <span class="salto-montana salto-montana-uno"></span>
                    <span class="salto-montana salto-montana-dos"></span>
                    <span class="salto-arbol salto-arbol-uno"></span>
                    <span class="salto-arbol salto-arbol-dos"></span>
                </div>
            </section>

            <section class="salto-estadisticas" aria-label="Resumen del sitio">
                <article class="salto-estadistica">
                    <div class="salto-estadistica-icono usuarios" aria-hidden="true">
                        <svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M20 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <div><p>Usuarios registrados</p><strong><?= formatoEstadistica($estadisticas["usuarios"]) ?></strong></div>
                    <span class="salto-estadistica-nota">Cuentas de la comunidad</span>
                </article>
                <article class="salto-estadistica">
                    <div class="salto-estadistica-icono lugares" aria-hidden="true">
                        <svg viewBox="0 0 24 24"><path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg>
                    </div>
                    <div><p>Lugares publicados</p><strong><?= formatoEstadistica($estadisticas["lugares"]) ?></strong></div>
                    <span class="salto-estadistica-nota">Destinos para explorar</span>
                </article>
                <article class="salto-estadistica">
                    <div class="salto-estadistica-icono eventos" aria-hidden="true">
                        <svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 11h18"/></svg>
                    </div>
                    <div><p>Eventos</p><strong><?= formatoEstadistica($estadisticas["eventos"]) ?></strong></div>
                    <span class="salto-estadistica-nota">En la agenda de Salto</span>
                </article>
                <article class="salto-estadistica">
                    <div class="salto-estadistica-icono comentarios" aria-hidden="true">
                        <svg viewBox="0 0 24 24"><path d="M21 11.5a8.5 8.5 0 0 1-12.7 7.4L3 21l2.1-5.3A8.5 8.5 0 1 1 21 11.5Z"/></svg>
                    </div>
                    <div><p>Comentarios</p><strong><?= formatoEstadistica($estadisticas["comentarios"]) ?></strong></div>
                    <span class="salto-estadistica-nota">Conversaciones compartidas</span>
                </article>
            </section>

            <section class="salto-gestion" aria-labelledby="titulo-gestion">
                <div class="salto-seccion-encabezado">
                    <div>
                        <p class="salto-seccion-etiqueta">Accesos directos</p>
                        <h2 id="titulo-gestion">¿Qué querés gestionar?</h2>
                    </div>
                    <span>Herramientas de administración</span>
                </div>
                <div class="salto-acciones">
                    <a class="salto-accion" href="usuarios.php">
                        <span class="salto-accion-icono usuarios" aria-hidden="true">
                            <svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M20 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </span>
                        <span class="salto-accion-texto"><strong>Administrar usuarios</strong><small>Revisá cuentas y permisos de acceso.</small></span>
                        <span class="salto-accion-flecha" aria-hidden="true">↗</span>
                    </a>
                    <a class="salto-accion" href="lugares.php">
                        <span class="salto-accion-icono lugares" aria-hidden="true">
                            <svg viewBox="0 0 24 24"><path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg>
                        </span>
                        <span class="salto-accion-texto"><strong>Administrar lugares</strong><small>Actualizá destinos, descripciones e imágenes.</small></span>
                        <span class="salto-accion-flecha" aria-hidden="true">↗</span>
                    </a>
                    <a class="salto-accion" href="eventos.php">
                        <span class="salto-accion-icono eventos" aria-hidden="true">
                            <svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 11h18"/></svg>
                        </span>
                        <span class="salto-accion-texto"><strong>Administrar eventos</strong><small>Revisá, editá o eliminá publicaciones de la comunidad.</small></span>
                        <span class="salto-accion-flecha" aria-hidden="true">↗</span>
                    </a>
                    <a class="salto-accion" href="agregar_lugar.php">
                        <span class="salto-accion-icono agregar" aria-hidden="true">
                            <svg viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                        </span>
                        <span class="salto-accion-texto"><strong>Agregar un lugar</strong><small>Sumá un nuevo destino a GoSalto.</small></span>
                        <span class="salto-accion-flecha" aria-hidden="true">↗</span>
                    </a>
                    <a class="salto-accion" href="comentarios.php">
                        <span class="salto-accion-icono comentarios" aria-hidden="true">
                            <svg viewBox="0 0 24 24"><path d="M21 11.5a8.5 8.5 0 0 1-12.7 7.4L3 21l2.1-5.3A8.5 8.5 0 1 1 21 11.5Z"/></svg>
                        </span>
                        <span class="salto-accion-texto"><strong>Administrar comentarios</strong><small>Revisá, editá o eliminá comentarios de la comunidad.</small></span>
                        <span class="salto-accion-flecha" aria-hidden="true">↗</span>
                    </a>
                </div>
            </section>

            <footer class="salto-pie">
                <span>GoSalto <span aria-hidden="true">·</span> Panel de administración</span>
                <a href="../../index.php">Volver al sitio <span aria-hidden="true">→</span></a>
            </footer>
        </main>
    </div>
    <?php include("../../includes/chat-widget.php"); ?>
</body>
</html>
