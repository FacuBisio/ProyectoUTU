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
    <link rel="stylesheet" href="assets/css/comments.css?v=3">

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
        </p>

        <a href="#segunda-seccion" class="empresa-btn">
            Mostrar más
        </a>

    </div>

</section>

<!-- SEGUNDA SECCION -->
<section id="segunda-seccion">

    <div class="titulo-seccion">
        <h2>Explora Salto</h2>
    </div>

    <div class="cards-container">

        <a class="card" href="pages/lugares/termas.php">

            <img src="assets/img/termas.jpeg" alt="Termas">

            <h3>Termas</h3>

            <p>
                Relajate en las mejores aguas termales y disfrutá momentos únicos.
            </p>

        </a>

        <a class="card" href="pages/lugares/paisajes.php">

            <img src="assets/img/fuente-naturaleza.jpeg" alt="Naturaleza">

            <h3>Naturaleza</h3>

            <p>
                Descubrí paisajes increíbles, parques y actividades al aire libre.
            </p>

        </a>

        <a class="card" href="pages/gastronomia/locales-top.php">

            <img src="assets/img/trouville.jpeg" alt="Gastronomía">

            <h3>Gastronomía</h3>

            <p>
                Probá sabores locales y experiencias gastronómicas inolvidables.
            </p>

        </a>

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
                GoSalto es una empresa turística ficticia nacida en Salto, impulsada por un equipo apasionado por la hospitalidad y la identidad del litoral. Diseñamos recorridos y experiencias para que cada visitante pueda conocer la ciudad desde su naturaleza, su cultura y su vida cotidiana.
            </p>

            <p>
                Trabajamos junto a emprendimientos y comunidades locales para promover un turismo cercano, responsable y sostenible, creando oportunidades para que los atractivos y sabores de Salto sean protagonistas durante todo el año.
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

</body>
</html>