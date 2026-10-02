<?php

session_start();
require_once("../../conexion.php");

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 1) {
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
    <title>Administrar Lugares</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>

<body>

<h1>Administrar Lugares</h1>

<a href="agregar_lugar.php">
    <button>Agregar Lugar</button>
</a>

<br><br>

<table border="1" cellpadding="10">

    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Categoría</th>
        <th>Dirección</th>
        <th>Imagen</th>
        <th>Acciones</th>
    </tr>

    <?php while ($lugar = $resultado->fetch_assoc()) { ?>

        <tr>

            <td>
                <?= $lugar["ID_LUGAR"] ?>
            </td>

            <td>
                <?= htmlspecialchars($lugar["NOMBRE"]) ?>
            </td>

            <td>
                <?= htmlspecialchars($lugar["CATEGORIA"] ?? "Sin categoría") ?>
            </td>

            <td>
                <?= htmlspecialchars($lugar["DIRECCION"]) ?>
            </td>

            <td>

                <?php if (!empty($lugar["IMAGEN"])) { ?>

                    <img 
                        src="../../<?= htmlspecialchars($lugar["IMAGEN"]) ?>" 
                        width="100"
                    >

                <?php } else { ?>

                    Sin imagen

                <?php } ?>

            </td>

            <td>

                <a href="editar_lugar.php?id=<?= $lugar["ID_LUGAR"] ?>">
                    Editar
                </a>

                |

                <a 
                    href="eliminar_lugar.php?id=<?= $lugar["ID_LUGAR"] ?>"
                    onclick="return confirm('¿Seguro que quieres eliminar este lugar?')"
                >
                    Eliminar
                </a>

            </td>

        </tr>

    <?php } ?>

</table>

<br>

<a href="panel.php">
    Volver al panel
</a>

</body>

</html>