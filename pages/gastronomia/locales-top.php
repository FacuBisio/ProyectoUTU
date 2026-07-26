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

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        #mapaLocalesTop {
            width: 100%;
            height: 450px;
            border-radius: 8px;
            margin-top: 15px;
            z-index: 1;
        }
    </style>
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

<section class="mapa-seccion">
    <h2>Ubicación de locales y resto-bars destacados</h2>
    <p>
        Encuentra los lugares más destacados para salir a cenar, tomar tragos o disfrutar de cerveza artesanal en Salto.
    </p>

    <div id="mapaLocalesTop"></div>
</section>

<?php include("../../includes/comments.php"); ?>

</main>

<?php include("../../includes/footer.php"); ?>

<?php include("../../includes/chat-widget.php"); ?>

<script src="../../assets/js/slider.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // 1. Inicializar el mapa centrado en Salto
    const mapa = L.map('mapaLocalesTop').setView([-31.3875, -57.9620], 14);

    // 2. Capa de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap'
    }).addTo(mapa);

    // 3. Datos de los locales destacables
    const localesTop = [
      {
        nombre: "Juan Perez Resto Bar",
        coords: [-31.3868, -57.9635],
        imagen: "../../assets/img/mdelhombre.jpg",
        descripcion: "Resto-bar con música en vivo, excelente gastronomía y cócteles."
      },
      {
        nombre: "Paddock Bar",
        coords: [-31.3880, -57.9610],
        imagen: "../../assets/img/mbellasartes.jpg",
        descripcion: "Bar moderno con ambientación especial, coctelería de autor y picadas."
      },
      {
        nombre: "La Trinchera",
        coords: [-31.3895, -57.9640],
        imagen: "../../assets/img/mhoracioquiroga.jpg",
        descripcion: "Cervecería artesanal con variedad de canillas y gastronomía urbana."
      }
    ];

    // 4. Recorrer la lista y añadir marcadores con popups
    localesTop.forEach(local => {
      const [lat, lng] = local.coords;
      
      const popupContent = `
        <h3>${local.nombre}</h3>
        <img src="${local.imagen}" width="220" alt="${local.nombre}">
        <p>${local.descripcion}</p>
        <a href="https://www.google.com/maps/search/?api=1&query=${lat},${lng}" target="_blank">
          📍 Cómo llegar
        </a>
      `;

      L.marker(local.coords)
        .addTo(mapa)
        .bindPopup(popupContent);
    });

    // 5. Ajustar renderizado del mapa
    setTimeout(() => {
      mapa.invalidateSize();
    }, 200);
  });
</script>

</body>
</html>