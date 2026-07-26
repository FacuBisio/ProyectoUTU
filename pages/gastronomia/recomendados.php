<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recomendados</title>

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
        #mapaRecomendados {
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

<?php include("../../includes/slider.php"); ?> 

<section class="mapa-seccion">
    <h2>Lugares recomendados imperdibles</h2>
    <p>
        Descubre los atracciones más visitadas y mejor valoradas por turistas y locales en Salto.
    </p>

    <div id="mapaRecomendados"></div>
</section>

<?php include("../../includes/comments.php"); ?>

</main>

<?php include("../../includes/footer.php"); ?>

<?php include("../../includes/chat-widget.php"); ?>

<script src="../../assets/js/slider.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // 1. Inicializar el mapa centrado en Salto
    const mapa = L.map('mapaRecomendados').setView([-31.4000, -57.9400], 12);

    // 2. Capa de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap'
    }).addTo(mapa);

    // 3. Datos de los lugares recomendados
    const recomendados = [
      {
        nombre: "Termas del Daymán",
        coords: [-31.4678, -57.9083],
        imagen: "../../assets/img/termas.jpeg",
        descripcion: "El complejo termal más icónico para relajarse y disfrutar del bienestar."
      },
      {
        nombre: "Teatro Larrañaga",
        coords: [-31.3887, -57.9631],
        imagen: "../../assets/img/teatro.jpeg",
        descripcion: "Una de las joyas arquitectónicas e históricas más destacadas de la ciudad."
      },
      {
        nombre: "Costanera Norte",
        coords: [-31.3735, -57.9690],
        imagen: "../../assets/img/costaneranorte.jpeg",
        descripcion: "Espacio natural imperdible para paseos, atardeceres y recreación al aire libre."
      }
    ];

    // 4. Recorrer la lista y añadir marcadores con popups
    recomendados.forEach(sitio => {
      const [lat, lng] = sitio.coords;
      
      const popupContent = `
        <h3>${sitio.nombre}</h3>
        <img src="${sitio.imagen}" width="220" alt="${sitio.nombre}">
        <p>${sitio.descripcion}</p>
        <a href="https://www.google.com/maps/search/?api=1&query=${lat},${lng}" target="_blank">
          📍 Cómo llegar
        </a>
      `;

      L.marker(sitio.coords)
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