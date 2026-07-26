<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termas</title>
    
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
        #mapaTermas {
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
        <img src="../../assets/img/termas.jpeg" alt="Termas del Daymán">
        <div class="slide-info">
            <h2>Termas del Daymán</h2>
            <p>
                Un complejo termal que ofrece una experiencia relajante y terapéutica, 
                con piscinas de aguas termales, spa y servicios de bienestar.
            </p>
        </div>
    </div>

    <div class="slide">
        <img src="../../assets/img/arapey.jpg" alt="Termas del Arapey">
        <div class="slide-info">
            <h2>Termas del Arapey</h2>
            <p>
                Un complejo termal que ofrece una experiencia relajante y terapéutica, 
                con piscinas de aguas termales, spa y servicios de bienestar.
            </p>
        </div>
    </div>

    <button class="slider-btn next">❯</button>

    <div class="slider-dots">
        <span class="dot active"></span>
        <span class="dot"></span>
    </div>

</section>

<section class="mapa-seccion">
    <h2>Ubicación de complejos termales</h2>
    <p>
        Descubre la localización de los principales centros termales y parques acuáticos del departamento de Salto.
    </p>

    <div id="mapaTermas"></div>
</section>

<?php include("../../includes/comments.php"); ?>

</main>

<?php include("../../includes/footer.php"); ?>

<?php include("../../includes/chat-widget.php"); ?>

<script src="../../assets/js/slider.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // 1. Inicializar el mapa centrado de forma de abarcar Daymán y Arapey
    const mapa = L.map('mapaTermas').setView([-31.1000, -57.8000], 9);

    // 2. Capa de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap'
    }).addTo(mapa);

    // 3. Datos de los complejos termales
    const termas = [
      {
        nombre: "Termas del Daymán",
        coords: [-31.4678, -57.9083],
        imagen: "../../assets/img/termas.jpeg",
        descripcion: "Complejo termal con múltiples piscinas, spa y parque acuático a pocos minutos de Salto."
      },
      {
        nombre: "Termas del Arapey",
        coords: [-30.9340, -57.5186],
        imagen: "../../assets/img/arapey.jpg",
        descripcion: "El centro termal más antiguo del país, rodeado de frondosa vegetación y hoteles de primer nivel."
      }
    ];

    // 4. Recorrer los puntos e incluirlos en el mapa
    termas.forEach(terma => {
      const [lat, lng] = terma.coords;
      
      const popupContent = `
        <h3>${terma.nombre}</h3>
        <img src="${terma.imagen}" width="220" alt="${terma.nombre}">
        <p>${terma.descripcion}</p>
        <a href="https://www.google.com/maps/search/?api=1&query=${lat},${lng}" target="_blank">
          📍 Cómo llegar
        </a>
      `;

      L.marker(terma.coords)
        .addTo(mapa)
        .bindPopup(popupContent);
    });

    // 5. Ajustar renderizado de Leaflet
    setTimeout(() => {
      mapa.invalidateSize();
    }, 200);
  });
</script>

</body>
</html>