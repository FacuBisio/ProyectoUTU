<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos</title>
    
    <link rel="stylesheet" href="<?php echo '../../assets/css/var.css'; ?>">
    <link rel="stylesheet" href="<?php echo '../../assets/css/styles.css'; ?>">
    <link rel="stylesheet" href="<?php echo '../../assets/css/componentes.css'; ?>">
    <link rel="stylesheet" href="<?php echo '../../assets/css/style-secciones.css'; ?>">
    <link rel="stylesheet" href="<?php echo '../../assets/css/slider.css'; ?>"> 

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>

<main>

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

        <img src="../../assets/img/mdelhombre.jpg" alt="Carnaval de Salto">

        <div class="slide-info">
            <h2>Carnaval de Salto</h2>
            <p>
                El Carnaval de Salto es un evento cultural que celebra la tradición y la alegría del carnaval, 
                con desfiles, comparsas, música y baile, ofreciendo a los visitantes una experiencia festiva única.
            </p>
        </div>

    </div>

    <div class="slide">

        <img src="../../assets/img/mbellasartes.jpg" alt="Expo Salto">

        <div class="slide-info">
            <h2>Expo Salto</h2>
            <p>
                Expo Salto es una feria que reúne a emprendedores, empresas y artistas locales, 
                ofreciendo a los visitantes la oportunidad de conocer y adquirir productos y servicios 
                de la región, así como disfrutar de actividades culturales y recreativas.
            </p>
        </div>

    </div>

    <div class="slide">

        <img src="../../assets/img/mhoracioquiroga.jpg" alt="Salon del Vino Fino">

        <div class="slide-info">
            <h2>Salon del Vino Fino</h2>
            <p>
                El Salón del Vino Fino es un evento que celebra la cultura del vino, 
                ofreciendo a los visitantes la oportunidad de degustar y conocer una amplia variedad 
                de vinos finos, así como participar en catas, talleres y actividades relacionadas con el mundo del vino.
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