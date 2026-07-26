<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos</title>
    
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
        #mapaEventos {
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
        <img src="../../assets/img/mdelhombre.jpg" alt="Carnaval de Salto">
        <div class="slide-info">
            <h2>Carnaval de Salto</h2>
            <p>
                El Carnaval de Salto es un evento cultural que celebra la tradición y la alegría del carnaval, 
                con desfiles, comparsas, música y baile, ofreciendo a los visitantes una experiencia festiva única.
            </p>
        </div>
    </div>

    <div class="slide">
        <img src="../../assets/img/mbellasartes.jpg" alt="Expo Salto">
        <div class="slide-info">
            <h2>Expo Salto</h2>
            <p>
                Expo Salto es una feria que reúne a emprendedores, empresas y artistas locales, 
                ofreciendo a los visitantes la oportunidad de conocer y adquirir productos y servicios 
                de la región, así como disfrutar de actividades culturales y recreativas.
            </p>
        </div>
    </div>

    <div class="slide">
        <img src="../../assets/img/mhoracioquiroga.jpg" alt="Salón del Vino Fino">
        <div class="slide-info">
            <h2>Salón del Vino Fino</h2>
            <p>
                El Salón del Vino Fino es un evento que celebra la cultura del vino, 
                ofreciendo a los visitantes la oportunidad de degustar y conocer una amplia variedad 
                de vinos finos, así como participar en catas, talleres y actividades relacionadas con el mundo del vino.
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
    <h2>Ubicación de eventos y sedes principales</h2>
    <p>
        Encuentra los escenarios y recintos donde se desarrollan las festividades y exposiciones más destacadas de Salto.
    </p>

    <div id="mapaEventos"></div>
</section>

<?php include("../../includes/comments.php"); ?>

</main>

<?php include("../../includes/footer.php"); ?>

<?php include("../../includes/chat-widget.php"); ?>

<script src="../../assets/js/slider.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // 1. Inicializar el mapa centrado en Salto
    const mapa = L.map('mapaEventos').setView([-31.3880, -57.9550], 13);

    // 2. Capa de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap'
    }).addTo(mapa);

    // 3. Datos de los eventos
    const eventos = [
      {
        nombre: "Carnaval de Salto (Av. Uruguay)",
        coords: [-31.3875, -57.9610],
        imagen: "../../assets/img/mdelhombre.jpg",
        descripcion: "Principal avenida de la ciudad donde se lleva a cabo el tradicional desfile de carnaval."
      },
      {
        nombre: "Expo Salto (Predio Ferial)",
        coords: [-31.3950, -57.9350],
        imagen: "../../assets/img/mbellasartes.jpg",
        descripcion: "Predio ferial de la Asociación Agropecuaria de Salto donde se celebra la Expo Salto."
      },
      {
        nombre: "Salón del Vino Fino (Hotel Salto)",
        coords: [-31.3880, -57.9610],
        imagen: "../../assets/img/mhoracioquiroga.jpg",
        descripcion: "Sede tradicional de las catas y presentación de bodegas durante el evento."
      }
    ];

    // 4. Recorrer la lista y añadir marcadores con popups
    eventos.forEach(evento => {
      const [lat, lng] = evento.coords;
      
      const popupContent = `
        <h3>${evento.nombre}</h3>
        <img src="${evento.imagen}" width="220" alt="${evento.nombre}">
        <p>${evento.descripcion}</p>
        <a href="https://www.google.com/maps/search/?api=1&query=${lat},${lng}" target="_blank">
          📍 Cómo llegar
        </a>
      `;

      L.marker(evento.coords)
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