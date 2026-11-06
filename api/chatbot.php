<?php
header("Content-Type: application/json; charset=UTF-8");
header("Cache-Control: no-store");

// Centraliza el formato JSON para que la interfaz siempre reciba una respuesta predecible.
function responderChat(string $respuesta, array $enlaces = [], array $sugerencias = [], int $estado = 200): void
{
    http_response_code($estado);
    if ($estado >= 400) {
        echo json_encode(["error" => $respuesta], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
        exit();
    }
    echo json_encode(
        ["respuesta" => $respuesta, "enlaces" => $enlaces, "sugerencias" => $sugerencias],
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE
    );
    exit();
}

// Uniformiza mayúsculas, tildes y signos para comparar preguntas y datos del catálogo.
function normalizarChat(string $texto): string
{
    $texto = mb_strtolower($texto, "UTF-8");
    $normalizado = iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", $texto);
    if ($normalizado === false) {
        $normalizado = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $texto);
    } else {
        $normalizado = str_replace("'", "", $normalizado);
        $normalizado = preg_replace('/[^a-z0-9\s]/i', ' ', $normalizado);
    }
    return preg_replace('/\s+/', ' ', trim($normalizado));
}

// Comprueba frases completas para evitar coincidencias accidentales dentro de otras palabras.
function contieneFrase(string $texto, string $frase): bool
{
    $frase = normalizarChat($frase);
    return $frase !== "" && preg_match('/(^|\s)' . preg_quote($frase, '/') . '($|\s)/', $texto) === 1;
}

function contieneAlguna(string $texto, array $frases): bool
{
    foreach ($frases as $frase) {
        if (contieneFrase($texto, $frase)) {
            return true;
        }
    }
    return false;
}

// Busca coincidencias por nombre completo o por una palabra distintiva del lugar.
function buscarLugar(string $consulta, array $lugares): ?array
{
    $genericas = [
        "parque", "parques", "museo", "museos", "terma", "termas", "hotel",
        "restaurante", "restaurantes", "restaurant", "restaurants",
        "heladeria", "heladerias", "cafeteria", "cafeterias", "cafe", "cafes",
        "plaza", "plazas", "cine", "ocio", "comida", "rapida",
    ];
    $mejorCoincidencia = null;
    $mejorPuntaje = 0;

    foreach ($lugares as $lugar) {
        $nombre = normalizarChat($lugar["NOMBRE"]);
        if (mb_strlen($nombre, "UTF-8") >= 5 && contieneFrase($consulta, $nombre)) {
            $puntaje = mb_strlen($nombre, "UTF-8") + 100;
        } else {
            $puntaje = 0;
            foreach (explode(" ", $nombre) as $palabra) {
                if (mb_strlen($palabra, "UTF-8") >= 6
                    && !in_array($palabra, $genericas, true)
                    && contieneFrase($consulta, $palabra)) {
                    $puntaje = max($puntaje, mb_strlen($palabra, "UTF-8"));
                }
            }
        }

        if ($puntaje > $mejorPuntaje) {
            $mejorCoincidencia = $lugar;
            $mejorPuntaje = $puntaje;
        }
    }

    return $mejorCoincidencia;
}

// Interpreta qué información pidió la persona mediante un único mapa de palabras clave.
function detectarCampos(string $consulta): array
{
    $criterios = [
        "HORARIO" => ["Horario", ["horario", "horarios", "a que hora", "cuando abre", "cuando cierra", "esta abierto", "abren", "cierran"]],
        "PRECIO" => ["Precio", ["precio", "precios", "cuanto cuesta", "cuanto sale", "entrada", "entradas", "gratis", "gratuito"]],
        "DIRECCION" => ["Dirección", ["direccion", "donde queda", "ubicacion", "como llegar"]],
        "TELEFONO" => ["Teléfono", ["telefono", "telefonos", "contacto", "llamar"]],
        "ACCESIBLE" => ["Accesibilidad", ["accesible", "accesibles", "accesibilidad", "silla de ruedas"]],
        "SITIO_WEB" => ["Sitio web", ["pagina web", "sitio web", "web oficial"]],
    ];
    $campos = [];

    foreach ($criterios as $campo => [$etiqueta, $palabras]) {
        if (contieneAlguna($consulta, $palabras)) {
            $campos[$campo] = $etiqueta;
        }
    }

    return $campos;
}

