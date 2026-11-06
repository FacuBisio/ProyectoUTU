<?php
require_once(__DIR__ . "/../config/sesion.php");
iniciarSesionSegura();
require_once(__DIR__ . "/../conexion.php");
require_once(__DIR__ . "/../config/config.php");
require_once(__DIR__ . "/../config/imagenes-lugares.php");

$idLugar = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
if (!$idLugar || $idLugar < 1) {
    http_response_code(400);
    exit("El identificador del lugar no es válido.");
}

$stmtLugar = $conexion->prepare(
    "SELECT l.*, GROUP_CONCAT(DISTINCT c.NOMBRE ORDER BY c.NOMBRE SEPARATOR ', ') AS CATEGORIAS
     FROM LUGAR l
     LEFT JOIN LUGAR_CATEGORIA lc ON lc.ID_LUGAR = l.ID_LUGAR
     LEFT JOIN CATEGORIA c ON c.ID_CATEGORIA = lc.ID_CATEGORIA
     WHERE l.ID_LUGAR = ?
     GROUP BY l.ID_LUGAR
     LIMIT 1"
);
$stmtLugar->bind_param("i", $idLugar);
$stmtLugar->execute();
$lugar = $stmtLugar->get_result()->fetch_assoc();
$stmtLugar->close();

if (!$lugar) {
    http_response_code(404);
    exit("No se encontró ese lugar.");
}

$estaAutenticado = isset($_SESSION["id_usuario"]);
$esFavorito = false;
if ($estaAutenticado) {
    $usuarioId = (int) $_SESSION["id_usuario"];
    $stmtFavorito = $conexion->prepare(
        "SELECT 1 FROM USUARIO_LUGAR_GUARDADO WHERE ID_USUARIO = ? AND ID_LUGAR = ? LIMIT 1"
    );
    $stmtFavorito->bind_param("ii", $usuarioId, $idLugar);
    $stmtFavorito->execute();
    $esFavorito = $stmtFavorito->get_result()->num_rows > 0;
    $stmtFavorito->close();
}

if (empty($_SESSION["csrf_favorito"])) {
    $_SESSION["csrf_favorito"] = bin2hex(random_bytes(32));
}
$flashFavorito = $_SESSION["flash_favorito"] ?? null;
unset($_SESSION["flash_favorito"]);
$latitudValida = is_numeric($lugar["LATITUD"])
    && (float) $lugar["LATITUD"] >= -90
    && (float) $lugar["LATITUD"] <= 90;
$longitudValida = is_numeric($lugar["LONGITUD"])
    && (float) $lugar["LONGITUD"] >= -180
    && (float) $lugar["LONGITUD"] <= 180;
$tieneCoordenadas = $latitudValida && $longitudValida;
$imagenLugar = rutaImagenLugar($idLugar);
$urlMapa = $tieneCoordenadas
    ? "https://www.google.com/maps/search/?api=1&query=" . rawurlencode($lugar["LATITUD"] . "," . $lugar["LONGITUD"])
    : "https://www.google.com/maps/search/?api=1&query=" . rawurlencode($lugar["DIRECCION"] . ", Salto, Uruguay");
$sitioWebValido = !empty($lugar["SITIO_WEB"])
    && filter_var($lugar["SITIO_WEB"], FILTER_VALIDATE_URL)
    && preg_match('/^https?:\/\//i', $lugar["SITIO_WEB"]);

