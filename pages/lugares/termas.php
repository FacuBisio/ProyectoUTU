<?php

require_once(__DIR__ . "/../../conexion.php");

// Buscar la categoría Termas
$sql_categoria = "SELECT id_categoria
                  FROM categoria
                  WHERE nombre = 'Termas'
                  LIMIT 1";

$resultado_categoria = $conexion->query($sql_categoria);

if (!$resultado_categoria) {
    die("Error al buscar la categoría: " . $conexion->error);
}

if ($resultado_categoria->num_rows == 0) {
    die("No se encontró la categoría Termas.");
}

$categoria = $resultado_categoria->fetch_assoc();

$id_categoria = $categoria["id_categoria"];


// Buscar los lugares pertenecientes a Termas
$sql_lugares = "SELECT id_lugar, nombre, descripcion, direccion, imagen
                FROM lugar
                WHERE id_categoria = ?
                ORDER BY id_lugar ASC";

$stmt_lugares = $conexion->prepare($sql_lugares);

if (!$stmt_lugares) {
    die("Error al preparar la consulta: " . $conexion->error);
}

$stmt_lugares->bind_param("i", $id_categoria);

$stmt_lugares->execute();

$lugares = $stmt_lugares->get_result();


// Guardar los lugares en un array
$lista_lugares = [];

while ($lugar = $lugares->fetch_assoc()) {
    $lista_lugares[] = $lugar;
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termas</title>

    <link rel="stylesheet" href="../../assets/css/var.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/componentes.css">
    <link rel="stylesheet" href="../../assets/css/style-secciones.css">
    <link rel="stylesheet" href="../../assets/css/slider.css">
    <link rel="stylesheet" href="../../assets/css/comentarios.css">

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        #mapaTermas {
            width: 100%;
            height: 450px;
            border-radius: 8px;
            margin-top: 15px;
        }
    </style>
</head>

<body>

<main class="main-content pagina-interna">

<?php include("../../includes/navbar.php"); ?>

<section class="slider-container">

    <button class="slider-btn prev">❮</button>

    <?php

$primer_slide = true;

while ($lugar = $lugares->fetch_assoc()) {

?>

<div class="slide <?= $primer_slide ? 'active' : '' ?>">

    <img 
        src="../../assets/img/<?= htmlspecialchars($lugar["imagen"]) ?>" 
        alt="<?= htmlspecialchars($lugar["nombre"]) ?>"
    >

    <div class="slide-info">

        <h2>
            <?= htmlspecialchars($lugar["nombre"]) ?>
        </h2>

        <p>
            <?= htmlspecialchars($lugar["descripcion"]) ?>
        </p>

        <?php if (!empty($lugar["direccion"])) { ?>

            <small>
                📍 <?= htmlspecialchars($lugar["direccion"]) ?>
            </small>

        <?php } ?>

    </div>

</div>

<?php

    $primer_slide = false;

}

?>

    <button class="slider-btn next">❯</button>
<div class="slider-dots">

    <?php

    $cantidad_lugares = $lugares->num_rows;

    for ($i = 0; $i < $cantidad_lugares; $i++) {

    ?>

        <span class="dot <?= $i == 0 ? 'active' : '' ?>"></span>

    <?php } ?>

</div>

</section>


<section class="mapa-seccion">

    <h2>Ubicación de complejos termales</h2>

    <p>
        Descubre la localización de los principales centros termales
        del departamento de Salto.
    </p>

    <div id="mapaTermas"></div>

</section>


<?php include(__DIR__ . "/../../includes/coments.php"); ?>

</main>

<?php include("../../includes/footer.php"); ?>

<?php include("../../includes/chat-widget.php"); ?>

<script src="../../assets/js/slider.js"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {

    const mapa = L.map("mapaTermas").setView([-31.1000, -57.8000], 9);

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "© OpenStreetMap"
    }).addTo(mapa);


    const termas = [
        {
            nombre: "Termas del Daymán",
            coords: [-31.4678, -57.9083],
            imagen: "../../assets/img/termas.jpeg",
            descripcion: "Complejo termal con piscinas, spa y parque acuático."
        },
        {
            nombre: "Termas del Arapey",
            coords: [-30.9340, -57.5186],
            imagen: "../../assets/img/arapey.jpg",
            descripcion: "Centro termal rodeado de naturaleza y hoteles."
        }
    ];


    termas.forEach(terma => {

        const popup = `
            <h3>${terma.nombre}</h3>
            <img src="${terma.imagen}" width="220">
            <p>${terma.descripcion}</p>
            <a href="https://www.google.com/maps/search/?api=1&query=${terma.coords[0]},${terma.coords[1]}" target="_blank">
                📍 Cómo llegar
            </a>
        `;


        L.marker(terma.coords)
            .addTo(mapa)
            .bindPopup(popup);

    });


    setTimeout(() => {
        mapa.invalidateSize();
    }, 200);

});
</script>

</body>
</html>