<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paisajes</title>
    
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
        #mapaPaisajes {
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
        <img src="../../assets/img/costaneranorte.jpeg" alt="Costanera Norte">
        <div class="slide-info">
            <h2>Costanera Norte</h2>
            <p>
                Un lugar ideal para disfrutar de la naturaleza, 
                realizar caminatas y apreciar la belleza del paisaje.
            </p>
        </div>
    </div>

    <div class="slide">
        <img src="../../assets/img/cuevas.png" alt="Cuevas de San Antonio">
        <div class="slide-info">
            <h2>Cuevas de San Antonio</h2>
            <p>
                Un lugar natural y fascinante, 
                con cuevas que albergan una gran variedad de formaciones geológicas y
                una rica biodiversidad.
            </p>
        </div>
    </div>

    <div class="slide">
        <img src="../../assets/img/roosevelt.png" alt="Plaza Roosevelt">
        <div class="slide-info">
            <h2>Plaza Roosevelt</h2>
            <p>
                Un espacio público que ofrece un entorno ideal para relajarse, 
                socializar y disfrutar de la vida urbana.
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
    <h2>Ubicación de los miradores y paisajes</h2>
    <p>
        Explora los mejores entornos naturales y paseos al aire libre en Salto.
    </p>

    <div id="mapaPaisajes"></div>
</section>

<?php include("../../includes/comments.php"); ?>

</main>

<?php include("../../includes/footer.php"); ?>

<?php include("../../includes/chat-widget.php"); ?>

<script src="../../assets/js/slider.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // 1. Inicializar el mapa centrado en Salto
    const mapa = L.map('mapaPaisajes').setView([-31.3780, -57.9650], 13);

    // 2. Capa de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap'
    }).addTo(mapa);

    // 3. Datos de los paisajes y paseos
    const paisajes = [
      {
        nombre: "Costanera Norte",
        coords: [-31.3735, -57.9690],
        imagen: "../../assets/img/costaneranorte.jpeg",
        descripcion: "Paseo junto al río, ideal para caminatas y contemplar atardeceres."
      },
      {
        nombre: "Cuevas de San Antonio",
        coords: [-31.3520, -57.9730],
        imagen: "../../assets/img/cuevas.png",
        descripcion: "Formaciones geológicas y naturaleza sobre el Río Uruguay."
      },
      {
        nombre: "Plaza Roosevelt",
        coords: [-31.3885, -57.9685],
        imagen: "../../assets/img/roosevelt.png",
        descripcion: "Plaza y mirador natural con vistas al puerto y al río."
      }
    ];

    // 4. Recorrer los puntos e incluirlos en el mapa
    paisajes.forEach(punto => {
      const [lat, lng] = punto.coords;
      
      const popupContent = `
        <h3>${punto.nombre}</h3>
        <img src="${punto.imagen}" width="220" alt="${punto.nombre}">
        <p>${punto.descripcion}</p>
        <a href="https://www.google.com/maps/search/?api=1&query=${lat},${lng}" target="_blank">
          📍 Cómo llegar
        </a>
      `;

      L.marker(punto.coords)
        .addTo(mapa)
        .bindPopup(popupContent);
    });

    // 5. Ajuste de renderizado
    setTimeout(() => {
      mapa.invalidateSize();
    }, 200);
  });
</script>

</body>
</html>