function escaparLugar($valor): string
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
    <title><?= escaparLugar($lugar["NOMBRE"]) ?> | GoSalto</title>
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
        <a class="detalle-volver" href="<?= escaparLugar(url("pages/buscar.php")) ?>"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Volver a explorar</a>
        <?php if ($flashFavorito): ?>
            <p class="detalle-aviso" role="status"><?= escaparLugar($flashFavorito) ?></p>
        <?php endif; ?>

        <article class="detalle-tarjeta">
            <img class="detalle-imagen" src="<?= escaparLugar($imagenLugar) ?>" alt="<?= escaparLugar($lugar["NOMBRE"]) ?>">
            <div class="detalle-contenido">
                <p class="detalle-categoria"><?= escaparLugar($lugar["CATEGORIAS"] ?: "Lugar turístico") ?></p>
                <h1><?= escaparLugar($lugar["NOMBRE"]) ?></h1>
                <?php if (!empty($lugar["DESCRIPCION"])): ?>
                    <p class="detalle-descripcion"><?= nl2br(escaparLugar($lugar["DESCRIPCION"])) ?></p>
                <?php endif; ?>
                <dl class="detalle-datos">
                    <?php if (!empty($lugar["DIRECCION"])): ?>
                        <div><dt>Dirección</dt><dd><?= escaparLugar($lugar["DIRECCION"]) ?></dd></div>
                    <?php endif; ?>
                    <?php if (!empty($lugar["HORARIO"])): ?>
                        <div><dt>Horario</dt><dd><?= escaparLugar($lugar["HORARIO"]) ?></dd></div>
                    <?php endif; ?>
                    <?php if (!empty($lugar["PRECIO"])): ?>
                        <div><dt>Precio</dt><dd><?= escaparLugar($lugar["PRECIO"]) ?></dd></div>
                    <?php endif; ?>
                    <div><dt>Accesibilidad física</dt><dd><?= $lugar["ACCESIBLE"] === null ? "Sin información" : ((int) $lugar["ACCESIBLE"] === 1 ? "Accesible" : "No accesible") ?></dd></div>
                    <?php if (!empty($lugar["TELEFONO"])): ?>
                        <div><dt>Contacto</dt><dd><a href="tel:<?= escaparLugar(preg_replace('/[^0-9+]/', '', $lugar["TELEFONO"])) ?>"><?= escaparLugar($lugar["TELEFONO"]) ?></a></dd></div>
                    <?php endif; ?>
                    <?php if ($sitioWebValido): ?>
                        <div><dt>Sitio web</dt><dd><a href="<?= escaparLugar($lugar["SITIO_WEB"]) ?>" target="_blank" rel="noopener noreferrer"><?= escaparLugar($lugar["SITIO_WEB"]) ?></a></dd></div>
                    <?php endif; ?>
                </dl>
                <div class="detalle-acciones">
                    <a class="detalle-boton detalle-boton--principal" href="<?= escaparLugar($urlMapa) ?>" target="_blank" rel="noopener noreferrer">
                        <i class="fa-solid fa-diamond-turn-right" aria-hidden="true"></i> Cómo llegar
                    </a>
                    <?php if ($estaAutenticado): ?>
                        <form method="POST" action="<?= escaparLugar(url("pages/guardar_favorito.php")) ?>">
                            <input type="hidden" name="csrf_token" value="<?= escaparLugar($_SESSION["csrf_favorito"]) ?>">
                            <input type="hidden" name="id_lugar" value="<?= (int) $idLugar ?>">
                            <input type="hidden" name="accion" value="<?= $esFavorito ? "quitar" : "guardar" ?>">
                            <input type="hidden" name="volver" value="lugar">
                            <button class="detalle-boton" type="submit">
                                <i class="fa-<?= $esFavorito ? "solid" : "regular" ?> fa-heart" aria-hidden="true"></i>
                                <?= $esFavorito ? "Quitar de mi ruta" : "Guardar en mi ruta" ?>
                            </button>
                        </form>
                    <?php else: ?>
                        <a class="detalle-boton" href="<?= escaparLugar(url("pages/login.php")) ?>">Inicia sesión para guardar</a>
                    <?php endif; ?>
                </div>
                <?php if (!empty($lugar["ACTUALIZADO_EN"])): ?>
                    <p class="detalle-actualizacion">Información actualizada: <?= escaparLugar(date("d/m/Y", strtotime($lugar["ACTUALIZADO_EN"]))) ?></p>
                <?php endif; ?>
            </div>
        </article>

        <?php if ($tieneCoordenadas): ?>
            <section class="mapa-lugares" aria-labelledby="titulo-mapa-lugar">
                <h2 id="titulo-mapa-lugar">Ubicación</h2>
                <p><?= escaparLugar($lugar["DIRECCION"]) ?></p>
                <div id="mapaDetalleLugar" aria-label="Mapa de <?= escaparLugar($lugar["NOMBRE"]) ?>"></div>
            </section>
            <script>
                document.addEventListener("DOMContentLoaded", () => {
                    if (!window.L) {
                        console.error("No se pudo cargar Leaflet para mostrar el lugar.");
                        return;
                    }
                    const mapa = L.map("mapaDetalleLugar").setView(
                        [<?= json_encode((float) $lugar["LATITUD"]) ?>, <?= json_encode((float) $lugar["LONGITUD"]) ?>],
                        15
                    );
                    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                        attribution: "&copy; OpenStreetMap contributors",
                        maxZoom: 19
                    }).addTo(mapa);
                    L.marker([<?= json_encode((float) $lugar["LATITUD"]) ?>, <?= json_encode((float) $lugar["LONGITUD"]) ?>])
                        .addTo(mapa)
                        .bindPopup(<?= json_encode($lugar["NOMBRE"], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>)
                        .openPopup();
                });
            </script>
        <?php endif; ?>
    </main>
    <?php include(__DIR__ . "/../includes/footer.php"); ?>
</body>
</html>