// Selecciona la categoría cuyo término coincidente sea más específico.
function detectarTema(string $consulta, array $temas): ?string
{
    $mejorTema = null;
    $mejorPuntaje = 0;
    foreach ($temas as $clave => $tema) {
        foreach ($tema["palabras"] as $palabra) {
            $puntaje = mb_strlen(normalizarChat($palabra), "UTF-8");
            if ($puntaje > $mejorPuntaje && contieneFrase($consulta, $palabra)) {
                $mejorTema = $clave;
                $mejorPuntaje = $puntaje;
            }
        }
    }

    return $mejorTema;
}

// Verifica que el catálogo tenga todos los datos solicitados para un lugar.
function tieneDatosSolicitados(array $lugar, array $campos): bool
{
    foreach ($campos as $campo => $etiqueta) {
        if ($campo === "ACCESIBLE") {
            if ($lugar[$campo] === null || (int) $lugar[$campo] !== 1) {
                return false;
            }
        } elseif (trim((string) ($lugar[$campo] ?? "")) === "") {
            return false;
        }
    }

    return true;
}

// Mantiene palabras genéricas de categorías fuera de la búsqueda parcial por nombre.
function esLugarDeTema(array $lugar, array $tema): bool
{
    $categorias = normalizarChat($lugar["CATEGORIAS"] ?? "");
    $descripcion = normalizarChat($lugar["NOMBRE"] . " " . ($lugar["DESCRIPCION"] ?? ""));
    return contieneFrase($categorias, $tema["categoria"])
        || contieneAlguna($descripcion, $tema["palabras"]);
}

// Devuelve un enlace a una página interna usando la configuración común de rutas.
function enlaceInterno(string $texto, string $ruta): array
{
    require_once(__DIR__ . "/../config/config.php");
    return ["texto" => $texto, "url" => url($ruta)];
}

// Acepta únicamente consultas POST con un mensaje válido y de longitud limitada.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Allow: POST");
    http_response_code(405);
    echo json_encode(["error" => "Envía tu consulta desde el chat."], JSON_UNESCAPED_UNICODE);
    exit();
}

$mensaje = $_POST["mensaje"] ?? "";
if (!is_string($mensaje)) {
    responderChat("El mensaje no tiene un formato válido.", [], [], 400);
}
$mensaje = trim($mensaje);
if ($mensaje === "" || mb_strlen($mensaje, "UTF-8") > 1000) {
    responderChat("Escribe una consulta de hasta 1000 caracteres.", [], [], 400);
}
$consulta = normalizarChat($mensaje);

// Carga los lugares y eventos próximos desde la base; el asistente no inventa contenido.
try {
    require_once(__DIR__ . "/../conexion.php");

    $resultadoLugares = $conexion->query(
        "SELECT l.ID_LUGAR, l.NOMBRE, l.DESCRIPCION, l.DIRECCION, l.HORARIO,
                l.PRECIO, l.TELEFONO, l.SITIO_WEB, l.ACCESIBLE,
                GROUP_CONCAT(DISTINCT c.NOMBRE ORDER BY c.NOMBRE SEPARATOR ', ') AS CATEGORIAS
         FROM LUGAR l
         LEFT JOIN LUGAR_CATEGORIA lc ON lc.ID_LUGAR = l.ID_LUGAR
         LEFT JOIN CATEGORIA c ON c.ID_CATEGORIA = lc.ID_CATEGORIA
         GROUP BY l.ID_LUGAR, l.NOMBRE, l.DESCRIPCION, l.DIRECCION, l.HORARIO,
                  l.PRECIO, l.TELEFONO, l.SITIO_WEB, l.ACCESIBLE
         ORDER BY l.NOMBRE"
    );

    $fechaEvento = "STR_TO_DATE(CONCAT(e.ANIO, '-', LPAD(e.MES, 2, '0'), '-', LPAD(e.DIA, 2, '0')), '%Y-%m-%d')";
    $resultadoEventos = $conexion->query(
        "SELECT e.ID_EVENTO, e.NOMBRE, e.DIRECCION, e.DIA, e.MES, e.ANIO,
                e.HORA_INI, l.NOMBRE AS LUGAR
         FROM EVENTO e
         LEFT JOIN LUGAR l ON l.ID_LUGAR = e.ID_LUGAR
         WHERE e.ANIO IS NOT NULL AND {$fechaEvento} >= CURDATE()
         ORDER BY {$fechaEvento} ASC, e.HORA_INI ASC
         LIMIT 20"
    );
} catch (mysqli_sql_exception $error) {
    error_log("No se pudo cargar la información del chatbot: " . $error->getMessage());
    responderChat("Ahora no puedo consultar la información del sitio. Inténtalo de nuevo en unos minutos.", [], [], 503);
}

