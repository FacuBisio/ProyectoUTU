<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comida Rápida</title>
    
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
        #mapaComidaRapida {
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
        <img src="../../assets/img/mdelhombre.jpg" alt="Restaurant Trouville">
        <div class="slide-info">
            <h2>Restaurant Trouville</h2>
            <p>
                Este restaurante ofrece una experiencia culinaria de comida rápida, 
                con un menú variado que incluye hamburguesas, pizzas y otros platos populares.
            </p>
        </div>
    </div>

    <div class="slide">
        <img src="../../assets/img/mbellasartes.jpg" alt="Burger King">
        <div class="slide-info">
            <h2>Burger King</h2>
            <p>
                Burger King es una cadena de comida rápida reconocida por sus hamburguesas a la parrilla, 
                ofreciendo un menú variado que incluye opciones de desayuno, almuerzo y cena.
            </p>
        </div>
    </div>

    <div class="slide">
        <img src="../../assets/img/mhoracioquiroga.jpg" alt="Pizzería Náutico">
        <div class="slide-info">
            <h2>Pizzería Náutico</h2>
            <p>
                Pizzería Náutico es un restaurante especializado en pizzas, ofreciendo una variedad de sabores y estilos, 
                con ingredientes frescos y un ambiente acogedor para disfrutar de una comida rápida y deliciosa.
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
    <h2>Ubicación de locales de comida rápida</h2>
    <p>
        Explora los locales gastronómicos y de comida rápida más conocidos de Salto.
    </p>

    <div id="mapaComidaRapida"></div>
</section>

<?php include("../../includes/comments.php"); ?>

</main>

<?php include("../../includes/footer.php"); ?>

<?php include("../../includes/chat-widget.php"); ?>

<script src="../../assets/js/slider.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // 1. Inicializar el mapa centrado en Salto
    const mapa = L.map('mapaComidaRapida').setView([-31.3880, -57.9580], 14);

    // 2. Capa de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap'
    }).addTo(mapa);

    // 3. Datos de los locales de comida rápida
    const locales = [
      {
        nombre: "Restaurant Trouville",
        coords: [-31.3878, -57.9628],
        imagen: "../../assets/img/mdelhombre.jpg",
        descripcion: "Restaurante clásico en pleno centro con chivitos, pizzas y minutas rápidas."
      },
      {
        nombre: "Burger King (Salto Shopping)",
        coords: [-31.3850, -57.9480],
        imagen: "../../assets/img/mbellasartes.jpg",
        descripcion: "Local ubicado en el patio de comidas de Salto Shopping."
      },
      {
        nombre: "Pizzería Náutico",
        coords: [-31.3892, -57.9670],
        imagen: "../../assets/img/mhoracioquiroga.jpg",
        descripcion: "Pizzería tradicional cercana a la zona del puerto y la costanera."
      }
    ];

    // 4. Recorrer la lista y añadir marcadores con popups
    locales.forEach(local => {
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