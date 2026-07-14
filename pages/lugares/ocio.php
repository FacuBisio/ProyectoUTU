<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ocio y Vida Nocturna</title>
    
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

        <img src="../../assets/img/mdelhombre.jpg" alt="Calle Uruguay">

        <div class="slide-info">
            <h2>Calle Uruguay</h2>
            <p>
                Una de las calles más emblemáticas de la ciudad, llena de historia y cultura. 
                Es un lugar ideal para pasear, disfrutar de la arquitectura y 
                conocer la vida nocturna de la ciudad.
            </p>
        </div>

    </div>

    <div class="slide">

        <img src="../../assets/img/mbellasartes.jpg" alt="Salto Shopping">

        <div class="slide-info">
            <h2>Salto Shopping</h2>
            <p>
                Un centro comercial moderno con una amplia variedad de tiendas y servicios.
                Queda a pocos minutos del centro de la ciudad y es un lugar ideal para disfrutar de compras 
                y entretenimiento.
            </p>
        </div>

    </div>

    <div class="slide">

        <img src="../../assets/img/mhoracioquiroga.jpg" alt="Cine Sarandi">

        <div class="slide-info">
            <h2>Cine Sarandi</h2>
            <p>
                El Cine Sarandi es un lugar emblemático para los amantes del cine, 
                ofreciendo una experiencia única con una programación variada de películas nacionales 
                e internacionales.
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