<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#123c30">
    <meta name="description" content="Conoce SIGTUR, la plataforma para explorar lugares, eventos y propuestas gastronómicas de Salto, Uruguay.">
    <title>Acerca de SIGTUR | GoSalto</title>
    <link rel="stylesheet" href="../assets/css/var.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/componentes.css">
    <link rel="stylesheet" href="../assets/css/sobre-sigtur.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
    <?php include("../includes/navbar.php"); ?>

    <main class="acerca-pagina">
        <section class="acerca-portada" aria-labelledby="acerca-titulo">
            <div class="acerca-portada-contenido">
                <span class="acerca-etiqueta"><i class="fa-solid fa-location-dot" aria-hidden="true"></i> Salto, Uruguay</span>
                <h1 id="acerca-titulo">Descubre Salto con <span>SIGTUR</span></h1>
                <p>
                    Un espacio digital para conocer los lugares, las actividades y los sabores que forman parte de la ciudad.
                </p>
                <a class="acerca-boton" href="<?= htmlspecialchars(url('index.php#segunda-seccion'), ENT_QUOTES, 'UTF-8') ?>">
                    Explorar Salto <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                </a>
            </div>
        </section>

        <section class="acerca-contenido" aria-labelledby="que-es-sigtur">
            <div class="acerca-introduccion">
                <span class="acerca-subtitulo">Conoce la plataforma</span>
                <h2 id="que-es-sigtur">¿Qué es SIGTUR?</h2>
                <p>
                    SIGTUR es una plataforma web de información turística sobre Salto, Uruguay. Reúne en un solo lugar datos y propuestas de interés para que residentes y visitantes puedan descubrir mejor la ciudad.
                </p>
                <p>
                    Desde sus distintas secciones es posible explorar lugares, consultar eventos y encontrar opciones de gastronomía. También incluye herramientas de búsqueda y espacios para compartir comentarios y experiencias.
                </p>
            </div>

            <div class="acerca-beneficios" aria-label="Qué puedes hacer en SIGTUR">
                <article class="acerca-beneficio">
                    <span class="acerca-beneficio-icono" aria-hidden="true"><i class="fa-solid fa-map-location-dot"></i></span>
                    <h3>Explorar lugares</h3>
                    <p>Encuentra sitios naturales, espacios culturales, patrimonio y otras propuestas para conocer Salto.</p>
                </article>
                <article class="acerca-beneficio">
                    <span class="acerca-beneficio-icono" aria-hidden="true"><i class="fa-regular fa-calendar-days"></i></span>
                    <h3>Descubrir eventos</h3>
                    <p>Consulta actividades y publicaciones de la comunidad para conocer lo que sucede en la ciudad.</p>
                </article>
                <article class="acerca-beneficio">
                    <span class="acerca-beneficio-icono" aria-hidden="true"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <h3>Encontrar opciones</h3>
                    <p>Busca información de forma sencilla y descubre propuestas gastronómicas y experiencias locales.</p>
                </article>
            </div>

            <aside class="acerca-proposito">
                <span class="acerca-proposito-icono" aria-hidden="true"><i class="fa-solid fa-compass"></i></span>
                <div>
                    <h2>¿Para qué sirve?</h2>
                    <p>
                        SIGTUR facilita el acceso a información turística de Salto desde un único sitio. Su propósito es ayudar a las personas a conocer qué ofrece la ciudad y encontrar propuestas que se adapten a sus intereses.
                    </p>
                </div>
            </aside>
        </section>
    </main>

    <?php include("../includes/footer.php"); ?>
</body>
</html>
