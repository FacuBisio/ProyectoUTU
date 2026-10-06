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

$sql = "SELECT usuario.id_usuario,
               usuario.id_rol,
               usuario.nombre,
               usuario.correo,
               rol.nombre AS rol
        FROM usuario
        INNER JOIN rol
        ON usuario.id_rol = rol.id_rol
        ORDER BY usuario.id_usuario";

$resultado = $conexion->query($sql);
$cantidadUsuarios = (int) $resultado->num_rows;
$idAdministradorActual = (int) $_SESSION["id_usuario"];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#163d30">
    <title>Administrar usuarios | GoSalto</title>
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
            <a href="usuarios.php" aria-current="page">Usuarios</a>
            <a href="lugares.php">Lugares</a>
            <a href="eventos.php">Eventos</a>
            <a href="comentarios.php">Comentarios</a>
        </nav>
        <a class="admin-crud-salir" href="../logout.php">Cerrar sesión</a>
    </header>

    <main class="admin-crud-contenido">
        <div class="admin-crud-migas"><a href="panel.php">Panel</a><span>/</span><span>Usuarios</span></div>
        <section class="admin-crud-encabezado">
            <div>
                <p class="admin-crud-etiqueta">Comunidad GoSalto</p>
                <h1>Administrar usuarios</h1>
                <p>Gestioná las cuentas y los permisos de quienes forman parte de la comunidad.</p>
            </div>
        </section>

        <section class="admin-crud-tabla-card" aria-labelledby="titulo-listado">
            <div class="admin-crud-tabla-encabezado">
                <div>
                    <h2 id="titulo-listado">Cuentas registradas</h2>
                    <p>Revisá los datos y actualizá el rol de cada cuenta.</p>
                </div>
                <span class="admin-crud-total"><?= number_format($cantidadUsuarios, 0, ",", ".") ?> usuarios</span>
            </div>

            <div class="admin-crud-tabla-scroll">
                <table class="admin-crud-tabla admin-crud-tabla--usuarios">
                    <thead>
                        <tr>
                            <th scope="col">Usuario</th>
                            <th scope="col">Correo electrónico</th>
                            <th scope="col">Rol actual</th>
                            <th scope="col">Administrar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($cantidadUsuarios === 0): ?>
                            <tr><td class="admin-crud-vacio" colspan="4">Todavía no hay usuarios registrados.</td></tr>
                        <?php else: ?>
                            <?php while ($fila = $resultado->fetch_assoc()): ?>
                                <?php
                                $idUsuario = (int) $fila["id_usuario"];
                                $esUsuarioActual = $idUsuario === $idAdministradorActual;
                                $claseRol = "usuario";
                                if ((int) $fila["id_rol"] === 1) {
                                    $claseRol = "administrador";
                                } elseif ((int) $fila["id_rol"] === 2) {
                                    $claseRol = "organizador";
                                }
                                ?>
                                <tr>
                                    <td>
                                        <div class="admin-crud-cuenta">
                                            <span class="admin-crud-avatar" aria-hidden="true"><?= htmlspecialchars(mb_strtoupper(mb_substr($fila["nombre"], 0, 1, "UTF-8"), "UTF-8"), ENT_QUOTES, "UTF-8") ?></span>
                                            <span><strong><?= htmlspecialchars($fila["nombre"], ENT_QUOTES, "UTF-8") ?></strong><small>ID #<?= $idUsuario ?><?= $esUsuarioActual ? " · Tu cuenta" : "" ?></small></span>
                                        </div>
                                    </td>
                                    <td class="admin-crud-correo"><?= htmlspecialchars($fila["correo"], ENT_QUOTES, "UTF-8") ?></td>
                                    <td><span class="admin-crud-rol <?= $claseRol ?>"><?= htmlspecialchars($fila["rol"], ENT_QUOTES, "UTF-8") ?></span></td>
                                    <td>
                                        <div class="admin-crud-acciones admin-crud-acciones--usuario">
                                            <form class="admin-crud-rol-form" action="cambiar_rol.php" method="POST">
                                                <input type="hidden" name="id_usuario" value="<?= $idUsuario ?>">
                                                <label class="admin-crud-sr-only" for="rol-<?= $idUsuario ?>">Rol para <?= htmlspecialchars($fila["nombre"], ENT_QUOTES, "UTF-8") ?></label>
                                                <select id="rol-<?= $idUsuario ?>" name="id_rol" aria-label="Rol para <?= htmlspecialchars($fila["nombre"], ENT_QUOTES, "UTF-8") ?>">
                                                    <option value="1" <?= (int) $fila["id_rol"] === 1 ? "selected" : "" ?>>Administrador</option>
                                                    <option value="2" <?= (int) $fila["id_rol"] === 2 ? "selected" : "" ?>>Organizador</option>
                                                    <option value="3" <?= (int) $fila["id_rol"] === 3 ? "selected" : "" ?>>Usuario</option>
                                                </select>
                                                <button class="admin-crud-accion editar" type="submit">Guardar rol</button>
                                            </form>
                                            <?php if (!$esUsuarioActual): ?>
                                                <a class="admin-crud-accion eliminar" href="eliminar_usuario.php?id=<?= $idUsuario ?>" onclick="return confirm('¿Seguro que quieres eliminar a <?= htmlspecialchars($fila["nombre"], ENT_QUOTES, "UTF-8") ?>?')">Eliminar</a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
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
