<?php

require_once("../../conexion.php");

$sql = "SELECT 
            LUGAR.ID_LUGAR,
            LUGAR.NOMBRE,
            LUGAR.DESCRIPCION,
            LUGAR.DIRECCION,
            LUGAR.IMAGEN
        FROM LUGAR
        INNER JOIN LUGAR_CATEGORIA
            ON LUGAR.ID_LUGAR = LUGAR_CATEGORIA.ID_LUGAR
        WHERE LUGAR_CATEGORIA.ID_CATEGORIA = 5";

$lugares = $conexion->query($sql);

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Termas</title>

    <link rel="stylesheet" href="../../assets/css/var.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/componentes.css">
    <link rel="stylesheet" href="../../assets/css/style-secciones.css">
    <link rel="stylesheet" href="../../assets/css/slider.css">
    <link rel="stylesheet" href="../../assets/css/comments.css?v=3">

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<main class="main-content pagina-interna">

<?php include("../../includes/navbar.php"); ?>


<section class="slider-container">

    <button class="slider-btn prev">❮</button>


    <?php

    $primer_slide = true;

    while ($lugar = $lugares->fetch_assoc()) {

    ?>

        <div class="slide <?= $primer_slide ? 'active' : '' ?>">

            <img 
                src="../../<?= htmlspecialchars($lugar["IMAGEN"]) ?>" 
                alt="<?= htmlspecialchars($lugar["NOMBRE"]) ?>"
            >

            <div class="slide-info">

                <h2>
                    <?= htmlspecialchars($lugar["NOMBRE"]) ?>
                </h2>

                <p>
                    <?= htmlspecialchars($lugar["DESCRIPCION"]) ?>
                </p>

                <?php if (!empty($lugar["DIRECCION"])) { ?>

                    <small>
                        📍 <?= htmlspecialchars($lugar["DIRECCION"]) ?>
                    </small>

                <?php } ?>

            </div>

        </div>

    <?php

        $primer_slide = false;

    }

    ?>


    <button class="slider-btn next">❯</button>


    <div class="slider-dots">

        <span class="dot active"></span>
        <span class="dot"></span>
        <span class="dot"></span>

    </div>

</section>


<?php include("../../includes/comments.php"); ?>


</main>


<?php include("../../includes/footer.php"); ?>

<?php include("../../includes/chat-widget.php"); ?>


<script src="../../assets/js/slider.js"></script>

</body>

</html>