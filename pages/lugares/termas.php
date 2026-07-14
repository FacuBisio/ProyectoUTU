<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termas</title>
    
    <link rel="stylesheet" href="<?php echo '../../assets/css/var.css'; ?>">
    <link rel="stylesheet" href="<?php echo '../../assets/css/styles.css'; ?>">
    <link rel="stylesheet" href="<?php echo '../../assets/css/componentes.css'; ?>">
    <link rel="stylesheet" href="<?php echo '../../assets/css/style-secciones.css'; ?>">
    <link rel="stylesheet" href="<?php echo '../../assets/css/slider.css'; ?>">  

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>

<main class="main-content pagina-interna">

<!-- NAVBAR -->
<?php include("../../includes/navbar.php"); ?>

<!-- SLIDER -->
<?php
// include("../../includes/slider.php");
?>

<!-- SLIDER -->

<section class="slider-container">

    <button class="slider-btn prev">&#10094;</button>

    <div class="slide active">

        <img src="../../assets/img/termas.jpeg" alt="Termas del Daymán">

        <div class="slide-info">
            <h2>Termas del Daymán</h2>
            <p>
                Un complejo termal que ofrece una experiencia relajante y terapéutica, 
                con piscinas de aguas termales, spa y servicios de bienestar.
            </p>
        </div>

    </div>

    <div class="slide">

        <img src="../../assets/img/arapey.jpg" alt="Termas del Arapey">

        <div class="slide-info">
            <h2>Termas del Arapey</h2>
            <p>
                Un complejo termal que ofrece una experiencia relajante y terapéutica, 
                con piscinas de aguas termales, spa y servicios de bienestar.
            </p>
        </div>

    </div>

    <button class="slider-btn next">&#10095;</button>

    <div class="slider-dots">
        <span class="dot active"></span>
        <span class="dot"></span>
        <span class="dot"></span>
    </div>

</section>

<!-- SECCION COMENTARIOS -->
<?php include("../../includes/comments.php"); ?>

</main>

<!-- FOOTER -->
<?php include("../../includes/footer.php"); ?>

<!-- CHAT PERSONAS -->
<?php include("../../includes/chat-widget.php"); ?>

<script src="../../assets/js/slider.js"></script>

</body>
</html>