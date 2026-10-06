<?php
require_once(__DIR__ . "/../conexion.php");
require_once(__DIR__ . "/../config/config.php");

function escaparBusqueda($valor)
{
    return htmlspecialchars((string) $valor, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}

function obtenerResultadosBusqueda($conexion, $busqueda, $limite = null)
{
    $termino = "%" . $busqueda . "%";
    $sql = "
        SELECT
            l.ID_LUGAR AS id_lugar,
            l.NOMBRE AS nombre,
            l.DESCRIPCION AS descripcion,
            l.DIRECCION AS direccion,
            l.IMAGEN AS imagen,
            GROUP_CONCAT(DISTINCT c.NOMBRE ORDER BY c.NOMBRE SEPARATOR ', ') AS categoria
        FROM LUGAR l
        LEFT JOIN LUGAR_CATEGORIA lc ON lc.ID_LUGAR = l.ID_LUGAR
        LEFT JOIN CATEGORIA c ON c.ID_CATEGORIA = lc.ID_CATEGORIA
        WHERE
            l.NOMBRE LIKE ?
            OR l.DESCRIPCION LIKE ?
            OR l.DIRECCION LIKE ?
            OR c.NOMBRE LIKE ?
        GROUP BY l.ID_LUGAR, l.NOMBRE, l.DESCRIPCION, l.DIRECCION, l.IMAGEN
        ORDER BY
            CASE WHEN l.NOMBRE LIKE ? THEN 0 ELSE 1 END,
            l.NOMBRE ASC
    ";

    if ($limite !== null) {
        $sql .= " LIMIT ?";
    }

    $stmt = $conexion->prepare($sql);
    if ($limite === null) {
        $stmt->bind_param("sssss", $termino, $termino, $termino, $termino, $termino);
    } else {
        $stmt->bind_param("sssssi", $termino, $termino, $termino, $termino, $termino, $limite);
    }
    $stmt->execute();
    $resultado = $stmt->get_result();
    $filas = $resultado->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    foreach ($filas as &$fila) {
        $imagen = trim((string) ($fila["imagen"] ?? ""));
        $fila["imagen_url"] = $imagen !== "" ? url(ltrim($imagen, "/\\")) : "";
    }
    unset($fila);

    return $filas;
}

$busqueda = isset($_GET["q"]) && is_string($_GET["q"]) ? trim($_GET["q"]) : "";
$esSugerencias = ($_GET["sugerencias"] ?? "") === "1";

if ($esSugerencias) {
    header("Content-Type: application/json; charset=UTF-8");
    header("Cache-Control: no-store");

    if (mb_strlen($busqueda, "UTF-8") < 2) {
        echo json_encode(["resultados" => []], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit();
    }

    $sugerencias = obtenerResultadosBusqueda($conexion, $busqueda, 6);
    echo json_encode(
        ["resultados" => $sugerencias],
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );
    exit();
}

$resultados = $busqueda !== "" ? obtenerResultadosBusqueda($conexion, $busqueda) : [];

$rutasCategorias = [
    "parques" => "pages/lugares/parques.php",
    "paisajes" => "pages/lugares/paisajes.php",
    "paisajes y espacios naturales" => "pages/lugares/paisajes.php",
    "patrimonio" => "pages/lugares/patrimonio.php",
    "museos" => "pages/lugares/museos.php",
    "termas" => "pages/lugares/termas.php",
    "ocio" => "pages/lugares/ocio.php",
    "restaurantes" => "pages/gastronomia/restaurantes.php",
    "comida rápida" => "pages/gastronomia/comida-rapida.php",
    "comida rapida" => "pages/gastronomia/comida-rapida.php",
    "heladerías" => "pages/gastronomia/heladerias.php",
    "heladerias" => "pages/gastronomia/heladerias.php",
    "cafeterías" => "pages/gastronomia/cafeterias.php",
    "cafeterias" => "pages/gastronomia/cafeterias.php",
    "locales top" => "pages/gastronomia/locales-top.php",
    "recomendados" => "pages/gastronomia/recomendados.php",
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar lugares | GoSalto</title>
    <link rel="stylesheet" href="../assets/css/var.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/componentes.css">
    <link rel="stylesheet" href="../assets/css/style-secciones.css">
    <link rel="stylesheet" href="../assets/css/buscar.css">
    <link rel="stylesheet" href="../assets/css/accesibilidad.css?v=5">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
    <?php include(__DIR__ . "/../includes/navbar.php"); ?>

    <main class="pagina-busqueda">
        <div class="contenedor-busqueda">
            <h1>Buscar lugares</h1>

            <form class="buscador-pagina" action="buscar.php" method="GET" role="search">
                <input
                    type="search"
                    name="q"
                    value="<?= escaparBusqueda($busqueda) ?>"
                    placeholder="¿Qué lugar estás buscando?"
                    autocomplete="off"
                    aria-label="Buscar lugares"
                    required
                >
                <button type="submit">
                    <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                    Buscar
                </button>
            </form>

            <?php if ($busqueda === ""): ?>
                <div class="mensaje-inicial">
                    <h2><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i> Buscá un lugar turístico</h2>
                    <p>Escribí un nombre, una categoría, una dirección o una palabra relacionada.</p>
                    <div class="ejemplos-busqueda">
                        <span>Parque</span>
                        <span>Termas</span>
                        <span>Museo</span>
                        <span>Paisajes</span>
                        <span>Patrimonio</span>
                        <span>Ocio</span>
                    </div>
                </div>
            <?php else: ?>
                <div class="titulo-resultados">
                    <h2>Resultados para: <strong>“<?= escaparBusqueda($busqueda) ?>”</strong></h2>
                </div>

                <?php if ($resultados): ?>
                    <p class="cantidad-resultados"><?= count($resultados) ?> resultado(s) encontrado(s)</p>
                    <div class="resultados-busqueda">
                        <?php foreach ($resultados as $lugar): ?>
                            <?php
                            $categorias = $lugar["categoria"] ?: "Lugar turístico";
                            $ruta = "";
                            foreach ($rutasCategorias as $categoria => $destino) {
                                if (stripos($categorias, $categoria) !== false) {
                                    $ruta = $destino;
                                    break;
                                }
                            }
                            $imagen = trim((string) $lugar["imagen"]);
                            $rutaImagen = $imagen !== "" ? __DIR__ . "/../" . ltrim($imagen, "/\\") : "";
                            $imagenDisponible = $imagen !== "" && is_file($rutaImagen);
                            ?>
                            <article class="resultado-lugar">
                                <div class="resultado-imagen">
                                    <?php if ($imagenDisponible): ?>
                                        <img
                                            src="<?= escaparBusqueda($lugar["imagen_url"]) ?>"
                                            alt="<?= escaparBusqueda($lugar["nombre"]) ?>"
                                            loading="lazy"
                                        >
                                    <?php else: ?>
                                        <div class="imagen-sin-foto"><i class="fa-regular fa-image" aria-hidden="true"></i></div>
                                    <?php endif; ?>
                                </div>
                                <div class="resultado-info">
                                    <span class="categoria-resultado">
                                        <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                                        <?= escaparBusqueda($categorias) ?>
                                    </span>
                                    <h3><?= escaparBusqueda($lugar["nombre"]) ?></h3>
                                    <?php if (!empty($lugar["descripcion"])): ?>
                                        <p class="descripcion-resultado"><?= escaparBusqueda($lugar["descripcion"]) ?></p>
                                    <?php endif; ?>
                                    <?php if (!empty($lugar["direccion"])): ?>
                                        <p class="direccion"><i class="fa-solid fa-map-pin" aria-hidden="true"></i> <?= escaparBusqueda($lugar["direccion"]) ?></p>
                                    <?php endif; ?>
                                    <?php if ($ruta !== ""): ?>
                                        <a class="resultado-enlace" href="<?= escaparBusqueda(url($ruta)) ?>">Explorar esta categoría <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
                                    <?php endif; ?>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="sin-resultados">
                        <div class="icono-sin-resultados"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i></div>
                        <h3>No encontramos resultados</h3>
                        <p>No encontramos lugares relacionados con “<?= escaparBusqueda($busqueda) ?>”.</p>
                        <p>Probá con otra palabra, por ejemplo <strong>parque</strong>, <strong>termas</strong> o <strong>museo</strong>.</p>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </main>

    <?php include(__DIR__ . "/../includes/footer.php"); ?>
</body>
</html>
