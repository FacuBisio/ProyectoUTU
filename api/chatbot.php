<?php
header("Content-Type: application/json; charset=UTF-8");
header("Cache-Control: no-store");

function responderChat($payload, $status = 200)
{
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit();
}

function normalizarChat($texto)
{
    $texto = mb_strtolower($texto, "UTF-8");
    $normalizado = iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", $texto);
    if ($normalizado === false) {
        $normalizado = preg_replace('/[^\p{L}\p{N}\s]/u', '', $texto);
    } else {
        $normalizado = preg_replace('/[^a-z0-9\s]/i', '', $normalizado);
    }
    return preg_replace('/\s+/', ' ', trim($normalizado));
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Allow: POST");
    responderChat(["error" => "Enviá tu consulta desde el chat."], 405);
}

$mensaje = $_POST["mensaje"] ?? "";
if (!is_string($mensaje)) {
    responderChat(["error" => "El mensaje no tiene un formato válido."], 400);
}

$mensaje = trim($mensaje);
if ($mensaje === "" || mb_strlen($mensaje, "UTF-8") > 1000) {
    responderChat(["error" => "Escribí una consulta de hasta 1000 caracteres."], 400);
}

$consulta = normalizarChat($mensaje);

try {
    require_once(__DIR__ . "/../conexion.php");

    $sql = "SELECT l.ID_LUGAR, l.NOMBRE, l.DESCRIPCION, l.DIRECCION,
                   GROUP_CONCAT(DISTINCT c.NOMBRE ORDER BY c.NOMBRE SEPARATOR ', ') AS CATEGORIAS
            FROM LUGAR l
            LEFT JOIN LUGAR_CATEGORIA lc ON lc.ID_LUGAR = l.ID_LUGAR
            LEFT JOIN CATEGORIA c ON c.ID_CATEGORIA = lc.ID_CATEGORIA
            GROUP BY l.ID_LUGAR, l.NOMBRE, l.DESCRIPCION, l.DIRECCION
            ORDER BY l.NOMBRE";
    $resultado = $conexion->query($sql);
    $lugares = [];

    while ($fila = $resultado->fetch_assoc()) {
        $fila["BUSQUEDA"] = normalizarChat(
            $fila["NOMBRE"] . " " . $fila["CATEGORIAS"] . " "
            . $fila["DESCRIPCION"] . " " . $fila["DIRECCION"]
        );
        $lugares[] = $fila;
    }
} catch (Throwable $error) {
    error_log("No se pudo cargar la información turística del chatbot: " . $error->getMessage());
    responderChat(["error" => "Ahora no puedo consultar los lugares. Probá de nuevo en unos minutos."], 503);
}

$categorias = [
    "termas" => ["terma", "aguas termales", "dayman", "arapey", "relajar", "relax", "banos termales"],
    "parques" => ["parque", "plaza", "verde", "aire libre", "picnic"],
    "naturaleza" => ["naturaleza", "paisaje", "sendero", "cueva", "costanera", "rio", "aire libre"],
    "gastronomia" => ["gastronomia", "comer", "comida", "restaurant", "restaurante", "hamburguesa", "almorzar", "cenar"],
    "cafeterias" => ["cafe", "cafeteria", "merienda", "desayuno"],
    "heladerias" => ["helado", "heladeria", "postre"],
    "museos" => ["museo", "arte", "exposicion"],
    "cultura" => ["historia", "historico", "patrimonio", "catedral", "teatro", "cultura"],
    "ocio" => ["ocio", "noche", "nocturna", "cine", "diversion"],
    "eventos" => ["evento", "eventos", "agenda", "actividad"],
];

$rutas = [
    "termas" => "pages/lugares/termas.php",
    "parques" => "pages/lugares/parques.php",
    "naturaleza" => "pages/lugares/paisajes.php",
    "gastronomia" => "pages/gastronomia/locales-top.php",
    "cafeterias" => "pages/gastronomia/cafeterias.php",
    "heladerias" => "pages/gastronomia/heladerias.php",
    "museos" => "pages/lugares/museos.php",
    "cultura" => "pages/lugares/patrimonio.php",
    "ocio" => "pages/lugares/ocio.php",
    "eventos" => "pages/eventos/eventos.php",
];

$categoriasEsperadas = [
    "termas" => ["termas y bienestar"],
    "parques" => ["parques"],
    "naturaleza" => ["paisajes y espacios naturales"],
    "gastronomia" => ["restaurantes", "comida rapida", "locales top", "recomendados"],
    "cafeterias" => ["cafeterias"],
    "heladerias" => ["heladerias"],
    "museos" => ["museos y arte"],
    "cultura" => ["patrimonio historico"],
    "ocio" => ["ocio y vida nocturna"],
    "eventos" => [],
];

$responderLista = static function ($seleccionados, $introduccion, $ruta) {
    require_once(__DIR__ . "/../config/config.php");
    $lineas = [];
    foreach (array_slice($seleccionados, 0, 4) as $lugar) {
        $linea = $lugar["NOMBRE"];
        if (!empty($lugar["DIRECCION"])) {
            $linea .= " (" . $lugar["DIRECCION"] . ")";
        }
        $lineas[] = $linea;
    }

    $respuesta = $introduccion;
    if ($lineas) {
        $respuesta .= "\n" . implode("\n", $lineas);
    }
    if ($ruta !== "") {
        $respuesta .= "\n\nEncontrás más opciones en: " . url($ruta);
    }
    return $respuesta;
};

