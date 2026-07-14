<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patrimonio Histórico</title>
    
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

        <img src="../../assets/img/teatro.jpeg" alt="Teatro Larrañaga">

        <div class="slide-info">
            <h2>Teatro Larrañaga</h2>
            <p>
                Un espacio cultural emblemático que ofrece una variedad de espectáculos artísticos, 
                desde teatro y música hasta danza y eventos comunitarios.
            </p>
        </div>

    </div>

    <div class="slide">

        <img src="../../assets/img/catedral.jpg" alt="Catedral Basílica San Juan Bautista">

        <div class="slide-info">
            <h2>Catedral Basílica San Juan Bautista</h2>
            <p>
                Un majestuoso edificio religioso que combina arquitectura histórica y espiritualidad, 
                siendo un punto de referencia para la comunidad y los visitantes.
            </p>
        </div>

    </div>

    <div class="slide">

        <img src="../../assets/img/granhotel.jpg" alt="Gran Hotel Concordia">

        <div class="slide-info">
            <h2>Gran Hotel Concordia</h2>
            <p>
                Un edificio histórico que combina arquitectura clásica y moderna, ofreciendo a los visitantes 
                una experiencia única de alojamiento y eventos en un entorno elegante y sofisticado.
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