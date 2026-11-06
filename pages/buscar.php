<?php
require_once(__DIR__ . "/../config/sesion.php");
iniciarSesionSegura();
require_once(__DIR__ . "/../conexion.php");
require_once(__DIR__ . "/../config/config.php");
require_once(__DIR__ . "/../config/imagenes-lugares.php");

// Escapa los datos de búsqueda al insertarlos en el HTML.
function escaparBusqueda($valor)
{
    return htmlspecialchars((string) $valor, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}

// Reutiliza la misma consulta para resultados completos y sugerencias rápidas.
function obtenerResultadosBusqueda($conexion, $busqueda, $limite = null, $categoriaId = null)
{
    $termino = "%" . $busqueda . "%";
    $filtroCategoria = $categoriaId ? " AND lc.ID_CATEGORIA = ?" : "";
    $sql = "
        SELECT
            l.ID_LUGAR AS id_lugar,
            l.NOMBRE AS nombre,
            l.DESCRIPCION AS descripcion,
            l.DIRECCION AS direccion,
            GROUP_CONCAT(DISTINCT c.NOMBRE ORDER BY c.NOMBRE SEPARATOR ', ') AS categoria
        FROM LUGAR l
        LEFT JOIN LUGAR_CATEGORIA lc ON lc.ID_LUGAR = l.ID_LUGAR
        LEFT JOIN CATEGORIA c ON c.ID_CATEGORIA = lc.ID_CATEGORIA
        WHERE (
            l.NOMBRE LIKE ?
            OR l.DESCRIPCION LIKE ?
            OR l.DIRECCION LIKE ?
            OR c.NOMBRE LIKE ?
        ){$filtroCategoria}
        GROUP BY l.ID_LUGAR, l.NOMBRE, l.DESCRIPCION, l.DIRECCION
        ORDER BY
            CASE WHEN l.NOMBRE LIKE ? THEN 0 ELSE 1 END,
            l.NOMBRE ASC
    ";

    if ($limite !== null) {
        $sql .= " LIMIT ?";
    }

    $stmt = $conexion->prepare($sql);
    if ($categoriaId && $limite !== null) {
        $stmt->bind_param("ssssisi", $termino, $termino, $termino, $termino, $categoriaId, $termino, $limite);
    } elseif ($categoriaId) {
        $stmt->bind_param("ssssis", $termino, $termino, $termino, $termino, $categoriaId, $termino);
    } elseif ($limite === null) {
        $stmt->bind_param("sssss", $termino, $termino, $termino, $termino, $termino);
    } else {
        $stmt->bind_param("sssssi", $termino, $termino, $termino, $termino, $termino, $limite);
    }
    $stmt->execute();
    $resultado = $stmt->get_result();
    $filas = $resultado->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    foreach ($filas as &$fila) {
        $fila["imagen_url"] = rutaImagenLugar((int) $fila["id_lugar"]);
    }
    unset($fila);

    return $filas;
}

$busqueda = isset($_GET["q"]) && is_string($_GET["q"]) ? trim($_GET["q"]) : "";
$esSugerencias = ($_GET["sugerencias"] ?? "") === "1";
$categoriaRecibida = $_GET["categoria"] ?? "";
$categoriaId = is_string($categoriaRecibida) ? filter_var($categoriaRecibida, FILTER_VALIDATE_INT) : false;
$categoriaId = $categoriaId && $categoriaId > 0 ? $categoriaId : null;

// El buscador del navbar consume sugerencias JSON sin renderizar la página entera.
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

$categoriasDisponibles = $conexion->query("SELECT ID_CATEGORIA, NOMBRE FROM CATEGORIA ORDER BY NOMBRE");
$resultados = $busqueda !== "" ? obtenerResultadosBusqueda($conexion, $busqueda, null, $categoriaId) : [];
$eventosEncontrados = [];
if ($busqueda !== "") {
    $terminoEvento = "%" . $busqueda . "%";
    $consultaEventos = $conexion->prepare(
        "SELECT e.ID_EVENTO, e.NOMBRE, e.TIPO, e.DESCRIPCION, e.DIRECCION, e.LATITUD, e.LONGITUD,
                e.DIA, e.MES, e.ANIO, e.HORA_INI, e.HORA_FIN, u.NOMBRE AS AUTOR
         FROM EVENTO e
         INNER JOIN USUARIO u ON u.ID_USUARIO = e.ID_USUARIO
         WHERE e.NOMBRE LIKE ? OR e.TIPO LIKE ? OR e.DESCRIPCION LIKE ? OR e.DIRECCION LIKE ? OR u.NOMBRE LIKE ?
         ORDER BY CASE
             WHEN e.ANIO IS NOT NULL AND STR_TO_DATE(CONCAT(e.ANIO, '-', LPAD(e.MES, 2, '0'), '-', LPAD(e.DIA, 2, '0')), '%Y-%m-%d') >= CURDATE() THEN 0
             ELSE 1
         END,
         STR_TO_DATE(CONCAT(e.ANIO, '-', LPAD(e.MES, 2, '0'), '-', LPAD(e.DIA, 2, '0')), '%Y-%m-%d') ASC"
    );
    $consultaEventos->bind_param("sssss", $terminoEvento, $terminoEvento, $terminoEvento, $terminoEvento, $terminoEvento);
    $consultaEventos->execute();
    $eventosEncontrados = $consultaEventos->get_result()->fetch_all(MYSQLI_ASSOC);
    $consultaEventos->close();
}

// Relaciona categorías del catálogo con sus páginas de destino.
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
    <title>Buscar lugares y eventos | GoSalto</title>
    <link rel="stylesheet" href="../assets/css/var.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/componentes.css">
    <link rel="stylesheet" href="../assets/css/style-secciones.css">
    <link rel="stylesheet" href="../assets/css/buscar.css?v=2">
    <link rel="stylesheet" href="../assets/css/accesibilidad.css?v=5">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
    <?php include(__DIR__ . "/../includes/navbar.php"); ?>

    <main class="pagina-busqueda">
        <div class="contenedor-busqueda">
            <h1>Buscar en Salto</h1>

            <form class="buscador-pagina" action="buscar.php" method="GET" role="search">
                <input
                    type="search"
                    name="q"
                    value="<?= escaparBusqueda($busqueda) ?>"
                    placeholder="¿Qué lugar o evento estás buscando?"
                    autocomplete="off"
                    aria-label="Buscar lugares o eventos"
                    required
                >
                <label class="filtro-categoria">
                    <span>Filtrar lugares</span>
                    <select name="categoria">
                        <option value="">Todas las categorías</option>
                        <?php while ($categoriaDisponible = $categoriasDisponibles->fetch_assoc()): ?>
                            <option value="<?= (int) $categoriaDisponible["ID_CATEGORIA"] ?>" <?= $categoriaId === (int) $categoriaDisponible["ID_CATEGORIA"] ? "selected" : "" ?>>
                                <?= escaparBusqueda($categoriaDisponible["NOMBRE"]) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </label>
                <button type="submit">
                    <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                    Buscar
                </button>
            </form>

            <?php if ($busqueda === ""): ?>
                <div class="mensaje-inicial">
                    <h2><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i> Busca lugares y eventos</h2>
                    <p>Escribe un nombre, una categoría, una dirección o una palabra relacionada.</p>
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

                <?php if ($resultados || $eventosEncontrados): ?>
                    <p class="cantidad-resultados"><?= count($resultados) + count($eventosEncontrados) ?> resultado(s) encontrado(s)</p>
                    <?php if ($resultados): ?>
                    <h3 class="subtitulo-resultados">Lugares</h3>
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
                            ?>
                            <article class="resultado-lugar">
                                <div class="resultado-imagen">
                                    <img
                                        src="<?= escaparBusqueda($lugar["imagen_url"]) ?>"
                                        alt="<?= escaparBusqueda($lugar["nombre"]) ?>"
                                        loading="lazy"
                                    >
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
                                    <a class="resultado-enlace" href="<?= escaparBusqueda(url("pages/lugar.php?id=" . (int) $lugar["id_lugar"])) ?>">Ver información y guardar <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    <?php if ($eventosEncontrados): ?>
                        <h3 class="subtitulo-resultados">Eventos</h3>
                        <div class="resultados-eventos">
                            <?php foreach ($eventosEncontrados as $evento): ?>
                                <?php
                                $fechaEvento = $evento["ANIO"]
                                    ? sprintf("%02d/%02d/%04d", $evento["DIA"], $evento["MES"], $evento["ANIO"])
                                    : sprintf("%02d/%02d", $evento["DIA"], $evento["MES"]);
                                $ubicacionEvento = trim((string) $evento["DIRECCION"]);
                                ?>
                                <article class="resultado-evento">
                                    <p class="categoria-resultado"><i class="fa-regular fa-calendar" aria-hidden="true"></i> <?= escaparBusqueda($evento["TIPO"]) ?> · <?= escaparBusqueda($fechaEvento) ?> · <?= escaparBusqueda(substr((string) $evento["HORA_INI"], 0, 5)) ?></p>
                                    <h3><?= escaparBusqueda($evento["NOMBRE"]) ?></h3>
                                    <?php if (!empty($evento["DESCRIPCION"])): ?>
                                        <p><?= escaparBusqueda($evento["DESCRIPCION"]) ?></p>
                                    <?php endif; ?>
                                    <?php if ($ubicacionEvento !== ""): ?>
                                        <p class="direccion"><i class="fa-solid fa-location-dot" aria-hidden="true"></i> <?= escaparBusqueda($ubicacionEvento) ?></p>
                                    <?php endif; ?>
                                    <a class="resultado-enlace" href="<?= escaparBusqueda(url("pages/eventos/eventos.php?estado=todos#evento-" . (int) $evento["ID_EVENTO"])) ?>">Ver evento <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
                                    <?php if (!empty($evento["ANIO"])): ?>
                                        <a class="resultado-enlace" href="<?= escaparBusqueda(url("pages/eventos/calendario.php?id=" . (int) $evento["ID_EVENTO"])) ?>">Añadir al calendario <i class="fa-regular fa-calendar-plus" aria-hidden="true"></i></a>
                                    <?php endif; ?>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="sin-resultados">
                        <div class="icono-sin-resultados"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i></div>
                        <h3>No encontramos resultados</h3>
                        <p>No encontramos lugares relacionados con “<?= escaparBusqueda($busqueda) ?>”.</p>
                        <p>Prueba con otra palabra, por ejemplo <strong>parque</strong>, <strong>termas</strong> o <strong>museo</strong>.</p>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </main>

    <?php include(__DIR__ . "/../includes/footer.php"); ?>
</body>
</html>
