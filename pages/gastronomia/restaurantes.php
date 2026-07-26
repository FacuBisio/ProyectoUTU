<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurantes</title>
    
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
        #mapaRestaurantes {
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
        <img src="../../assets/img/mdelhombre.jpg" alt="El Rancho">
        <div class="slide-info">
            <h2>El Rancho</h2>
            <p>
                El Rancho es un restaurante particularmente conocido por su ambiente acogedor y su excelente servicio,
                ofreciendo a sus clientes una experiencia culinaria única con platos elaborados con ingredientes frescos y de alta calidad.
            </p>
        </div>
    </div>

    <div class="slide">
        <img src="../../assets/img/mbellasartes.jpg" alt="La Trattoria">
        <div class="slide-info">
            <h2>La Trattoria</h2>
            <p>
                La Trattoria es un restaurante que a diferencia de otros lugares de gastronomía
                te ofrece una experiencia única en la que podrás disfrutar de una comida deliciosa y un ambiente acogedor,
                brindando a sus clientes un momento de disfrute y satisfacción.
            </p>
        </div>
    </div>

    <div class="slide">
        <img src="../../assets/img/mhoracioquiroga.jpg" alt="La Caldera">
        <div class="slide-info">
            <h2>La Caldera</h2>
            <p>
                La Caldera es un restaurante que ofrece una experiencia culinaria única, 
                con platos elaborados con ingredientes frescos y de alta calidad, 
                brindando a sus clientes un momento de disfrute y satisfacción.
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
    <h2>Ubicación de restaurantes tradicionales</h2>
    <p>
        Descubre las mejores propuestas gastronómicas y parrillas para almorzar o cenar en Salto.
    </p>

    <div id="mapaRestaurantes"></div>
</section>

<?php include("../../includes/comments.php"); ?>

</main>

<?php include("../../includes/footer.php"); ?>

<?php include("../../includes/chat-widget.php"); ?>

<script src="../../assets/js/slider.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // 1. Inicializar el mapa centrado en Salto
    const mapa = L.map('mapaRestaurantes').setView([-31.3880, -57.9620], 14);

    // 2. Capa de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap'
    }).addTo(mapa);

    // 3. Datos de los restaurantes
    const restaurantes = [
      {
        nombre: "El Rancho",
        coords: [-31.3872, -57.9645],
        imagen: "../../assets/img/mdelhombre.jpg",
        descripcion: "Restaurante con variada propuesta gastronómica, carnes y cocina tradicional."
      },
      {
        nombre: "La Trattoria",
        coords: [-31.3885, -57.9612],
        imagen: "../../assets/img/mbellasartes.jpg",
        descripcion: "Especialidad en pastas artesanales, pizzas y cocina de estilo italiano."
      },
      {
        nombre: "La Caldera",
        coords: [-31.3860, -57.9630],
        imagen: "../../assets/img/mhoracioquiroga.jpg",
        descripcion: "Parrilla tradicional con platos elaborados y gran atención."
      }
    ];

    // 4. Recorrer la lista y añadir marcadores con popups
    restaurantes.forEach(resto => {
      const [lat, lng] = resto.coords;
      
      const popupContent = `
        <h3>${resto.nombre}</h3>
        <img src="${resto.imagen}" width="220" alt="${resto.nombre}">
        <p>${resto.descripcion}</p>
        <a href="https://www.google.com/maps/search/?api=1&query=${lat},${lng}" target="_blank">
          📍 Cómo llegar
        </a>
      `;

      L.marker(resto.coords)
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