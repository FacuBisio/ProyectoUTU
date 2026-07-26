<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parques</title>
    
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
        #mapaParques {
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
        <img src="../../assets/img/parqueharriague.jpg" alt="Parque Harriague">
        <div class="slide-info">
            <h2>Parque Harriague</h2>
            <p>
                Un espacio verde ideal para relajarse, hacer ejercicio y disfrutar de la naturaleza. 
                El parque cuenta con áreas de picnic, senderos para caminar y zonas de juegos para niños.
            </p>
        </div>
    </div>

    <div class="slide">
        <img src="../../assets/img/parquesolari.png" alt="Parque Solari">
        <div class="slide-info">
            <h2>Parque Solari</h2>
            <p>
                Este parque es perfecto para familias y personas que buscan disfrutar de la naturaleza. 
                El parque ofrece instalaciones para deportes y áreas de descanso.
            </p>
        </div>
    </div>

    <div class="slide">
        <img src="../../assets/img/parqueindigena.jpg" alt="Parque Indigena Vaimaca Pirú">
        <div class="slide-info">
            <h2>Parque Indigena Vaimaca Pirú</h2>
            <p>
                Un espacio verde y acogedor, perfecto para familias y personas que buscan disfrutar de la naturaleza. 
                El parque ofrece instalaciones para deportes y áreas de descanso.
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

<section class="mapa-parques">
    <h2>Ubicación de los parques</h2>
    <p>
        Explora la ubicación de los principales parques turísticos de Salto.
    </p>

    <div id="mapaParques"></div>
</section>

<?php include("../../includes/comments.php"); ?>

</main>

<?php include("../../includes/footer.php"); ?>

<?php include("../../includes/chat-widget.php"); ?>

<script src="../../assets/js/slider.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // 1. Inicializar el mapa
    const mapa = L.map('mapaParques').setView([-31.3833, -57.9667], 13);

    // 2. Cargar las capas de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap'
    }).addTo(mapa);

    // 3. Datos de los parques
    const parques = [
      {
        nombre: "Parque Harriague",
        coords: [-31.3833, -57.9667],
        imagen: "../../assets/img/parqueharriague.jpg",
        descripcion: "Ideal para caminar, descansar y disfrutar en familia."
      },
      {
        nombre: "Parque Solari",
        coords: [-31.3900, -57.9570],
        imagen: "../../assets/img/parquesolari.png",
        descripcion: "Uno de los parques más visitados de Salto."
      },
      {
        nombre: "Parque Indígena Vaimaca Pirú",
        coords: [-31.4025, -57.9620],
        imagen: "../../assets/img/parqueindigena.jpg",
        descripcion: "Espacio ideal para disfrutar de la naturaleza."
      }
    ];

    // 4. Recorrer los parques y agregarlos al mapa
    parques.forEach(parque => {
      const [lat, lng] = parque.coords;
      
      // Plantilla HTML del popup
      const popupContent = `
        <h3>${parque.nombre}</h3>
        <img src="${parque.imagen}" width="220" alt="${parque.nombre}">
        <p>${parque.descripcion}</p>
        <a href="https://www.google.com/maps/search/?api=1&query=${lat},${lng}" target="_blank">
          📍 Cómo llegar
        </a>
      `;

      // Crear la marca y asociar el popup
      L.marker(parque.coords)
        .addTo(mapa)
        .bindPopup(popupContent);
    });

    // 5. Ajuste para asegurar renderizado correcto si hay estilos tardíos
    setTimeout(() => {
      mapa.invalidateSize();
    }, 200);
  });
</script>

</body>
</html>