$saludos = ["hola", "buenas", "buen dia", "buenas tardes", "buenas noches", "que tal"];
foreach ($saludos as $saludo) {
    if (preg_match('/(^|\s)' . preg_quote($saludo, '/') . '($|[!,.?\s])/', $consulta)) {
        responderChat([
            "respuesta" => "¡Hola! Soy el asistente de GoSalto. Puedo recomendarte termas, parques, lugares para comer, museos y otros sitios de la ciudad. ¿Qué te gustaría conocer?"
        ]);
    }
}

if (preg_match('/\b(gracias|muchas gracias|genial|perfecto)\b/', $consulta)) {
    responderChat(["respuesta" => "¡De nada! Si querés, también puedo recomendarte lugares para visitar, comer o disfrutar de las termas."]);
}

$lugarEncontrado = null;
foreach ($lugares as $lugar) {
    $nombre = normalizarChat($lugar["NOMBRE"]);
    if (mb_strlen($nombre, "UTF-8") >= 4 && str_contains($consulta, $nombre)) {
        $lugarEncontrado = $lugar;
        break;
    }
}

if ($lugarEncontrado !== null) {
    require_once(__DIR__ . "/../config/config.php");
    $respuesta = $lugarEncontrado["NOMBRE"] . ".";
    if (!empty($lugarEncontrado["DESCRIPCION"])) {
        $respuesta .= " " . trim($lugarEncontrado["DESCRIPCION"]);
    }
    if (!empty($lugarEncontrado["DIRECCION"])) {
        $respuesta .= " Dirección: " . $lugarEncontrado["DIRECCION"] . ".";
    }

    $categoria = normalizarChat($lugarEncontrado["CATEGORIAS"] ?? "");
    $ruta = "";
    foreach ($categorias as $tipo => $palabras) {
        foreach ($palabras as $palabra) {
            if (str_contains($categoria, normalizarChat($palabra))) {
                $ruta = $rutas[$tipo];
                break 2;
            }
        }
    }
    if ($ruta !== "") {
        $respuesta .= " Más información: " . url($ruta);
    }
    responderChat(["respuesta" => $respuesta]);
}

$categoriaDetectada = null;
$coincidencias = [];
foreach ($categorias as $tipo => $palabras) {
    foreach ($palabras as $palabra) {
        if (str_contains($consulta, normalizarChat($palabra))) {
            $categoriaDetectada = $tipo;
            break;
        }
    }
    if ($categoriaDetectada !== null) {
        break;
    }
}

if ($categoriaDetectada !== null) {
    foreach ($lugares as $lugar) {
        $categoriasLugar = normalizarChat($lugar["CATEGORIAS"] ?? "");
        foreach ($categoriasEsperadas[$categoriaDetectada] as $categoriaEsperada) {
            if (str_contains($categoriasLugar, normalizarChat($categoriaEsperada))) {
                $coincidencias[] = $lugar;
                break;
            }
        }
    }

    if ($categoriaDetectada === "eventos" && !$coincidencias) {
        responderChat([
            "respuesta" => "Podés revisar la sección de Eventos para ver lo publicado en la agenda. La información puede cambiar, así que confirmá allí los detalles.",
        ]);
    }

    $nombres = [
        "termas" => "termas y bienestar",
        "parques" => "parques y espacios verdes",
        "naturaleza" => "lugares de naturaleza",
        "gastronomia" => "lugares para comer",
        "cafeterias" => "cafeterías",
        "heladerias" => "heladerías",
        "museos" => "museos y arte",
        "cultura" => "sitios de historia y patrimonio",
        "ocio" => "opciones de ocio",
        "eventos" => "lugares y actividades",
    ];
    $respuesta = $responderLista(
        $coincidencias,
        "Estas son algunas opciones de " . $nombres[$categoriaDetectada] . " en Salto:",
        $rutas[$categoriaDetectada]
    );
    responderChat(["respuesta" => $respuesta]);
}

if (preg_match('/\b(que hacer|hacer en salto|recomend|visitar|turismo|lugares|pasear)\b/', $consulta)) {
    $recomendados = [];
    foreach (["Termas del Daymán", "Parque Harriague", "Museo del Hombre y la Tecnología", "Costanera Norte"] as $destacado) {
        foreach ($lugares as $lugar) {
            if (normalizarChat($lugar["NOMBRE"]) === normalizarChat($destacado)) {
                $recomendados[] = $lugar;
                break;
            }
        }
    }
    if (!$recomendados) {
        $recomendados = array_slice($lugares, 0, 4);
    }
    responderChat([
        "respuesta" => $responderLista($recomendados, "Para empezar a recorrer Salto, podés considerar estas opciones:", "")
    ]);
}

responderChat([
    "respuesta" => "Puedo orientarte con información de los lugares cargados en GoSalto. Probá preguntarme por termas, parques, naturaleza, gastronomía, cafeterías, heladerías, museos, patrimonio, ocio o por el nombre de un lugar."
]);
