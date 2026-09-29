<?php

require_once("../../conexion.php");

$sql = "SELECT 
            LUGAR.ID_LUGAR,
            LUGAR.NOMBRE,
            LUGAR.DESCRIPCION,
            LUGAR.DIRECCION,
            LUGAR.IMAGEN,
            LUGAR.LATITUD,
            LUGAR.LONGITUD
        FROM LUGAR
        INNER JOIN LUGAR_CATEGORIA
            ON LUGAR.ID_LUGAR = LUGAR_CATEGORIA.ID_LUGAR
        WHERE LUGAR_CATEGORIA.ID_CATEGORIA = 8";

$resultado = $conexion->query($sql);

?>

<!DOCTYPE html>

<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Comida Rápida</title>

<link rel="stylesheet" href="<?php echo '../../assets/css/var.css'; ?>">
<link rel="stylesheet" href="<?php echo '../../assets/css/styles.css'; ?>">
<link rel="stylesheet" href="<?php echo '../../assets/css/componentes.css'; ?>">
<link rel="stylesheet" href="<?php echo '../../assets/css/style-secciones.css'; ?>">
<link rel="stylesheet" href="<?php echo '../../assets/css/slider.css'; ?>">  
<link rel="stylesheet" href="<?php echo '../../assets/css/comments.css'; ?>"> 

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<main class="main-content pagina-interna">

<?php include("../../includes/navbar.php"); ?>

<!-- SLIDER -->

<section class="slider-container">

<button class="slider-btn prev">❮</button>

<?php $primero = true; ?>

<?php while ($comida = $resultado->fetch_assoc()) { ?>

    <div class="slide <?php echo $primero ? 'active' : ''; ?>">

        <img 
            src="../../<?php echo $comida['IMAGEN']; ?>" 
            alt="<?php echo $comida['NOMBRE']; ?>"
        >

        <div class="slide-info">

            <h2>
                <?php echo $comida['NOMBRE']; ?>
            </h2>

            <p>
                <?php echo $comida['DESCRIPCION']; ?>
            </p>

        </div>

    </div>

    <?php $primero = false; ?>

<?php } ?>

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
