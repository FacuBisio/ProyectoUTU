<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#123c30">
    <meta name="description" content="Conoce Salto, Uruguay: sus termas, paisajes junto al río, cultura, historia y propuestas para disfrutar durante todo el año.">
    <title>Salto y su turismo | GoSalto</title>
    <link rel="stylesheet" href="../assets/css/var.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/componentes.css">
    <link rel="stylesheet" href="../assets/css/sobre-sigtur.css">
    <link rel="stylesheet" href="../assets/css/turismo-salto.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
    <?php include("../includes/navbar.php"); ?>

    <main class="acerca-pagina turismo-salto">
        <section class="acerca-portada turismo-portada" aria-labelledby="turismo-titulo">
            <div class="acerca-portada-contenido">
                <span class="acerca-etiqueta"><i class="fa-solid fa-location-dot" aria-hidden="true"></i> Noroeste de Uruguay</span>
                <h1 id="turismo-titulo">Salto, naturaleza, cultura y <span>bienestar</span></h1>
                <p>
                    A orillas del río Uruguay, Salto combina espacios naturales, aguas termales, patrimonio y una vida local que invita a descubrir la ciudad a tu propio ritmo.
                </p>
                <a class="acerca-boton" href="<?= htmlspecialchars(url('pages/lugares/termas.php'), ENT_QUOTES, 'UTF-8') ?>">
                    Explorar las termas <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                </a>
            </div>
        </section>

        <section class="acerca-contenido turismo-contenido" aria-labelledby="turismo-conoce-titulo">
            <div class="acerca-introduccion">
                <span class="acerca-subtitulo">Una ciudad para descubrir</span>
                <h2 id="turismo-conoce-titulo">Conoce Salto</h2>
                <p>
                    Salto es una ciudad del litoral uruguayo, reconocida por su relación con el río Uruguay y por la diversidad de sus atractivos. Su entorno reúne paisajes ribereños, espacios verdes, arquitectura, propuestas culturales y una oferta turística que se disfruta durante todo el año.
                </p>
                <p>
                    Tanto si buscas descansar como recorrer la ciudad, disfrutar de la naturaleza o conocer su historia, encontrarás distintas formas de vivir Salto y acercarte a su identidad.
                </p>
            </div>

            <div class="turismo-categorias">
                <article class="turismo-categoria">
                    <span class="turismo-categoria-icono" aria-hidden="true"><i class="fa-solid fa-water"></i></span>
                    <div>
                        <span class="turismo-categoria-etiqueta">Descanso y bienestar</span>
                        <h3>Termas</h3>
                        <p>Las termas son uno de los principales atractivos de la región. Daymán y Arapey ofrecen piscinas de aguas termales y espacios pensados para descansar y disfrutar en familia.</p>
                        <a href="<?= htmlspecialchars(url('pages/lugares/termas.php'), ENT_QUOTES, 'UTF-8') ?>">Conocer las termas <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
                    </div>
                </article>

                <article class="turismo-categoria">
                    <span class="turismo-categoria-icono" aria-hidden="true"><i class="fa-solid fa-leaf"></i></span>
                    <div>
                        <span class="turismo-categoria-etiqueta">Aire libre</span>
                        <h3>Naturaleza y río</h3>
                        <p>La costa del río Uruguay, los parques y los paisajes de la zona permiten pasear, disfrutar del aire libre y contemplar distintos rincones del litoral.</p>
                        <a href="<?= htmlspecialchars(url('pages/lugares/paisajes.php'), ENT_QUOTES, 'UTF-8') ?>">Explorar paisajes <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
                    </div>
                </article>

                <article class="turismo-categoria">
                    <span class="turismo-categoria-icono" aria-hidden="true"><i class="fa-solid fa-landmark"></i></span>
                    <div>
                        <span class="turismo-categoria-etiqueta">Identidad local</span>
                        <h3>Historia y cultura</h3>
                        <p>La arquitectura, los museos y los espacios culturales cuentan parte de la historia de Salto y ayudan a conocer las tradiciones de la región.</p>
                        <a href="<?= htmlspecialchars(url('pages/lugares/patrimonio.php'), ENT_QUOTES, 'UTF-8') ?>">Descubrir el patrimonio <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
                    </div>
                </article>

                <article class="turismo-categoria">
                    <span class="turismo-categoria-icono" aria-hidden="true"><i class="fa-solid fa-utensils"></i></span>
                    <div>
                        <span class="turismo-categoria-etiqueta">Sabores de la ciudad</span>
                        <h3>Gastronomía y vida local</h3>
                        <p>Restaurantes, cafeterías y comercios locales ofrecen distintas opciones para hacer una pausa y disfrutar de los sabores y el ambiente de la ciudad.</p>
                        <a href="<?= htmlspecialchars(url('pages/gastronomia/locales-top.php'), ENT_QUOTES, 'UTF-8') ?>">Ver opciones gastronómicas <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
                    </div>
                </article>
            </div>

            <aside class="turismo-cierre">
                <span class="turismo-cierre-icono" aria-hidden="true"><i class="fa-regular fa-compass"></i></span>
                <div>
                    <h2>Una visita a tu manera</h2>
                    <p>
                        Puedes organizar una escapada de descanso, recorrer espacios naturales y culturales o combinar diferentes propuestas. SIGTUR reúne información para ayudarte a descubrir qué hacer y planificar tu visita a Salto.
                    </p>
                </div>
                <a href="<?= htmlspecialchars(url('pages/eventos/eventos.php'), ENT_QUOTES, 'UTF-8') ?>">
                    Ver eventos <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                </a>
            </aside>
        </section>
    </main>

    <?php include("../includes/footer.php"); ?>
</body>
</html>