$lugares = $resultadoLugares->fetch_all(MYSQLI_ASSOC);
$eventos = $resultadoEventos->fetch_all(MYSQLI_ASSOC);

$temas = [
    "termas" => [
        "nombre" => "termas y bienestar", "categoria" => "termas y bienestar",
        "ruta" => "pages/lugares/termas.php",
        "palabras" => ["terma", "termas", "dayman", "daymán", "arapey", "aguas termales", "baños termales", "relajar"],
    ],
    "cafeterias" => [
        "nombre" => "cafeterías", "categoria" => "cafeterias",
        "ruta" => "pages/gastronomia/cafeterias.php",
        "palabras" => ["cafeteria", "cafeterias", "cafe", "cafes", "desayuno", "desayunos", "merienda", "meriendas"],
    ],
    "heladerias" => [
        "nombre" => "heladerías", "categoria" => "heladerias",
        "ruta" => "pages/gastronomia/heladerias.php",
        "palabras" => ["heladeria", "heladerias", "helado", "helados"],
    ],
    "comida_rapida" => [
        "nombre" => "comida rápida", "categoria" => "comida rapida",
        "ruta" => "pages/gastronomia/comida-rapida.php",
        "palabras" => ["comida rapida", "comidas rapidas", "hamburguesa", "hamburguesas", "pizza", "pizzas", "fast food"],
    ],
    "restaurantes" => [
        "nombre" => "restaurantes", "categoria" => "restaurantes",
        "ruta" => "pages/gastronomia/restaurantes.php",
        "palabras" => ["restaurante", "restaurantes", "restaurant", "restaurants", "almorzar", "cenar"],
    ],
    "parques" => [
        "nombre" => "parques", "categoria" => "parques",
        "ruta" => "pages/lugares/parques.php",
        "palabras" => ["parque", "parques", "plaza", "plazas", "picnic"],
    ],
    "naturaleza" => [
        "nombre" => "lugares de naturaleza", "categoria" => "paisajes y espacios naturales",
        "ruta" => "pages/lugares/paisajes.php",
        "palabras" => ["naturaleza", "paisaje", "paisajes", "sendero", "senderos", "costanera", "rio", "rios", "aire libre"],
    ],
    "museos" => [
        "nombre" => "museos y arte", "categoria" => "museos y arte",
        "ruta" => "pages/lugares/museos.php",
        "palabras" => ["museo", "museos", "arte", "exposicion", "exposiciones"],
    ],
    "cultura" => [
        "nombre" => "sitios de historia y patrimonio", "categoria" => "patrimonio historico",
        "ruta" => "pages/lugares/patrimonio.php",
        "palabras" => ["historia", "historico", "patrimonio", "catedral", "teatro", "cultura"],
    ],
    "ocio" => [
        "nombre" => "ocio y vida nocturna", "categoria" => "ocio y vida nocturna",
        "ruta" => "pages/lugares/ocio.php",
        "palabras" => ["ocio", "noche", "nocturna", "cine", "diversion"],
    ],
];

