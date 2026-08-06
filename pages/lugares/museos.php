<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Museos y Arte</title>
    
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
        #mapaMuseos {
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
        <img src="../../assets/img/mdelhombre.jpg" alt="Museo del Hombre y la Tecnología">
        <div class="slide-info">
            <h2>Museo del Hombre y la Tecnología</h2>
            <p>
                Un espacio dedicado a la preservación y exhibición del patrimonio cultural y 
                tecnológico de la región.
            </p>
        </div>
    </div>

    <div class="slide">
        <img src="../../assets/img/gallino.jpg" alt="Museo Bellas Artes">
        <div class="slide-info">
            <h2>Museo Bellas Artes</h2>
            <p>
               El Museo Bellas Artes alberga una colección de obras de arte que abarca desde la pintura 
               y la escultura hasta la fotografía y el diseño, ofreciendo a los visitantes una 
               experiencia cultural enriquecedora.
            </p>
        </div>
    </div>

    <div class="slide">
        <img src="../../assets/img/mhoracioquiroga.jpg" alt="Museo de Horacio Quiroga">
        <div class="slide-info">
            <h2>Museo de Horacio Quiroga</h2>
            <p>
                Un espacio dedicado a la preservación y exhibición del patrimonio cultural y 
                literario del escritor horaciano.
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
    <h2>Ubicación de los museos</h2>
    <p>
        Explora la ubicación de los principales museos y centros culturales de Salto.
    </p>

    <div id="mapaMuseos"></div>
</section>

<?php include("../../includes/comments.php"); ?>

</main>

<?php include("../../includes/footer.php"); ?>

<?php include("../../includes/chat-widget.php"); ?>

<script src="../../assets/js/slider.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // 1. Inicializar el mapa centrado en Salto
    const mapa = L.map('mapaMuseos').setView([-31.3880, -57.9620], 14);

    // 2. Capa de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap'
    }).addTo(mapa);

    // 3. Datos de los museos
    const museos = [
      {
        nombre: "Museo del Hombre y la Tecnología",
        coords: [-31.3872, -57.9635],
        imagen: "../../assets/img/mdelhombre.jpg",
        descripcion: "Preservación y exhibición del patrimonio cultural y tecnológico de la región."
      },
      {
        nombre: "Museo Bellas Artes (María Irene Olarreaga Gallino)",
        coords: [-31.3855, -57.9610],
        imagen: "../../assets/img/gallino.jpg",
        descripcion: "Colección de obras de arte, pintura, escultura y diseño."
      },
      {
        nombre: "Casa Museo Horacio Quiroga",
        coords: [-31.3965, -57.9530],
        imagen: "../../assets/img/mhoracioquiroga.jpg",
        descripcion: "Espacio dedicado a la vida y obra literaria de Horacio Quiroga."
      }
    ];

    // 4. Recorrer los museos y colocarlos en el mapa
    museos.forEach(museo => {
      const [lat, lng] = museo.coords;
      
      const popupContent = `
        <h3>${museo.nombre}</h3>
        <img src="${museo.imagen}" width="220" alt="${museo.nombre}">
        <p>${museo.descripcion}</p>
        <a href="https://www.google.com/maps/search/?api=1&query=${lat},${lng}" target="_blank">
          📍 Cómo llegar
        </a>
      `;

      L.marker(museo.coords)
        .addTo(mapa)
        .bindPopup(popupContent);
    });

    // 5. Refresco de renderizado
    setTimeout(() => {
      mapa.invalidateSize();
    }, 200);
  });
</script>

</body>
</html>