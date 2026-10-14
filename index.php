<!DOCTYPE html>
<html lang="es-UY">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Descubre qué hacer en Salto, Uruguay: termas, paisajes, parques, cultura, eventos y gastronomía local. Planifica tu próxima visita con GoSalto.">
    <meta name="robots" content="index, follow">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="es_UY">
    <meta property="og:site_name" content="GoSalto">
    <meta property="og:title" content="GoSalto | Turismo, termas y experiencias en Salto, Uruguay">
    <meta property="og:description" content="Explora termas, naturaleza, cultura, eventos y gastronomía de Salto, Uruguay.">
    <title>GoSalto | Turismo, termas y experiencias en Salto, Uruguay</title>

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
            Ven a conocer Salto, una ciudad donde la naturaleza,
            las termas y la tranquilidad te esperan para vivir
            momentos únicos e inolvidables.
        </p>

        <a class="btn-principal" href="pages/salto.php">
            Ver más <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
        </a>

    </div>

</section>

<!-- SEGUNDA SECCION -->
<section id="segunda-seccion">

    <div class="titulo-seccion">
        <h2>Explora Salto</h2>
    </div>

    <div class="cards-container">

        <div class="card" aria-label="Lugares">

            <img src="assets/img/fuente-naturaleza.jpeg" alt="Lugares de Salto">

            <h3>Lugares</h3>

            <p>
                Descubre paisajes, parques, patrimonio y experiencias para recorrer en Salto.
            </p>

        </div>

        <div class="card" aria-label="Eventos">

            <img src="assets/img/Ocio/porco.jpg" alt="Eventos en Salto">

            <h3>Eventos</h3>

            <p>
                Mira lo que pasa en la ciudad y encuentra actividades, reuniones y propuestas para vivir.
            </p>

        </div>

        <div class="card" aria-label="Gastronomía">

            <img src="assets/img/trouville.jpeg" alt="Gastronomía en Salto">

            <h3>Gastronomía</h3>

            <p>
                Prueba sabores locales, cafés, helados y lugares con identidad para disfrutar.
            </p>

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
                GoSalto es una empresa de desarrollo web nacida en Salto, Uruguay, impulsada por un equipo de estudiantes y jóvenes emprendedores con ganas de crear cosas útiles, claras y bien hechas. Diseñamos páginas web y experiencias digitales para que proyectos, negocios y comunidades puedan mostrarse con identidad y presencia en línea.
            </p>

            <p>
                Trabajamos de forma cercana y práctica, combinando diseño, contenido y desarrollo para crear soluciones funcionales, modernas y pensadas para conectar con la gente. En otras palabras: hacemos que la idea de cada emprendimiento se vea bien y funcione en la web.
            </p>

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