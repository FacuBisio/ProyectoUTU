<?php

session_start();
require_once("../../conexion.php");

if (!isset($_SESSION["id_usuario"]) || (int) ($_SESSION["id_rol"] ?? 0) !== 1) {
    http_response_code(403);
    die("Acceso denegado.");
}

$sql = "SELECT 
            l.ID_LUGAR,
            l.NOMBRE,
            l.DESCRIPCION,
            l.DIRECCION,
            l.IMAGEN,
            c.NOMBRE AS CATEGORIA
        FROM LUGAR l
        LEFT JOIN LUGAR_CATEGORIA lc 
            ON l.ID_LUGAR = lc.ID_LUGAR
        LEFT JOIN CATEGORIA c 
            ON lc.ID_CATEGORIA = c.ID_CATEGORIA
        ORDER BY l.ID_LUGAR DESC";

$resultado = $conexion->query($sql);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#163d30">
    <title>Administrar lugares | GoSalto</title>
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
            <a href="lugares.php" aria-current="page">Lugares</a>
            <a href="eventos.php">Eventos</a>
            <a href="comentarios.php">Comentarios</a>
        </nav>
        <a class="admin-crud-salir" href="../logout.php">Cerrar sesión</a>
    </header>

    <main class="admin-crud-contenido">
        <div class="admin-crud-migas"><a href="panel.php">Panel</a><span>/</span><span>Lugares</span></div>
        <section class="admin-crud-encabezado">
            <div>
                <p class="admin-crud-etiqueta">Contenido de GoSalto</p>
                <h1>Administrar lugares</h1>
                <p>Organizá los destinos que la comunidad puede descubrir en Salto.</p>
            </div>
            <a class="admin-crud-boton-principal" href="agregar_lugar.php"><span aria-hidden="true">＋</span> Agregar lugar</a>
        </section>

        <section class="admin-crud-tabla-card" aria-labelledby="titulo-listado">
            <div class="admin-crud-tabla-encabezado">
                <div>
                    <h2 id="titulo-listado">Lugares publicados</h2>
                    <p><?= number_format($resultado->num_rows, 0, ",", ".") ?> destinos en el catálogo</p>
                </div>
                <span class="admin-crud-total"><?= number_format($resultado->num_rows, 0, ",", ".") ?> en total</span>
            </div>

            <div class="admin-crud-tabla-scroll">
                <table class="admin-crud-tabla">
                    <thead>
                        <tr>
                            <th scope="col">Lugar</th>
                            <th scope="col">Categoría</th>
                            <th scope="col">Dirección</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($resultado->num_rows === 0): ?>
                            <tr><td class="admin-crud-vacio" colspan="4">Todavía no hay lugares publicados. Agregá el primero para empezar.</td></tr>
                        <?php else: ?>
                            <?php while ($lugar = $resultado->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <div class="admin-crud-lugar">
                                            <?php if (!empty($lugar["IMAGEN"])): ?>
                                                <img src="../../<?= htmlspecialchars($lugar["IMAGEN"], ENT_QUOTES, "UTF-8") ?>" alt="" loading="lazy">
                                            <?php else: ?>
                                                <span class="admin-crud-sin-imagen" aria-hidden="true">⌖</span>
                                            <?php endif; ?>
                                            <span><strong><?= htmlspecialchars($lugar["NOMBRE"], ENT_QUOTES, "UTF-8") ?></strong><small>ID #<?= (int) $lugar["ID_LUGAR"] ?></small></span>
                                        </div>
                                    </td>
                                    <td><span class="admin-crud-categoria"><?= htmlspecialchars($lugar["CATEGORIA"] ?? "Sin categoría", ENT_QUOTES, "UTF-8") ?></span></td>
                                    <td class="admin-crud-direccion"><?= htmlspecialchars($lugar["DIRECCION"], ENT_QUOTES, "UTF-8") ?></td>
                                    <td>
                                        <div class="admin-crud-acciones">
                                            <a class="admin-crud-accion editar" href="editar_lugar.php?id=<?= (int) $lugar["ID_LUGAR"] ?>">Editar</a>
                                            <a class="admin-crud-accion eliminar" href="eliminar_lugar.php?id=<?= (int) $lugar["ID_LUGAR"] ?>" onclick="return confirm('¿Seguro que quieres eliminar este lugar?')">Eliminar</a>
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