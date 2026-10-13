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
        WHERE LUGAR_CATEGORIA.ID_CATEGORIA = 7";

$resultado = $conexion->query($sql);
// Reuse the same records for the slider and its pagination dots.
$lugares = $resultado->fetch_all(MYSQLI_ASSOC);
$mapaCategorias = [7];
$mapaTitulo = "Ubicación de restaurantes";
$mapaDescripcion = "Encontrá restaurantes y propuestas para disfrutar la gastronomía de Salto.";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurantes</title>

    <link rel="stylesheet" href="../../assets/css/var.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/componentes.css">
    <link rel="stylesheet" href="../../assets/css/style-secciones.css">
    <link rel="stylesheet" href="../../assets/css/slider.css">
    <link rel="stylesheet" href="../../assets/css/comments.css?v=3">

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <?php include("../../includes/mapa-head.php"); ?>
</head>

<body>

<main class="main-content pagina-interna">

<?php include("../../includes/navbar.php"); ?>

<section class="slider-container">

    <button class="slider-btn prev">❮</button>

    <?php foreach ($lugares as $indice => $lugar) { ?>

        <div class="slide <?php echo $indice === 0 ? 'active' : ''; ?>">

            <img 
                src="../../<?php echo htmlspecialchars($lugar['IMAGEN']); ?>" 
                alt="<?php echo htmlspecialchars($lugar['NOMBRE']); ?>"
            >

            <div class="slide-info">

                <h2>
                    <?php echo htmlspecialchars($lugar['NOMBRE']); ?>
                </h2>

                <p>
                    <?php echo htmlspecialchars($lugar['DESCRIPCION']); ?>
                </p>

                <p>
                    <strong>Dirección:</strong>
                    <?php echo htmlspecialchars($lugar['DIRECCION']); ?>
                </p>

            </div>

        </div>

    <?php } ?>

    <button class="slider-btn next">❯</button>

    <div class="slider-dots">

        <?php foreach ($lugares as $indice => $lugar) { ?>
            <span class="dot <?php echo $indice === 0 ? 'active' : ''; ?>"></span>
        <?php } ?>

    </div>

</section>

<?php include("../../includes/mapa-categoria.php"); ?>

<?php include("../../includes/comments.php"); ?>

</main>

<?php include("../../includes/footer.php"); ?>

<?php include("../../includes/chat-widget.php"); ?>

<script src="../../assets/js/slider.js"></script>

</body>
</html>