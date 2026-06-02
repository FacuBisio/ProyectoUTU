<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parques</title>

    <link rel="stylesheet" href="assets/css/style-secciones.css">
    <link rel="stylesheet" href="assets/css/var.css">
    <link rel="stylesheet" href="assets/css/slider.css">

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>

<!-- NAVBAR -->
<section id="navbar">

    <div class="navbar-container">

        <div class="logo">
            <h1>GoSalto</h1>
        </div>

        <div class="nav-links">

                <a class="link-btn" href="index.php">
                Inicio
                </a>

            <div class="dropdown">

                <button class="link-btn">
                    Lugares ▾
                </button>

                <div class="menu-desplegable">

                    <div>
                        <h3>Explorar</h3>

                        <a href="parques.php">Parques</a>
                        <a href="#">Naturaleza</a>
                    </div>

                    <div>
                        <h3>Cultura</h3>

                        <a href="#">Museos</a>
                        <a href="#">Historia</a>
                    </div>

                    <div>
                        <h3>Experiencias</h3>

                        <a href="#">Automovilismo</a>
                        <a href="#">Relax</a>
                        <a href="#">Termas</a>
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

        <img src="assets/img/parqueharriague.jpg" alt="Parque 1">

        <div class="slide-info">
            <h2>Parque Harriague</h2>
            <p>
                Uno de los espacios verdes más importantes de Salto.
                Ideal para caminatas, deporte y actividades familiares.
            </p>
        </div>

    </div>

    <div class="slide">

        <img src="assets/img/parquesolari.png" alt="Parque 2">

        <div class="slide-info">
            <h2>Parque Solari</h2>
            <p>
                Un amplio espacio verde ideal para disfrutar de la naturaleza, realizar actividades recreativas y compartir momentos en familia en un entorno tranquilo y agradable.
            </p>
        </div>

    </div>

    <div class="slide">

        <img src="assets/img/parqueindigena.jpg" alt="Parque 3">

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

<!-- FOOTER -->
<footer id="footer">

    <div class="footer-container">

        <div class="footer-logo">

            <h2>GoSalto</h2>

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
<script>
const slides = document.querySelectorAll(".slide");
const dots = document.querySelectorAll(".dot");

let current = 0;

function showSlide(index){

    slides.forEach(slide => slide.classList.remove("active"));
    dots.forEach(dot => dot.classList.remove("active"));

    slides[index].classList.add("active");
    dots[index].classList.add("active");

    current = index;
}

document.querySelector(".next").addEventListener("click", () => {

    current++;

    if(current >= slides.length){
        current = 0;
    }

    showSlide(current);
});

document.querySelector(".prev").addEventListener("click", () => {

    current--;

    if(current < 0){
        current = slides.length - 1;
    }

    showSlide(current);
});

dots.forEach((dot,index)=>{

    dot.addEventListener("click",()=>{

        showSlide(index);

    });

});

setInterval(()=>{

    current++;

    if(current >= slides.length){
        current = 0;
    }

    showSlide(current);

},5000);
</script>
</body>
</html>