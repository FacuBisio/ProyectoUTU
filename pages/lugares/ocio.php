<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ocio y Vida Nocturna</title>
    
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
        #mapaOcio {
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

    <button class="slider-btn prev">&#10094;</button>

    <div class="slide active">
        <img src="../../assets/img/mdelhombre.jpg" alt="Calle Uruguay">
        <div class="slide-info">
            <h2>Calle Uruguay</h2>
            <p>
                Una de las calles más emblemáticas de la ciudad, llena de historia y cultura. 
                Es un lugar ideal para pasear, disfrutar de la arquitectura y 
                conocer la vida nocturna de la ciudad.
            </p>
        </div>
    </div>

    <div class="slide">
        <img src="../../assets/img/mbellasartes.jpg" alt="Salto Shopping">
        <div class="slide-info">
            <h2>Salto Shopping</h2>
            <p>
                Un centro comercial moderno con una amplia variedad de tiendas y servicios.
                Queda a pocos minutos del centro de la ciudad y es un lugar ideal para disfrutar de compras 
                y entretenimiento.
            </p>
        </div>
    </div>

    <div class="slide">
        <img src="../../assets/img/mhoracioquiroga.jpg" alt="Cine Sarandí">
        <div class="slide-info">
            <h2>Cine Sarandí</h2>
            <p>
                El Cine Sarandí es un lugar emblemático para los amantes del cine, 
                ofreciendo una experiencia única con una programación variada de películas nacionales 
                e internacionales.
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

<section class="mapa-seccion">
    <h2>Ubicación de centros de ocio y entretenimiento</h2>
    <p>
        Descubre los principales puntos de paseo, compras y entretenimiento nocturno en Salto.
    </p>

    <div id="mapaOcio"></div>
</section>

<?php include("../../includes/comments.php"); ?>

</main>

<?php include("../../includes/footer.php"); ?>

<?php include("../../includes/chat-widget.php"); ?>

<script src="../../assets/js/slider.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // 1. Inicializar el mapa centrado en Salto
    const mapa = L.map('mapaOcio').setView([-31.3880, -57.9600], 14);

    // 2. Capa de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap'
    }).addTo(mapa);

    // 3. Datos de los lugares de ocio
    const lugaresOcio = [
      {
        nombre: "Calle Uruguay",
        coords: [-31.3878, -57.9625],
        imagen: "../../assets/img/mdelhombre.jpg",
        descripcion: "Eje comercial y cultural céntrico ideal para paseos y gastronomía."
      },
      {
        nombre: "Salto Shopping & Terminal",
        coords: [-31.3850, -57.9480],
        imagen: "../../assets/img/mbellasartes.jpg",
        descripcion: "Centro comercial, patio de comidas, cines y servicios."
      },
      {
        nombre: "Cine Sarandí",
        coords: [-31.3888, -57.9605],
        imagen: "../../assets/img/mhoracioquiroga.jpg",
        descripcion: "Espacio emblemático para proyección de cine local e internacional."
      }
    ];

    // 4. Recorrer los puntos e incluirlos en el mapa
    lugaresOcio.forEach(lugar => {
      const [lat, lng] = lugar.coords;
      
      const popupContent = `
        <h3>${lugar.nombre}</h3>
        <img src="${lugar.imagen}" width="220" alt="${lugar.nombre}">
        <p>${lugar.descripcion}</p>
        <a href="https://www.google.com/maps/search/?api=1&query=${lat},${lng}" target="_blank">
          📍 Cómo llegar
        </a>
      `;

      L.marker(lugar.coords)
        .addTo(mapa)
        .bindPopup(popupContent);
    });

    // 5. Ajustar el tamaño del lienzo de Leaflet por si acaso
    setTimeout(() => {
      mapa.invalidateSize();
    }, 200);
  });
</script>

</body>
</html>