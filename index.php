<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>

    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/var.css">

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>

<!-- NAVBAR -->
<section id="navbar">

    <div class="navbar-container">

        <div class="logo">
            <h1>SIGTUR</h1>
        </div>

        <div class="nav-links">

                <a class="link-btn" href="../../index.php">
                Inicio
                </a>

            <div class="dropdown">

                <button class="link-btn">
                    Lugares ▾
                </button>

                <div class="menu-desplegable">

                    <div>
                        <h3>Naturaleza</h3>

                        <a href="assets/php/parques.php">Parques</a>
                        <a href="assets/php/paisajes.php">Paisajes y Espacios Naturales</a>
                    </div>

                    <div>
                        <h3>Historia y Cultura</h3>

                        <a href="assets/php/patrimonio.php">Patrimonio Histórico</a>
                        <a href="assets/php/museos.php">Museos y Arte</a>
                    </div>

                    <div>
                        <h3>Turismo y Experiencias</h3>

                        <a href="assets/php/termas.php">Termas y Bienestar</a>
                        <a href="assets/php/ocio.php">Ocio y Vida Nocturna</a>

                    </div>

                </div>

            </div>

            <a class="link-btn" href="assets/php/eventos.php">
                Eventos
            </a>

            <div class="dropdown">

                <button class="link-btn">
                    Gastronomía ▾
                </button>

                <div class="menu-desplegable">

                    <div>
                        <h3>Comer</h3>

                        <a href="assets/php/restaurantes.php">Restaurantes</a>
                        <a href="assets/php/comida-rapida.php">Comida Rápida</a>
                    </div>

                    <div>
                        <h3>Postres</h3>

                        <a href="assets/php/heladerias.php">Heladerías</a>
                        <a href="assets/php/cafeterias.php">Cafeterías</a>
                    </div>

                    <div>
                        <h3>Destacados</h3>

                        <a href="assets/php/locales-top.php">Locales Top</a>
                        <a href="assets/php/recomendados.php">Recomendados</a>
                    </div>

                </div>

            </div>

        </div>

        <div class="nav-extra">

            <div class="buscador">

                <input type="text" placeholder="Buscar...">

                <button>⌕</button>

            </div>

            <a href="assets/php/login.php" class="user">
    <i class="fa-regular fa-user"></i>
</a>

        </div>

    </div>

</section>

<!-- SECCION PRINCIPAL -->
<section id="seccion-principal">

    <div class="contenido-principal">

        <h1>DESCUBRE SALTO</h1>

        <p>
            Vení a conocer Salto, una ciudad donde la naturaleza,
            las termas y la tranquilidad te esperan para vivir
            momentos únicos e inolvidables.
        </p>

        <a href="#" class="btn-principal">
            LEER MAS →
        </a>

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

            <a href="#" class="card-btn">
                Ver más →
            </a>

        </div>

        <div class="card">

            <img src="assets/img/fuente-naturaleza.jpeg" alt="Naturaleza">

            <h3>Naturaleza</h3>

            <p>
                Descubrí paisajes increíbles, parques y actividades al aire libre.
            </p>

            <a href="#" class="card-btn">
                Ver más →
            </a>

        </div>

        <div class="card">

            <img src="assets/img/trouville.jpeg" alt="Gastronomía">

            <h3>Gastronomía</h3>

            <p>
                Probá sabores locales y experiencias gastronómicas inolvidables.
            </p>

            <a href="#" class="card-btn">
                Ver más →
            </a>

        </div>

    </div>

</section>

<!-- SECCION COMENTARIOS -->
<section id="comentarios">

    <div class="titulo-seccion">
        <h2>Lo que dicen los visitantes</h2>
    </div>

    <div class="comentarios-container">

        <div class="comentario-card">

            <p class="comentario-texto">
                “Las termas fueron una experiencia increíble, súper recomendable.”
            </p>

            <div class="comentario-user">
                <h4>Lucía Fernández</h4>
                <span>Argentina</span>
            </div>

        </div>

        <div class="comentario-card">

            <p class="comentario-texto">
                “Muy linda ciudad, tranquila y con lugares hermosos para recorrer.”
            </p>

            <div class="comentario-user">
                <h4>Martín Silva</h4>
                <span>Uruguay</span>
            </div>

        </div>

        <div class="comentario-card">

            <p class="comentario-texto">
                “La gastronomía me sorprendió muchísimo, volvería sin dudas.”
            </p>

            <div class="comentario-user">
                <h4>Camila Rodríguez</h4>
                <span>Chile</span>
            </div>

        </div>

    </div>

</section>

<!-- =========================
        SOBRE GOSALTO
========================= -->

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

            <a href="#lugares" class="empresa-btn">
                Descubrir Salto
            </a>

        </div>

        <div class="empresa-img">

            <img src="assets/img/LogoGoSalto.png" alt="GoSalto">

        </div>

    </div>

</section>

<!-- FOOTER -->
<footer id="footer">

    <div class="footer-container">

        <div class="footer-logo">

            <h2>SIGTUR</h2>

            <p>
                Descubrí los mejores lugares, experiencias y sabores de Salto.
            </p>

        </div>

        <div class="footer-links">

            <div>

                <h3>Navegación</h3>

                <a href="#">Inicio</a>
                <a href="#">Lugares</a>
                <a href="#">Eventos</a>
                <a href="#">Gastronomía</a>

            </div>

            <div>

                <h3>Explorar</h3>

                <a href="#">Termas</a>
                <a href="#">Naturaleza</a>
                <a href="#">Cultura</a>
                <a href="#">Parques</a>

            </div>

        </div>

    </div>

    <div class="footer-copy">
        <p>© 2026 GoSalto — Todos los derechos reservados.</p>
    </div>

</footer>

<!-- CHAT PERSONAS -->
<div class="chat-widget">

    <button class="chat-btn">

        <i class="fa-regular fa-message"></i>

        <div class="chat-notification"></div>

    </button>

</div>

<script src="assets/js/script.js"></script>

</body>
</html>