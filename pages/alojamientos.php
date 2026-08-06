<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alojamientos</title>
    
    <link rel="stylesheet" href="<?php echo '../../assets/css/var.css'; ?>">
    <link rel="stylesheet" href="<?php echo '../../assets/css/styles.css'; ?>">
    <link rel="stylesheet" href="<?php echo '../../assets/css/componentes.css'; ?>">
    <link rel="stylesheet" href="<?php echo '../../assets/css/style-secciones.css'; ?>">
    <link rel="stylesheet" href="<?php echo '../../assets/css/slider.css'; ?>"> 
    <link rel="stylesheet" href="../../assets/css/comentarios.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        #mapaAlojamientos {
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
    <h2>Ubicación de alojamientos</h2>
    <p>
        Encuentra los mejores hoteles, posadas y complejos para hospedarte durante tu estadía en Salto.
    </p>

    <div id="mapaAlojamientos"></div>
</section>

<?php include("../../includes/comments.php"); ?>

</main>

<?php include("../../includes/footer.php"); ?>

<?php include("../../includes/chat-widget.php"); ?>

<script src="../../assets/js/slider.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // 1. Inicializar el mapa centrado en Salto
    const mapa = L.map('mapaAlojamientos').setView([-31.3880, -57.9600], 13);

    // 2. Capa de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap'
    }).addTo(mapa);

    // 3. Datos de alojamientos
    const alojamientos = [
      {
        nombre: "Hotel Los Cedros",
        coords: [-31.3870, -57.9620],
        imagen: "../../assets/img/granhotel.jpg",
        descripcion: "Excelente ubicación céntrica con cómodas instalaciones para tu estadía."
      },
      {
        nombre: "Hotel Salto & Casino",
        coords: [-31.3880, -57.9610],
        imagen: "../../assets/img/granhotel.jpg",
        descripcion: "Hotel de alta categoría ubicado frente a la Plaza Artigas."
      },
      {
        nombre: "Complejo Posada del Daymán",
        coords: [-31.4685, -57.9090],
        imagen: "../../assets/img/termas.jpeg",
        descripcion: "Ideal para disfrutar del descanso y las aguas termales de Daymán."
      }
    ];

    // 4. Recorrer las opciones y agregarlas al mapa
    alojamientos.forEach(hotel => {
      const [lat, lng] = hotel.coords;
      
      const popupContent = `
        <h3>${hotel.nombre}</h3>
        <img src="${hotel.imagen}" width="220" alt="${hotel.nombre}">
        <p>${hotel.descripcion}</p>
        <a href="https://www.google.com/maps/search/?api=1&query=${lat},${lng}" target="_blank">
          📍 Cómo llegar
        </a>
      `;

      L.marker(hotel.coords)
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