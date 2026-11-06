<?php
require_once(__DIR__ . "/../config/sesion.php");
iniciarSesionSegura();
require_once(__DIR__ . "/../conexion.php");
require_once(__DIR__ . "/../config/config.php");
require_once(__DIR__ . "/../config/imagenes-lugares.php");

if (!isset($_SESSION["id_usuario"])) {
    header("Location: " . url("pages/login.php"));
    exit();
}

if (empty($_SESSION["csrf_favorito"])) {
    $_SESSION["csrf_favorito"] = bin2hex(random_bytes(32));
}
$flashFavorito = $_SESSION["flash_favorito"] ?? null;
unset($_SESSION["flash_favorito"]);
$idUsuario = (int) $_SESSION["id_usuario"];
$stmtLugares = $conexion->prepare(
    "SELECT l.ID_LUGAR, l.NOMBRE, l.DESCRIPCION, l.DIRECCION, l.LATITUD, l.LONGITUD,
            l.HORARIO, l.PRECIO, l.TELEFONO, l.SITIO_WEB, l.ACCESIBLE, g.FECHA_AGREGADO
     FROM USUARIO_LUGAR_GUARDADO g
     INNER JOIN LUGAR l ON l.ID_LUGAR = g.ID_LUGAR
     WHERE g.ID_USUARIO = ?
     ORDER BY g.FECHA_AGREGADO ASC, l.NOMBRE ASC"
);
$stmtLugares->bind_param("i", $idUsuario);
$stmtLugares->execute();
$resultadoLugares = $stmtLugares->get_result();
$lugares = $resultadoLugares->fetch_all(MYSQLI_ASSOC);
$stmtLugares->close();

$marcadores = [];
$puntosRuta = [];
foreach ($lugares as &$lugar) {
    $lugar["IMAGEN_URL"] = rutaImagenLugar((int) $lugar["ID_LUGAR"]);
    if (
        is_numeric($lugar["LATITUD"])
        && is_numeric($lugar["LONGITUD"])
        && (float) $lugar["LATITUD"] >= -90
        && (float) $lugar["LATITUD"] <= 90
        && (float) $lugar["LONGITUD"] >= -180
        && (float) $lugar["LONGITUD"] <= 180
    ) {
        $marcadores[] = [
            "nombre" => $lugar["NOMBRE"],
            "direccion" => $lugar["DIRECCION"],
            "coords" => [(float) $lugar["LATITUD"], (float) $lugar["LONGITUD"]],
        ];
        $puntosRuta[] = $lugar["LATITUD"] . "," . $lugar["LONGITUD"];
    }
}
unset($lugar);
$urlRuta = "";
if (count($puntosRuta) > 1) {
    $urlRuta = "https://www.google.com/maps/dir/?" . http_build_query([
        "api" => 1,
        "origin" => $puntosRuta[0],
        "destination" => $puntosRuta[count($puntosRuta) - 1],
        "waypoints" => implode("|", array_slice($puntosRuta, 1, -1)),
        "travelmode" => "walking",
    ], "", "&", PHP_QUERY_RFC3986);
}
$marcadoresJson = json_encode(
    $marcadores,
    JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_THROW_ON_ERROR
);