// Atiende saludos y preguntas de ayuda antes de buscar datos específicos.
$esEvento = contieneAlguna($consulta, ["evento", "eventos", "agenda", "que hay para hacer", "que se puede hacer", "actividades", "planes"]);
$esSaludo = in_array($consulta, ["hola", "buenas", "buen dia", "buenas tardes", "buenas noches", "que tal"], true);
$esAgradecimiento = in_array($consulta, ["gracias", "muchas gracias", "genial", "perfecto", "muchisimas gracias"], true);

if ($esSaludo && mb_strlen($consulta, "UTF-8") < 45) {
    responderChat(
        "¡Hola! Soy el asistente de GoSalto. Puedo ayudarte a encontrar lugares, consultar sus horarios y precios publicados, revisar eventos próximos o explorar opciones según tus intereses.",
        [],
        ["¿Qué puedo visitar?", "Busco termas", "¿Qué eventos hay?", "Lugares accesibles"]
    );
}
if ($esAgradecimiento) {
    responderChat("¡De nada! ¿Quieres que te recomiende algún lugar o que busque eventos próximos?", [], ["Recomiéndame lugares", "Ver eventos"]);
}
if (contieneAlguna($consulta, ["quien eres", "que puedes hacer", "como funciona"]) || $consulta === "ayuda") {
    responderChat(
        "Soy el asistente turístico de GoSalto. Respondo usando la información publicada en el catálogo del sitio; no confirmo disponibilidad en tiempo real. Puedo recomendar categorías, dar los datos registrados de un lugar y mostrar eventos próximos.",
        [],
        ["Recomiéndame lugares", "Consultar horarios", "Ver eventos", "Buscar cafeterías"]
    );
}

// Detecta lugar y datos solicitados antes de decidir el tipo de respuesta.
$lugarEncontrado = buscarLugar($consulta, $lugares);
$camposConsultados = detectarCampos($consulta);

// Si se identificó un sitio, prioriza su ficha sobre las recomendaciones generales.
if ($lugarEncontrado !== null) {
    $nombre = $lugarEncontrado["NOMBRE"];
    $respuesta = [];
    if ($camposConsultados) {
        foreach ($camposConsultados as $campo => $etiqueta) {
            $valor = $lugarEncontrado[$campo] ?? null;
            if ($campo === "ACCESIBLE") {
                $valor = $valor === null ? null : ((int) $valor === 1 ? "Sí" : "No");
            }
            $respuesta[] = $etiqueta . ": " . (trim((string) $valor) !== "" ? $valor : "el sitio no tiene ese dato registrado");
        }
        $textoRespuesta = $nombre . "\n" . implode("\n", $respuesta);
    } else {
        $textoRespuesta = $nombre . ".";
        if (!empty($lugarEncontrado["DESCRIPCION"])) {
            $textoRespuesta .= " " . trim($lugarEncontrado["DESCRIPCION"]);
        }
        if (!empty($lugarEncontrado["DIRECCION"])) {
            $textoRespuesta .= "\nDirección: " . $lugarEncontrado["DIRECCION"];
        }
        if (!empty($lugarEncontrado["HORARIO"])) {
            $textoRespuesta .= "\nHorario publicado: " . $lugarEncontrado["HORARIO"];
        }
        if (!empty($lugarEncontrado["PRECIO"])) {
            $textoRespuesta .= "\nPrecio publicado: " . $lugarEncontrado["PRECIO"];
        }
    }
    responderChat($textoRespuesta, [
        enlaceInterno("Ver ficha completa de " . $nombre, "pages/lugar.php?id=" . (int) $lugarEncontrado["ID_LUGAR"]),
    ], ["Ver lugares similares", "Consultar eventos"]);
}

