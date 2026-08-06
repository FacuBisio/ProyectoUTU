<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patrimonio Histórico</title>
    
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
        #mapaPatrimonio {
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
        <img src="../../assets/img/teatro.jpeg" alt="Teatro Larrañaga">
        <div class="slide-info">
            <h2>Teatro Larrañaga</h2>
            <p>
                Un espacio cultural emblemático que ofrece una variedad de espectáculos artísticos, 
                desde teatro y música hasta danza y eventos comunitarios.
            </p>
        </div>
    </div>

    <div class="slide">
        <img src="../../assets/img/catedral.jpg" alt="Catedral Basílica San Juan Bautista">
        <div class="slide-info">
            <h2>Catedral Basílica San Juan Bautista</h2>
            <p>
                Un majestuoso edificio religioso que combina arquitectura histórica y espiritualidad, 
                siendo un punto de referencia para la comunidad y los visitantes.
            </p>
        </div>
    </div>

    <div class="slide">
        <img src="../../assets/img/granhotel.jpg" alt="Gran Hotel Concordia">
        <div class="slide-info">
            <h2>Gran Hotel Concordia</h2>
            <p>
                Un edificio histórico que combina arquitectura clásica y moderna, ofreciendo a los visitantes 
                una experiencia única de alojamiento y eventos en un entorno elegante y sofisticado.
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
    <h2>Ubicación de monumentos y edificios históricos</h2>
    <p>
        Descubre el patrimonio arquitectónico y cultural que alberga la ciudad de Salto.
    </p>

    <div id="mapaPatrimonio"></div>
</section>

<?php include("../../includes/comments.php"); ?>

</main>

<?php include("../../includes/footer.php"); ?>

<?php include("../../includes/chat-widget.php"); ?>

<script src="../../assets/js/slider.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // 1. Inicializar el mapa centrado en el centro histórico de Salto
    const mapa = L.map('mapaPatrimonio').setView([-31.3882, -57.9628], 15);

    // 2. Capa de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap'
    }).addTo(mapa);

    // 3. Datos del patrimonio histórico
    const sitiosHistoricos = [
      {
        nombre: "Teatro Larrañaga",
        coords: [-31.3887, -57.9631],
        imagen: "../../assets/img/teatro.jpeg",
        descripcion: "Emblemático teatro inaugurado en 1882, joya arquitectónica de la ciudad."
      },
      {
        nombre: "Catedral Basílica San Juan Bautista",
        coords: [-31.3875, -57.9622],
        imagen: "../../assets/img/catedral.jpg",
        descripcion: "Templo principal de Salto con imponente arquitectura frente a Plaza Artigas."
      },
      {
        nombre: "Gran Hotel Concordia",
        coords: [-31.3880, -57.9615],
        imagen: "../../assets/img/granhotel.jpg",
        descripcion: "Histórico hotel de la ciudad, reconocido por sus valor arquitectónico y cultural."
      }
    ];

    // 4. Recorrer los sitios y agregarlos al mapa
    sitiosHistoricos.forEach(sitio => {
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

    // 5. Ajustar renderizado de Leaflet
    setTimeout(() => {
      mapa.invalidateSize();
    }, 200);
  });
</script>

</body>
</html>