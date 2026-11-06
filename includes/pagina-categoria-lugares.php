<?php
require_once(__DIR__ . "/../config/sesion.php");
iniciarSesionSegura();

require_once(__DIR__ . "/../conexion.php");
require_once(__DIR__ . "/../config/imagenes-lugares.php");

$mapaCategorias = [$categoriaId];
$consulta = $conexion->prepare(
    "SELECT LUGAR.ID_LUGAR, LUGAR.NOMBRE, LUGAR.DESCRIPCION, LUGAR.DIRECCION
     FROM LUGAR
     INNER JOIN LUGAR_CATEGORIA ON LUGAR.ID_LUGAR = LUGAR_CATEGORIA.ID_LUGAR
     WHERE LUGAR_CATEGORIA.ID_CATEGORIA = ?
     ORDER BY LUGAR.NOMBRE"
);
$consulta->bind_param("i", $categoriaId);
$consulta->execute();
$lugares = $consulta->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($tituloPagina, ENT_QUOTES, "UTF-8") ?></title>

    <link rel="stylesheet" href="../../assets/css/var.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/componentes.css">
    <link rel="stylesheet" href="../../assets/css/style-secciones.css">
    <link rel="stylesheet" href="../../assets/css/slider.css">
    <link rel="stylesheet" href="../../assets/css/comments.css?v=3">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <?php include(__DIR__ . "/mapa-head.php"); ?>
</head>
<body>
<main class="main-content pagina-interna">
    <?php include(__DIR__ . "/navbar.php"); ?>

    <section class="slider-container" aria-label="<?= htmlspecialchars($tituloPagina, ENT_QUOTES, "UTF-8") ?>">
        <button class="slider-btn prev" type="button" aria-label="Lugar anterior">❮</button>

        <?php foreach ($lugares as $indice => $lugar) { ?>
            <article class="slide <?= $indice === 0 ? "active" : "" ?>">
                <img
                    src="<?= htmlspecialchars(rutaImagenLugar((int) $lugar["ID_LUGAR"]), ENT_QUOTES, "UTF-8") ?>"
                    alt="<?= htmlspecialchars($lugar["NOMBRE"], ENT_QUOTES, "UTF-8") ?>"
                >
                <div class="slide-info">
                    <h2><?= htmlspecialchars($lugar["NOMBRE"], ENT_QUOTES, "UTF-8") ?></h2>
                    <p><?= htmlspecialchars($lugar["DESCRIPCION"] ?? "", ENT_QUOTES, "UTF-8") ?></p>
                    <?php if ($mostrarDireccion && !empty($lugar["DIRECCION"])) { ?>
                        <p><strong>Dirección:</strong> <?= htmlspecialchars($lugar["DIRECCION"], ENT_QUOTES, "UTF-8") ?></p>
                    <?php } ?>
                </div>
            </article>
        <?php } ?>

        <button class="slider-btn next" type="button" aria-label="Lugar siguiente">❯</button>
        <div class="slider-dots" aria-label="Seleccionar lugar"></div>
    </section>

    <?php include(__DIR__ . "/mapa-categoria.php"); ?>
    <?php include(__DIR__ . "/comments.php"); ?>
</main>

<?php include(__DIR__ . "/footer.php"); ?>
<?php include(__DIR__ . "/chat-widget.php"); ?>
<script src="../../assets/js/slider.js?v=2"></script>
</body>
</html>
