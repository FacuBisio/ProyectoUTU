<?php
require_once(__DIR__ . "/../conexion.php");

if (!empty($mapaLugarIds) && is_array($mapaLugarIds)) {
    $filtroMapa = array_values(array_unique(array_filter(
        array_map("intval", $mapaLugarIds),
        static function ($idLugar) {
            return $idLugar > 0;
        }
    )));
    $columnaFiltroMapa = "l.ID_LUGAR";
} elseif (!empty($mapaCategorias) && is_array($mapaCategorias)) {
    $filtroMapa = array_values(array_unique(array_filter(
        array_map("intval", $mapaCategorias),
        static function ($idCategoria) {
            return $idCategoria > 0;
        }
    )));
    $columnaFiltroMapa = "lc.ID_CATEGORIA";
} else {
    throw new LogicException("Debe indicarse al menos una categoría o lugar para cargar el mapa.");
}

if (!$filtroMapa) {
    throw new LogicException("Los filtros del mapa deben ser identificadores válidos.");
}

$filtroMapaSql = implode(",", $filtroMapa);
$consultaMapa = $conexion->query(
    "SELECT
        l.ID_LUGAR,
        l.NOMBRE,
        l.DESCRIPCION,
        l.DIRECCION,
        l.IMAGEN,
        l.LATITUD,
        l.LONGITUD,
        GROUP_CONCAT(DISTINCT c.NOMBRE ORDER BY c.NOMBRE SEPARATOR ', ') AS CATEGORIA
     FROM LUGAR l
     INNER JOIN LUGAR_CATEGORIA lc ON lc.ID_LUGAR = l.ID_LUGAR
     LEFT JOIN CATEGORIA c ON c.ID_CATEGORIA = lc.ID_CATEGORIA
     WHERE {$columnaFiltroMapa} IN ({$filtroMapaSql})
     GROUP BY l.ID_LUGAR
     ORDER BY l.NOMBRE"
);

$ubicacionesMapa = [];
while ($lugarMapa = $consultaMapa->fetch_assoc()) {
    if (
        !is_numeric($lugarMapa["LATITUD"])
        || !is_numeric($lugarMapa["LONGITUD"])
        || (float) $lugarMapa["LATITUD"] < -90
        || (float) $lugarMapa["LATITUD"] > 90
        || (float) $lugarMapa["LONGITUD"] < -180
        || (float) $lugarMapa["LONGITUD"] > 180
    ) {
        continue;
    }

    $imagenMapa = trim((string) ($lugarMapa["IMAGEN"] ?? ""));
    $ubicacionesMapa[] = [
        "nombre" => $lugarMapa["NOMBRE"],
        "descripcion" => $lugarMapa["DESCRIPCION"] ?? "",
        "direccion" => $lugarMapa["DIRECCION"] ?? "",
        "categoria" => $lugarMapa["CATEGORIA"] ?? "",
        "coords" => [(float) $lugarMapa["LATITUD"], (float) $lugarMapa["LONGITUD"]],
        "imagen" => $imagenMapa !== "" ? "../../" . ltrim($imagenMapa, "/\\") : "",
    ];
}

$ubicacionesJson = json_encode(
    $ubicacionesMapa,
    JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_THROW_ON_ERROR
);
$tituloMapaSeguro = htmlspecialchars($mapaTitulo ?? "Ubicación de los lugares", ENT_QUOTES, "UTF-8");
$descripcionMapaSegura = htmlspecialchars(
    $mapaDescripcion ?? "Explorá los lugares y encontrá cómo llegar.",
    ENT_QUOTES,
    "UTF-8"
);
?>
<section class="mapa-parques mapa-lugares" aria-labelledby="tituloMapaCategoria">
    <h2 id="tituloMapaCategoria"><?= $tituloMapaSeguro ?></h2>
    <p><?= $descripcionMapaSegura ?></p>
    <div class="mapa-lugares-contenedor">
        <div id="mapaCategoria" aria-label="Mapa de ubicaciones"></div>
        <?php if (!$ubicacionesMapa): ?>
            <p class="mapa-lugares-aviso" role="status">Todavía no hay ubicaciones con coordenadas para mostrar en el mapa.</p>
        <?php endif; ?>
    </div>
</section>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const ubicaciones = <?= $ubicacionesJson ?>;
    const elementoMapa = document.getElementById("mapaCategoria");
    if (!elementoMapa) {
        console.error("No se encontró el contenedor del mapa de lugares.");
        return;
    }
    if (!window.L) {
        console.error("No se pudo cargar Leaflet para mostrar el mapa.");
        return;
    }

    const mapa = L.map(elementoMapa).setView([-31.3833, -57.9667], 13);
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "&copy; OpenStreetMap contributors",
        maxZoom: 19
    }).addTo(mapa);

    const marcadores = [];
    ubicaciones.forEach(lugar => {
        const [latitud, longitud] = lugar.coords;
        const contenido = document.createElement("div");
        contenido.className = "mapa-lugares-popup";

        const titulo = document.createElement("h3");
        titulo.textContent = lugar.nombre;
        contenido.appendChild(titulo);

        if (lugar.imagen) {
            const imagen = document.createElement("img");
            imagen.src = lugar.imagen;
            imagen.alt = lugar.nombre;
            imagen.loading = "lazy";
            imagen.addEventListener("error", () => imagen.remove(), { once: true });
            contenido.appendChild(imagen);
        }

        if (lugar.categoria) {
            const categoria = document.createElement("small");
            categoria.textContent = lugar.categoria;
            contenido.appendChild(categoria);
        }

        if (lugar.descripcion) {
            const descripcion = document.createElement("p");
            descripcion.textContent = lugar.descripcion;
            contenido.appendChild(descripcion);
        }

        if (lugar.direccion) {
            const direccion = document.createElement("p");
            direccion.className = "mapa-lugares-direccion";
            direccion.textContent = lugar.direccion;
            contenido.appendChild(direccion);
        }

        const comoLlegar = document.createElement("a");
        comoLlegar.href = `https://www.google.com/maps/search/?api=1&query=${latitud},${longitud}`;
        comoLlegar.target = "_blank";
        comoLlegar.rel = "noopener noreferrer";
        comoLlegar.textContent = "Cómo llegar";
        contenido.appendChild(comoLlegar);

        marcadores.push(L.marker(lugar.coords).addTo(mapa).bindPopup(contenido));
    });

    if (marcadores.length > 1) {
        mapa.fitBounds(L.featureGroup(marcadores).getBounds(), { padding: [24, 24], maxZoom: 15 });
    } else if (marcadores.length === 1) {
        mapa.setView(marcadores[0].getLatLng(), 15);
    }

    window.setTimeout(() => mapa.invalidateSize(), 200);
});
</script>
