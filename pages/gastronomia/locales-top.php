<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Locales Top</title>
    
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

        <img src="../../assets/img/mdelhombre.jpg" alt="Juan Perez Resto Bar">

        <div class="slide-info">
            <h2>Juan Perez Resto Bar</h2>
            <p>
                Juan Perez Resto Bar es un lugar en el que se combinan la buena comida, 
                la música en vivo y un ambiente acogedor,
                ofreciendo a sus clientes una experiencia única y memorable.
            </p>    
        </div>

    </div>

    <div class="slide">

        <img src="../../assets/img/mbellasartes.jpg" alt="Paddock Bar">

        <div class="slide-info">
            <h2>Paddock Bar</h2>
            <p>
                Paddock Bar es un lugar emblemático que combina la pasión por la música, 
                la gastronomía y la coctelería, ofreciendo a sus clientes una experiencia 
                única en un ambiente acogedor y moderno.
            </p>
        </div>

    </div>

    <div class="slide">

        <img src="../../assets/img/mhoracioquiroga.jpg" alt="La Trinchera">

        <div class="slide-info">
            <h2>La Trinchera</h2>
            <p>
                La Trinchera es una cervecería artesanal que ofrece una experiencia única a 
                los amantes de la cerveza, con una variedad de estilos y 
                sabores que reflejan la creatividad y pasión de sus maestros cerveceros.
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