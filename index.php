<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>

    <link rel="stylesheet" href="assets/css/componentes.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/style-secciones.css">
    <link rel="stylesheet" href="assets/css/var.css">

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>

<!-- NAVBAR -->
<?php include("includes/navbar.php"); ?> <style> #navbar { background: transparent; } </style>

<!-- SECCION PRINCIPAL -->
<section id="seccion-principal">

    <div class="contenido-principal">

        <h1>DESCUBRE SALTO</h1>

        <p class="texto-salto">
            Vení a conocer Salto, una ciudad donde la naturaleza,
            las termas y la tranquilidad te esperan para vivir
            momentos únicos e inolvidables.
            <span id="textoExtra">
                Entre ellos se encuentran las termas, el Parque del Lago, el Museo del Hombre y la Tecnología y diversos eventos culturales durante todo el año.
            </span>
        </p>

    <button id="btnMostrar" class="btn-mostrar">
        Mostrar más
    </button>

    </div>

</section>

<!-- SEGUNDA SECCION -->
<section id="segunda-seccion">

    <div class="titulo-seccion">
        <h2>Explora Salto</h2>
    </div>

    <div class="cards-container">

        <div class="card">

            <img src="assets/img/termas.jpeg" alt="Termas">

            <h3>Termas</h3>

            <p>
                Relajate en las mejores aguas termales y disfrutá momentos únicos.
            </p>

            <a href="pages/lugares/termas.php" class="card-btn">
                Ver más →
            </a>

        </div>

        <div class="card">

            <img src="assets/img/fuente-naturaleza.jpeg" alt="Naturaleza">

            <h3>Naturaleza</h3>

            <p>
                Descubrí paisajes increíbles, parques y actividades al aire libre.
            </p>

            <a href="pages/lugares/paisajes.php" class="card-btn">
                Ver más →
            </a>

        </div>

        <div class="card">

            <img src="assets/img/trouville.jpeg" alt="Gastronomía">

            <h3>Gastronomía</h3>

            <p>
                Probá sabores locales y experiencias gastronómicas inolvidables.
            </p>

            <a href="pages/gastronomia/locales-top.php" class="card-btn">
                Ver más →
            </a>

        </div>

    </div>

</section>

<!-- SOBRE GOSALTO -->

<section id="empresa">

    <div class="empresa-container">

        <div class="empresa-texto">

            <span class="empresa-subtitulo">
                ¿Quiénes somos?
            </span>

            <h2>GoSalto</h2>

            <p>
                GoSalto es una plataforma turística creada para conectar a visitantes y residentes con los principales atractivos de la ciudad de Salto. Reunimos en un solo lugar información sobre destinos, gastronomía, eventos y experiencias para que descubrir la ciudad sea más fácil y agradable.
            </p>

            <p>
                Nuestro objetivo es impulsar el turismo local mediante una plataforma moderna, intuitiva y accesible, ofreciendo recomendaciones, información útil y recursos que ayuden a planificar cada visita de la mejor manera.
            </p>

            <a href="#seccion-principal" class="empresa-btn">
                Descubrir Salto
            </a>

        </div>

        <div class="empresa-img">

            <img src="assets/img/LogoGoSalto.png" alt="GoSalto">

        </div>

    </div>

</section>

<!-- SECCION COMENTARIOS -->
<?php include("includes/comments.php"); ?>

<!-- FOOTER -->
<?php include("includes/footer.php"); ?>

<!-- CHAT PERSONAS -->
<?php include("includes/chat-widget.php"); ?>

<script src="assets/js/script.js"></script>

</body>
</html>