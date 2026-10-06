```php
<?php

require_once("../../conexion.php");

$sql = "SELECT 
            LUGAR.ID_LUGAR,
            LUGAR.NOMBRE,
            LUGAR.DESCRIPCION,
            LUGAR.DIRECCION,
            LUGAR.IMAGEN,
            LUGAR.LATITUD,
            LUGAR.LONGITUD
        FROM LUGAR
        INNER JOIN LUGAR_CATEGORIA
            ON LUGAR.ID_LUGAR = LUGAR_CATEGORIA.ID_LUGAR
        WHERE LUGAR_CATEGORIA.ID_CATEGORIA = 1";

$resultado = $conexion->query($sql);
$parques = $resultado->fetch_all(MYSQLI_ASSOC);
$parquesConUbicacion = array_values(array_filter($parques, static function ($parque) {
    return is_numeric($parque['LATITUD'])
        && is_numeric($parque['LONGITUD'])
        && $parque['LATITUD'] >= -90
        && $parque['LATITUD'] <= 90
        && $parque['LONGITUD'] >= -180
        && $parque['LONGITUD'] <= 180;
}));

?>

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
    <link rel="stylesheet" href="<?php echo '../../assets/css/comments.css?v=3'; ?>">

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>

      #mapaParques {
    width: 70%;
    height: 450px;
    border-radius: 8px;
    margin: 15px auto 0;
    z-index: 1;
    }

    </style>

</head>

<body>

<main class="main-content pagina-interna">

<?php include("../../includes/navbar.php"); ?>


<!-- SLIDER -->

<section class="slider-container">

    <button class="slider-btn prev">❮</button>


    <?php foreach ($parques as $indice => $parque) { ?>

        <div class="slide <?php echo $indice === 0 ? 'active' : ''; ?>">

            <img 
                src="../../<?php echo $parque['IMAGEN']; ?>" 
                alt="<?php echo $parque['NOMBRE']; ?>"
            >

            <div class="slide-info">

                <h2>
                    <?php echo $parque['NOMBRE']; ?>
                </h2>

                <p>
                    <?php echo $parque['DESCRIPCION']; ?>
                </p>

            </div>

        </div>

    <?php } ?>


    <button class="slider-btn next">❯</button>


    <div class="slider-dots">

        <?php foreach ($parques as $indice => $parque) { ?>
            <span class="dot <?php echo $indice === 0 ? 'active' : ''; ?>"></span>
        <?php } ?>

    </div>

</section>


<!-- MAPA -->

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

    const mapa = L.map('mapaParques').setView([-31.3833, -57.9667], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '© OpenStreetMap'
    }).addTo(mapa);

    const parques = <?php
          $datosMapa = array_map(static function ($parque) {
              return [
                  'nombre' => $parque['NOMBRE'],
                  'coords' => [(float) $parque['LATITUD'], (float) $parque['LONGITUD']],
                  'imagen' => '../../' . $parque['IMAGEN'],
                  'descripcion' => $parque['DESCRIPCION'],
              ];
          }, $parquesConUbicacion);
          echo json_encode(
              $datosMapa,
              JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_THROW_ON_ERROR
          );
    ?>;

    const marcadores = [];
    const escaparHtml = valor => {
          const elemento = document.createElement('div');
          elemento.textContent = valor ?? '';
          return elemento.innerHTML;
    };

    parques.forEach(parque => {
          const [lat, lng] = parque.coords;
          const popupContent = `
              <h3>${escaparHtml(parque.nombre)}</h3>
              <img src="${escaparHtml(parque.imagen)}" width="220" alt="${escaparHtml(parque.nombre)}">
              <p>${escaparHtml(parque.descripcion)}</p>
              <a href="https://www.google.com/maps/search/?api=1&query=${lat},${lng}" target="_blank" rel="noopener noreferrer">
                  📍 Cómo llegar
              </a>
          `;

          marcadores.push(L.marker(parque.coords).addTo(mapa).bindPopup(popupContent));
    });

    if (marcadores.length > 1) {
          const ubicaciones = L.featureGroup(marcadores);
          mapa.fitBounds(ubicaciones.getBounds(), { padding: [24, 24], maxZoom: 15 });
    } else if (marcadores.length === 1) {
          mapa.setView(marcadores[0].getLatLng(), 15);
    }

    mapa.invalidateSize();
});

</script>


</body>

</html>
