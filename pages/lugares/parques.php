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
    <link rel="stylesheet" href="<?php echo '../../assets/css/comments.css'; ?>"> 

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


    <?php $primero = true; ?>

    <?php while ($parque = $resultado->fetch_assoc()) { ?>

        <div class="slide <?php echo $primero ? 'active' : ''; ?>">

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

        <?php $primero = false; ?>

    <?php } ?>


    <button class="slider-btn next">❯</button>


    <div class="slider-dots">

        <span class="dot active"></span>
        <span class="dot"></span>
        <span class="dot"></span>

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

    // Inicializar mapa

    const mapa = L.map('mapaParques').setView([-31.3833, -57.9667], 13);


    // OpenStreetMap

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {

        attribution: '© OpenStreetMap'

    }).addTo(mapa);


    // Datos de los parques desde PHP

    const parques = [

        <?php

        $resultado->data_seek(0);

        while ($parque = $resultado->fetch_assoc()) {

        ?>

        {
            nombre: <?php echo json_encode($parque['NOMBRE']); ?>,
            coords: [
                <?php echo $parque['LATITUD']; ?>,
                <?php echo $parque['LONGITUD']; ?>
            ],
            imagen: "../../<?php echo $parque['IMAGEN']; ?>",
            descripcion: <?php echo json_encode($parque['DESCRIPCION']); ?>
        },

        <?php } ?>

    ];


    // Agregar parques al mapa

    parques.forEach(parque => {

        const [lat, lng] = parque.coords;


        const popupContent = `

            <h3>${parque.nombre}</h3>

            <img 
                src="${parque.imagen}" 
                width="220" 
                alt="${parque.nombre}"
            >

            <p>${parque.descripcion}</p>

            <a 
                href="https://www.google.com/maps/search/?api=1&query=${lat},${lng}" 
                target="_blank"
            >
                📍 Cómo llegar
            </a>

        `;


        L.marker(parque.coords)

            .addTo(mapa)

            .bindPopup(popupContent);

    });


    // Ajustar mapa

    setTimeout(() => {

        mapa.invalidateSize();

    }, 200);

});

</script>


</body>

</html>


