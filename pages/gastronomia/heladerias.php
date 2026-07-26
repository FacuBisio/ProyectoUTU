<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Heladerías</title>
    
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
        #mapaHeladerias {
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
        <img src="../../assets/img/mdelhombre.jpg" alt="Chelato">
        <div class="slide-info">
            <h2>Chelato</h2>
            <p>
                Chelato es una heladería que ofrece una amplia variedad de sabores de helado, 
                elaborados con ingredientes frescos y de alta calidad, brindando una experiencia 
                única para los amantes del helado.
            </p>
        </div>
    </div>

    <div class="slide">
        <img src="../../assets/img/mbellasartes.jpg" alt="Heladería La Cigale">
        <div class="slide-info">
            <h2>Heladería La Cigale</h2>
            <p>
                La Heladería La Cigale es un lugar emblemático que ofrece una amplia variedad de sabores
                de helado, elaborados con ingredientes frescos y de alta calidad, brindando una experiencia
                única para los amantes del helado.
            </p>
        </div>
    </div>

    <div class="slide">
        <img src="../../assets/img/mhoracioquiroga.jpg" alt="Heladería Alfredito">
        <div class="slide-info">
            <h2>Heladería Alfredito</h2>
            <p>
                Es una heladería tradicional que ofrece una amplia variedad de sabores artesanales,
                elaborados con ingredientes frescos y de alta calidad, brindando una experiencia
                única para los amantes del helado.
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
    <h2>Ubicación de heladerías</h2>
    <p>
        Encuentra las heladerías más destacadas de Salto para disfrutar de helados artesanales y postres.
    </p>

    <div id="mapaHeladerias"></div>
</section>

<?php include("../../includes/comments.php"); ?>

</main>

<?php include("../../includes/footer.php"); ?>

<?php include("../../includes/chat-widget.php"); ?>

<script src="../../assets/js/slider.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // 1. Inicializar el mapa centrado en Salto
    const mapa = L.map('mapaHeladerias').setView([-31.3880, -57.9610], 14);

    // 2. Capa de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap'
    }).addTo(mapa);

    // 3. Datos de las heladerías
    const heladerias = [
      {
        nombre: "Chelato",
        coords: [-31.3875, -57.9620],
        imagen: "../../assets/img/mdelhombre.jpg",
        descripcion: "Heladería con amplia variedad de sabores artesanales y postres helados."
      },
      {
        nombre: "Heladería La Cigale",
        coords: [-31.3882, -57.9605],
        imagen: "../../assets/img/mbellasartes.jpg",
        descripcion: "Cadena tradicional de helados artesanales en zona céntrica."
      },
      {
        nombre: "Heladería Alfredito",
        coords: [-31.3890, -57.9585],
        imagen: "../../assets/img/mhoracioquiroga.jpg",
        descripcion: "Heladería tradicional saltoña con sabores de receta propia y atención familiar."
      }
    ];

    // 4. Recorrer la lista y añadir marcadores con popups
    heladerias.forEach(heladeria => {
      const [lat, lng] = heladeria.coords;
      
      const popupContent = `
        <h3>${heladeria.nombre}</h3>
        <img src="${heladeria.imagen}" width="220" alt="${heladeria.nombre}">
        <p>${heladeria.descripcion}</p>
        <a href="https://www.google.com/maps/search/?api=1&query=${lat},${lng}" target="_blank">
          📍 Cómo llegar
        </a>
      `;

      L.marker(heladeria.coords)
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