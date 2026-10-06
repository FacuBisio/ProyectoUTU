<?php
$mapaCategorias = [7, 8, 9, 10];
$mapaTitulo = "Ubicación de locales gastronómicos destacados";
$mapaDescripcion = "Explorá restaurantes, cafeterías, heladerías y opciones al paso de Salto.";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Locales Top</title>
    
    <link rel="stylesheet" href="<?php echo '../../assets/css/var.css'; ?>">
    <link rel="stylesheet" href="<?php echo '../../assets/css/styles.css'; ?>">
    <link rel="stylesheet" href="<?php echo '../../assets/css/componentes.css'; ?>">
    <link rel="stylesheet" href="<?php echo '../../assets/css/style-secciones.css'; ?>">
    <link rel="stylesheet" href="<?php echo '../../assets/css/slider.css'; ?>"> 
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

    <button class="slider-btn next">❯</button>

    <div class="slider-dots">
        <span class="dot active"></span>
        <span class="dot"></span>
        <span class="dot"></span>
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