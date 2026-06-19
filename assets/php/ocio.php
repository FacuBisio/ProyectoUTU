<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ocio y Vida Nocturna</title>

    <link rel="stylesheet" href="../css/style-secciones.css">
    <link rel="stylesheet" href="../css/var.css">
    <link rel="stylesheet" href="../css/slider.css">

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

                        <a href="parques.php">Parques</a>
                        <a href="paisajes.php">Paisajes y Espacios Naturales</a>
                    </div>

                    <div>
                        <h3>Historia y Cultura</h3>

                        <a href="patrimonio.php">Patrimonio Histórico</a>
                        <a href="museos.php">Museos y Arte</a>
                    </div>

                    <div>
                        <h3>Turismo y Experiencias</h3>

                        <a href="termas.php">Termas y Bienestar</a>
                        <a href="ocio.php">Ocio y Vida Nocturna</a>
                        <a href="alojamientos.php">Alojamientos</a>

                    </div>

                </div>

            </div>

            <a class="link-btn" href="eventos.php">
                Eventos
            </a>

            <div class="dropdown">

                <button class="link-btn">
                    Gastronomía ▾
                </button>

                <div class="menu-desplegable">

                    <div>
                        <h3>Comer</h3>

                        <a href="#">Restaurantes</a>
                        <a href="#">Comida Rápida</a>
                    </div>

                    <div>
                        <h3>Postres</h3>

                        <a href="#">Heladerías</a>
                        <a href="#">Cafeterías</a>
                    </div>

                    <div>
                        <h3>Destacados</h3>

                        <a href="#">Locales Top</a>
                        <a href="#">Recomendados</a>
                    </div>

                </div>

            </div>

        </div>

        <div class="nav-extra">

            <div class="buscador">

                <input type="text" placeholder="Buscar...">

                <button>⌕</button>

            </div>

            <div class="user">
                <span><i class="fa-regular fa-user"></i></span>
            </div>

        </div>

    </div>

</section>

<!-- SLIDER -->
<section class="slider-container">

    <button class="slider-btn prev">&#10094;</button>

    <div class="slide active">

        <img src="../img/parqueharriague.jpg" alt="Parque 1">

        <div class="slide-info">
            <h2>Parque Harriague</h2>
            <p>
                Uno de los espacios verdes más importantes de Salto.
                Ideal para caminatas, deporte y actividades familiares.
            </p>
        </div>

    </div>

    <div class="slide">

        <img src="../img/parquesolari.png" alt="Parque 2">

        <div class="slide-info">
            <h2>Parque Solari</h2>
            <p>
                Un amplio espacio verde ideal para disfrutar de la naturaleza, realizar actividades recreativas y compartir momentos en familia en un entorno tranquilo y agradable.
            </p>
        </div>

    </div>

    <div class="slide">

        <img src="../img/parqueindigena.jpg" alt="Parque 3">

        <div class="slide-info">
            <h2>Parque Indígena “Vaymaka Pirú”</h2>
            <p>
                Un espacio verde que combina naturaleza, recreación y homenaje a la cultura indígena, ideal para pasear, hacer deporte y disfrutar del aire libre.
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

<!-- INFO-MAP -->
    <section class="info-map">

    <!-- IZQUIERDA -->
    <div class="info-card">

        <h2>Parque Harriague</h2>

        <p class="descripcion-lugar">
            El Parque Harriague es uno de los espacios verdes más emblemáticos de Salto.
            Cuenta con amplias áreas para caminatas, juegos infantiles, actividades deportivas
            y zonas de descanso rodeadas de naturaleza.
        </p>

        <div class="info-grid">

            <div>
                <i class="fa-solid fa-location-dot"></i>
                <div>
                    <h4>Ubicación</h4>
                    <p>Av. Harriague s/n</p>
                </div>
            </div>

            <div>
                <i class="fa-regular fa-clock"></i>
                <div>
                    <h4>Horario</h4>
                    <p>Abierto las 24 horas</p>
                </div>
            </div>

            <div>
                <i class="fa-solid fa-ticket"></i>
                <div>
                    <h4>Entrada</h4>
                    <p>Gratuita</p>
                </div>
            </div>

            <div>
                <i class="fa-solid fa-square-parking"></i>
                <div>
                    <h4>Estacionamiento</h4>
                    <p>Disponible</p>
                </div>
            </div>

        </div>

        <h3>Opiniones de visitantes</h3>

        <div class="review">

            <img src="https://i.pravatar.cc/45?img=12">

            <div>

                <div class="review-top">

                    <strong>María Gómez</strong>

                    <span>★★★★★</span>

                </div>

                <p>Hermoso parque para disfrutar en familia. Muy cuidado.</p>

            </div>

        </div>

        <div class="review">

            <img src="https://i.pravatar.cc/45?img=20">

            <div>

                <div class="review-top">

                    <strong>Juan Pérez</strong>

                    <span>★★★★☆</span>

                </div>

                <p>Ideal para caminar y hacer ejercicio.</p>

            </div>

        </div>

        <div class="review">

            <img src="https://i.pravatar.cc/45?img=8">

            <div>

                <div class="review-top">

                    <strong>Lucía Fernández</strong>

                    <span>★★★★★</span>

                </div>

                <p>Muy recomendable para pasar la tarde.</p>

            </div>

        </div>

    </div>

    <!-- DERECHA -->

    <div class="map-card">

        <iframe
            src="https://www.google.com/maps?q=Parque+Harriague+Salto+Uruguay&output=embed">
        </iframe>

        <a href="https://maps.google.com/?q=Parque+Harriague+Salto+Uruguay"
        target="_blank">

            Abrir en Google Maps

        </a>

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

<script src="../js/slider.js"></script>

</body>
</html>