// Presenta únicamente eventos futuros con fecha, hora y ubicación disponibles.
if ($esEvento) {
    if (!$eventos) {
        responderChat("No hay eventos próximos publicados en este momento. Puedes revisar la agenda por si se agregan nuevas actividades.", [
            enlaceInterno("Abrir agenda de eventos", "pages/eventos/eventos.php"),
        ], ["¿Qué puedo visitar?", "Buscar termas"]);
    }
    $lineas = [];
    $enlaces = [];
    foreach (array_slice($eventos, 0, 5) as $evento) {
        $fecha = sprintf("%02d/%02d/%04d", (int) $evento["DIA"], (int) $evento["MES"], (int) $evento["ANIO"]);
        $linea = $evento["NOMBRE"] . " — " . $fecha . ", " . substr((string) $evento["HORA_INI"], 0, 5);
        $ubicacion = $evento["DIRECCION"] ?: $evento["LUGAR"];
        if ($ubicacion) {
            $linea .= ", " . $ubicacion;
        }
        $lineas[] = $linea;
        $enlaces[] = enlaceInterno("Añadir " . $evento["NOMBRE"] . " al calendario", "pages/eventos/calendario.php?id=" . (int) $evento["ID_EVENTO"]);
    }
    responderChat("Estos son algunos eventos próximos publicados:\n" . implode("\n", $lineas), $enlaces, ["Buscar lugares", "¿Qué puedo visitar?"]);
}

// Identifica el tema y las solicitudes generales de eventos, gastronomía o recomendaciones.
$temaDetectado = detectarTema($consulta, $temas);
$esGastronomiaGeneral = contieneAlguna($consulta, ["gastronomia", "comer", "comida", "donde comer"]);
$esRecomendacion = contieneAlguna($consulta, ["que hacer", "hacer en salto", "visitar", "turismo", "lugares", "pasear", "sugerencias"])
    || preg_match('/(^|\s)(recomend\w*|recomiend\w*|suger\w*)(\s|$)/', $consulta) === 1;
// Filtra el catálogo según la categoría, el dato consultado o la solicitud de recomendaciones.
$soloAccesibles = isset($camposConsultados["ACCESIBLE"])
    && $lugarEncontrado === null
    && $temaDetectado === null
    && !$esGastronomiaGeneral;

$seleccionados = [];
$ruta = "";
$nombreTema = "";
if ($soloAccesibles) {
    $seleccionados = array_values(array_filter($lugares, static function ($lugar) {
        return (int) ($lugar["ACCESIBLE"] ?? 0) === 1;
    }));
    $nombreTema = "lugares que figuran como accesibles";
} elseif ($temaDetectado !== null) {
    $tema = $temas[$temaDetectado];
    $nombreTema = $tema["nombre"];
    $ruta = $tema["ruta"];
    $seleccionados = array_values(array_filter(
        $lugares,
        static fn($lugar) => esLugarDeTema($lugar, $tema)
    ));
} elseif ($esGastronomiaGeneral) {
    $nombreTema = "gastronomía";
    $ruta = "pages/gastronomia/locales-top.php";
    $categoriasGastronomia = ["restaurantes", "comida rapida", "cafeterias", "heladerias", "locales top", "recomendados"];
    $palabrasGastronomia = ["restaurante", "comida", "hamburguesa", "pizza", "cafeteria", "cafe", "heladeria", "helado", "gastronomia"];
    $seleccionados = array_values(array_filter($lugares, static function ($lugar) use ($categoriasGastronomia, $palabrasGastronomia) {
        $categorias = normalizarChat($lugar["CATEGORIAS"] ?? "");
        $descripcion = normalizarChat($lugar["NOMBRE"] . " " . ($lugar["DESCRIPCION"] ?? ""));
        return contieneAlguna($categorias, $categoriasGastronomia)
            || contieneAlguna($descripcion, $palabrasGastronomia);
    }));
} elseif ($esRecomendacion) {
    $nombreTema = "lugares para conocer en Salto";
    $destacados = ["Termas del Dayman", "Parque Harriague", "Museo del Hombre y la Tecnologia", "Costanera Norte", "Teatro Larranaga"];
    $lugaresPorNombre = [];
    foreach ($lugares as $lugar) {
        $lugaresPorNombre[normalizarChat($lugar["NOMBRE"])] = $lugar;
    }

    foreach ($destacados as $destacado) {
        $nombreNormalizado = normalizarChat($destacado);
        if (isset($lugaresPorNombre[$nombreNormalizado])) {
            $seleccionados[] = $lugaresPorNombre[$nombreNormalizado];
        }
    }

    $idsSeleccionados = array_fill_keys(array_column($seleccionados, "ID_LUGAR"), true);
    foreach ($lugares as $lugar) {
        if (count($seleccionados) >= 5) {
            break;
        }
        if (!isset($idsSeleccionados[$lugar["ID_LUGAR"]])) {
            $seleccionados[] = $lugar;
            $idsSeleccionados[$lugar["ID_LUGAR"]] = true;
        }
    }
} elseif ($camposConsultados) {
    $campo = array_key_first($camposConsultados);
    $nombreTema = "lugares con " . mb_strtolower($camposConsultados[$campo], "UTF-8") . " publicado";
    $seleccionados = $lugares;
} else {
    responderChat(
        "No estoy seguro de haber entendido la consulta. Puedo buscar un lugar por su nombre, mostrar categorías del catálogo, consultar datos publicados o listar eventos próximos.",
        [],
        ["Buscar termas", "Ver parques", "Buscar dónde comer", "Consultar eventos"]
    );
}

