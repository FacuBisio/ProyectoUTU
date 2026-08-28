



<?php

require_once(__DIR__ . "/../conexion.php");

// Obtener lo que escribió el usuario
$busqueda = isset($_GET["q"]) ? trim($_GET["q"]) : "";

// Variable para guardar resultados
$resultados = null;

// Si hay una búsqueda
if ($busqueda !== "") {

    $termino = "%" . $busqueda . "%";

    $sql = "
        SELECT 
            l.id_lugar,
            l.id_categoria,
            l.nombre,
            l.descripcion,
            l.direccion,
            l.imagen,
            c.nombre AS categoria
        FROM lugar l
        INNER JOIN categoria c 
            ON l.id_categoria = c.id_categoria
        WHERE 
            l.nombre LIKE ?
            OR l.descripcion LIKE ?
            OR l.direccion LIKE ?
            OR c.nombre LIKE ?
        ORDER BY l.nombre ASC
    ";

    $stmt = $conexion->prepare($sql);

    if ($stmt) {

        $stmt->bind_param(
            "ssss",
            $termino,
            $termino,
            $termino,
            $termino
        );

        $stmt->execute();

        $resultados = $stmt->get_result();

        $stmt->close();

    } else {

        die("Error en la consulta: " . $conexion->error);

    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Buscar - SIGTUR</title>

    <!-- VARIABLES -->
    <link rel="stylesheet" href="../assets/css/var.css">

    <!-- CSS PRINCIPAL -->
    <link rel="stylesheet" href="../assets/css/styles.css">

    <!-- COMPONENTES -->
    <link rel="stylesheet" href="../assets/css/componentes.css">

    <!-- ESTILOS DE SECCIONES -->
    <link rel="stylesheet" href="../assets/css/style-secciones.css">

    <!-- CSS DEL BUSCADOR -->
    <link rel="stylesheet" href="../assets/css/buscar.css">

    <!-- GOOGLE FONT -->
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap"
        rel="stylesheet"
    >

    <!-- FONT AWESOME -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    >

</head>

<body>

<?php include(__DIR__ . "/../includes/navbar.php"); ?>


<main class="pagina-busqueda">

    <div class="contenedor-busqueda">

        <h1>Buscar lugares</h1>


        <!-- BUSCADOR PRINCIPAL -->

        <form class="buscador-pagina" action="" method="GET">

            <input
                type="text"
                name="q"
                value="<?= htmlspecialchars($busqueda) ?>"
                placeholder="¿Qué lugar estás buscando?"
                autocomplete="off"
                required
            >

            <button type="submit">
                <i class="fa-solid fa-magnifying-glass"></i>
                Buscar
            </button>

        </form>


        <?php if ($busqueda === ""): ?>

            <div class="mensaje-inicial">

                <h2>🔎 Buscá un lugar turístico</h2>

                <p>
                    Escribí el nombre de un lugar, una categoría,
                    una dirección o una palabra relacionada.
                </p>

                <div class="ejemplos-busqueda">

                    <span>Parque</span>
                    <span>Termas</span>
                    <span>Museo</span>
                    <span>Paisajes</span>
                    <span>Patrimonio</span>
                    <span>Ocio</span>

                </div>

            </div>


        <?php else: ?>

            <div class="titulo-resultados">

                <h2>
                    Resultados para:
                    <strong>
                        "<?= htmlspecialchars($busqueda) ?>"
                    </strong>
                </h2>

            </div>


            <?php if ($resultados && $resultados->num_rows > 0): ?>

                <p class="cantidad-resultados">
                    <?= $resultados->num_rows ?>
                    resultado(s) encontrado(s)
                </p>


                <div class="resultados-busqueda">


                    <?php while ($lugar = $resultados->fetch_assoc()): ?>


                        <article class="resultado-lugar">


                            <!-- IMAGEN -->

                            <div class="resultado-imagen">

                                <?php if (!empty($lugar["imagen"])): ?>

                                    <img
                                        src="../assets/img/<?= htmlspecialchars($lugar["imagen"]) ?>"
                                        alt="<?= htmlspecialchars($lugar["nombre"]) ?>"
                                        onerror="this.style.display='none';"
                                    >

                                <?php else: ?>

                                    <div class="imagen-sin-foto">
                                        <i class="fa-regular fa-image"></i>
                                    </div>

                                <?php endif; ?>

                            </div>


                            <!-- INFORMACIÓN -->

                            <div class="resultado-info">

                                <span class="categoria-resultado">

                                    <i class="fa-solid fa-location-dot"></i>

                                    <?= htmlspecialchars($lugar["categoria"]) ?>

                                </span>


                                <h3>
                                    <?= htmlspecialchars($lugar["nombre"]) ?>
                                </h3>


                                <?php if (!empty($lugar["descripcion"])): ?>

                                    <p class="descripcion-resultado">
                                        <?= htmlspecialchars($lugar["descripcion"]) ?>
                                    </p>

                                <?php endif; ?>


                                <?php if (!empty($lugar["direccion"])): ?>

                                    <p class="direccion">

                                        <i class="fa-solid fa-map-pin"></i>

                                        <?= htmlspecialchars($lugar["direccion"]) ?>

                                    </p>

                                <?php endif; ?>


                            </div>


                        </article>


                    <?php endwhile; ?>


                </div>


            <?php else: ?>

                <div class="sin-resultados">

                    <div class="icono-sin-resultados">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>

                    <h3>No encontramos resultados</h3>

                    <p>
                        No encontramos lugares relacionados con
                        <strong>
                            "<?= htmlspecialchars($busqueda) ?>"
                        </strong>.
                    </p>

                    <p>
                        Probá con otra palabra, por ejemplo:
                        <strong>parque</strong>,
                        <strong>termas</strong> o
                        <strong>museo</strong>.
                    </p>

                </div>

            <?php endif; ?>


        <?php endif; ?>


    </div>

</main>


</body>
</html>

