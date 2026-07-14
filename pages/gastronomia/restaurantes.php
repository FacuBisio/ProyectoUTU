<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurantes</title>
    
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

        <img src="../../assets/img/mdelhombre.jpg" alt="Museo del Hombre y la Tecnología">

        <div class="slide-info">
            <h2>Museo del Hombre y la Tecnología</h2>
            <p>
                Un espacio dedicado a la preservación y exhibición del patrimonio cultural y 
                tecnológico de la región.
            </p>
        </div>

    </div>

    <div class="slide">

        <img src="../../assets/img/mbellasartes.jpg" alt="Museo Bellas Artes">

        <div class="slide-info">
            <h2>Museo Bellas Artes</h2>
            <p>
               El Museo Bellas Artes alberga una colección de obras de arte que abarca desde la pintura 
               y la escultura hasta la fotografía y el diseño, ofreciendo a los visitantes una 
               experiencia cultural enriquecedora.
            </p>
        </div>

    </div>

    <div class="slide">

        <img src="../../assets/img/mhoracioquiroga.jpg" alt="La Caldera">

        <div class="slide-info">
            <h2>La Caldera</h2>
            <p>
                La Caldera es un restaurante que ofrece una experiencia culinaria única, 
                con platos elaborados con ingredientes frescos y de alta calidad, 
                brindando a sus clientes un momento de disfrute y satisfacción.
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