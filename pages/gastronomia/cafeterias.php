<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafeterías</title>
    
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
        #mapaCafeterias {
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
        <img src="../../assets/img/mdelhombre.jpg" alt="Panadería y Confitería La Estrella">
        <div class="slide-info">
            <h2>Panadería y Confitería La Estrella</h2>
            <p>
                Esta cafetería ofrece una experiencia única con su combinación de productos de panadería y confitería, 
                creando un ambiente acogedor para disfrutar de momentos especiales.
            </p>
        </div>
    </div>

    <div class="slide">
        <img src="../../assets/img/mbellasartes.jpg" alt="Panadería El Ombú">
        <div class="slide-info">
            <h2>Panadería El Ombú</h2>
            <p>
                Esta panadería, aparte de ofrecer productos de panadería y confitería, 
                también te ofrece un ambiente acogedor para disfrutar con amigos y familiares.
            </p>
        </div>
    </div>

    <div class="slide">
        <img src="../../assets/img/mhoracioquiroga.jpg" alt="Recova Burger & Coffee">
        <div class="slide-info">
            <h2>Recova Burger & Coffee</h2>
            <p>
                Esta cafetería ofrece una experiencia única con su combinación de hamburguesas gourmet 
                y café de alta calidad, creando un ambiente acogedor para disfrutar de momentos especiales.
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
    <h2>Ubicación de cafeterías y confiterías</h2>
    <p>
        Descubre los mejores lugares en Salto para disfrutar de un buen café y repostería.
    </p>

    <div id="mapaCafeterias"></div>
</section>

<?php include("../../includes/comments.php"); ?>

</main>

<?php include("../../includes/footer.php"); ?>

<?php include("../../includes/chat-widget.php"); ?>

<script src="../../assets/js/slider.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // 1. Inicializar el mapa centrado en Salto
    const mapa = L.map('mapaCafeterias').setView([-31.3880, -57.9620], 14);

    // 2. Capa de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap'
    }).addTo(mapa);

    // 3. Datos de las cafeterías
    const cafeterias = [
      {
        nombre: "Panadería y Confitería La Estrella",
        coords: [-31.3870, -57.9630],
        imagen: "../../assets/img/mdelhombre.jpg",
        descripcion: "Tradicional confitería y panadería con ambiente acogedor en la ciudad."
      },
      {
        nombre: "Panadería El Ombú",
        coords: [-31.3895, -57.9590],
        imagen: "../../assets/img/mbellasartes.jpg",
        descripcion: "Especialidades en panificados y repostería para compartir en familia."
      },
      {
        nombre: "Recova Burger & Coffee",
        coords: [-31.3862, -57.9655],
        imagen: "../../assets/img/mhoracioquiroga.jpg",
        descripcion: "Café de especialidad y hamburguesas gourmet en la zona costera/céntrica."
      }
    ];

    // 4. Recorrer la lista y añadir marcadores con popups
    cafeterias.forEach(local => {
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