// Los campos sin datos se omiten de las listas; su ausencia no se interpreta como una respuesta negativa.
if ($camposConsultados) {
    $seleccionados = array_values(array_filter(
        $seleccionados,
        static fn($lugar) => tieneDatosSolicitados($lugar, $camposConsultados)
    ));
}

// Devuelve resultados con enlaces a las fichas para que la persona pueda ampliar la información.
if (!$seleccionados) {
    $respuesta = $soloAccesibles
        ? "No hay lugares marcados como accesibles en el catálogo. La falta de esa marca no significa necesariamente que un lugar no sea accesible; consulta directamente con el establecimiento."
        : ($camposConsultados
            ? "No encontré lugares con ese dato publicado en el catálogo. La ausencia de información no confirma que el servicio no esté disponible."
            : "No encontré opciones publicadas en la categoría de " . $nombreTema . ". Puedes revisar la sección por si hay información adicional.");
    responderChat($respuesta, $ruta !== "" ? [enlaceInterno("Explorar " . $nombreTema, $ruta)] : [], ["¿Qué puedo visitar?", "Consultar eventos"]);
}

$lineas = [];
$enlaces = [];
foreach (array_slice($seleccionados, 0, 5) as $lugar) {
    $linea = $lugar["NOMBRE"];
    if (!empty($lugar["DIRECCION"]) && $lugar["DIRECCION"] !== "Salto") {
        $linea .= " — " . $lugar["DIRECCION"];
    }
    foreach ($camposConsultados as $campo => $etiqueta) {
        $valor = $campo === "ACCESIBLE"
            ? ((int) ($lugar[$campo] ?? 0) === 1 ? "Sí" : "No")
            : trim((string) ($lugar[$campo] ?? ""));
        if ($valor !== "") {
            $linea .= " · " . $etiqueta . ": " . $valor;
        }
    }
    $lineas[] = $linea;
    $enlaces[] = enlaceInterno("Ver " . $lugar["NOMBRE"], "pages/lugar.php?id=" . (int) $lugar["ID_LUGAR"]);
}
if ($ruta !== "") {
    $enlaces[] = enlaceInterno("Ver todas las opciones de " . $nombreTema, $ruta);
}

$introduccion = $soloAccesibles
    ? "Según la información registrada en GoSalto, estos lugares figuran como accesibles:"
    : ($esRecomendacion && $temaDetectado === null && !$esGastronomiaGeneral
        ? "Para empezar a recorrer Salto, puedes explorar estos lugares:"
        : "Estas son algunas opciones de " . $nombreTema . " en GoSalto:");
responderChat($introduccion . "\n" . implode("\n", $lineas), $enlaces, ["Consultar horarios", "¿Qué eventos hay?", "Buscar otra categoría"]);