function escaparFavoritos($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#123c30">
    <title>Mi ruta | GoSalto</title>
    <link rel="stylesheet" href="../assets/css/var.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/componentes.css">
    <link rel="stylesheet" href="../assets/css/accesibilidad.css?v=5">
    <link rel="stylesheet" href="../assets/css/mapa-lugares.css?v=1">
    <link rel="stylesheet" href="../assets/css/lugar-detalle.css?v=1">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body>
    <?php include(__DIR__ . "/../includes/navbar.php"); ?>
    <main class="detalle-lugar pagina-interna">
        <header class="ruta-encabezado">
            <p class="detalle-categoria">Tu próxima visita</p>
            <h1>Mi ruta por Salto</h1>
            <p>Guarda lugares y consulta sus ubicaciones para organizar el recorrido.</p>
            <?php if ($urlRuta !== ""): ?>
                <a class="detalle-boton detalle-boton--principal" href="<?= escaparFavoritos($urlRuta) ?>" target="_blank" rel="noopener noreferrer">
                    <i class="fa-solid fa-route" aria-hidden="true"></i> Abrir recorrido en Google Maps
                </a>
            <?php endif; ?>
        </header>

        <?php if ($flashFavorito): ?>
            <p class="detalle-aviso" role="status"><?= escaparFavoritos($flashFavorito) ?></p>
        <?php endif; ?>

        <?php if (!$lugares): ?>
            <section class="ruta-vacia">
                <i class="fa-regular fa-heart" aria-hidden="true"></i>
                <h2>Aún no guardaste lugares</h2>
                <p>Explora el catálogo y guarda los destinos que quieras visitar.</p>
                <a class="detalle-boton detalle-boton--principal" href="<?= escaparFavoritos(url("pages/buscar.php")) ?>">Explorar lugares</a>
            </section>
        <?php else: ?>
            <section class="ruta-lista" aria-label="Lugares guardados">
                <?php foreach ($lugares as $lugar): ?>
                    <?php
                    $ubicacion = is_numeric($lugar["LATITUD"]) && is_numeric($lugar["LONGITUD"])
                        ? $lugar["LATITUD"] . "," . $lugar["LONGITUD"]
                        : $lugar["DIRECCION"] . ", Salto, Uruguay";
                    $urlComoLlegar = "https://www.google.com/maps/search/?api=1&query=" . rawurlencode($ubicacion);
                    ?>
                    <article class="ruta-lugar">
                        <img src="<?= escaparFavoritos($lugar["IMAGEN_URL"]) ?>" alt="" loading="lazy">
                        <div>
                            <h2><a href="<?= escaparFavoritos(url("pages/lugar.php?id=" . (int) $lugar["ID_LUGAR"])) ?>"><?= escaparFavoritos($lugar["NOMBRE"]) ?></a></h2>
                            <p><?= escaparFavoritos($lugar["DIRECCION"]) ?></p>
                            <?php if (!empty($lugar["HORARIO"])): ?><p><strong>Horario:</strong> <?= escaparFavoritos($lugar["HORARIO"]) ?></p><?php endif; ?>
                            <?php if (!empty($lugar["PRECIO"])): ?><p><strong>Precio:</strong> <?= escaparFavoritos($lugar["PRECIO"]) ?></p><?php endif; ?>
                            <div class="ruta-acciones">
                                <a href="<?= escaparFavoritos($urlComoLlegar) ?>" target="_blank" rel="noopener noreferrer">Cómo llegar</a>
                                <form method="POST" action="<?= escaparFavoritos(url("pages/guardar_favorito.php")) ?>">
                                    <input type="hidden" name="csrf_token" value="<?= escaparFavoritos($_SESSION["csrf_favorito"]) ?>">
                                    <input type="hidden" name="id_lugar" value="<?= (int) $lugar["ID_LUGAR"] ?>">
                                    <input type="hidden" name="accion" value="quitar">
                                    <input type="hidden" name="volver" value="favoritos">
                                    <button type="submit">Quitar de mi ruta</button>
                                </form>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </section>

            <?php if ($marcadores): ?>
                <section class="mapa-lugares" aria-labelledby="titulo-mapa-ruta">
                    <h2 id="titulo-mapa-ruta">Mapa de mi ruta</h2>
                    <p>Los marcadores muestran los lugares guardados que tienen coordenadas.</p>
                    <div id="mapaFavoritos" aria-label="Mapa de lugares guardados"></div>
                </section>
                <script>
                    document.addEventListener("DOMContentLoaded", () => {
                        const lugares = <?= $marcadoresJson ?>;
                        if (!window.L) {
                            console.error("No se pudo cargar Leaflet para mostrar tu ruta.");
                            return;
                        }
                        const mapa = L.map("mapaFavoritos");
                        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                            attribution: "&copy; OpenStreetMap contributors",
                            maxZoom: 19
                        }).addTo(mapa);
                        const marcadores = lugares.map(lugar => {
                            const marcador = L.marker(lugar.coords).addTo(mapa);
                            const contenido = document.createElement("div");
                            const nombre = document.createElement("strong");
                            nombre.textContent = lugar.nombre;
                            contenido.appendChild(nombre);
                            const direccion = document.createElement("p");
                            direccion.textContent = lugar.direccion;
                            contenido.appendChild(direccion);
                            marcador.bindPopup(contenido);
                            return marcador;
                        });
                        if (marcadores.length > 1) {
                            mapa.fitBounds(L.featureGroup(marcadores).getBounds(), { padding: [24, 24] });
                        } else {
                            mapa.setView(marcadores[0].getLatLng(), 14);
                        }
                    });
                </script>
            <?php endif; ?>
        <?php endif; ?>
    </main>
    <?php include(__DIR__ . "/../includes/footer.php"); ?>
</body>
</html>
