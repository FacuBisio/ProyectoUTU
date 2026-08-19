<?php

header("Content-Type: application/json; charset=UTF-8");

$mensaje = trim($_POST["mensaje"] ?? "");

if ($mensaje === "") {
    echo json_encode([
        "error" => "No se recibió ningún mensaje."
    ]);
    exit;
}

$datos = [
    "model" => "qwen2.5:3b",

    "messages" => [
        [
            "role" => "system",
            "content" => "Sos el asistente turístico de GoSalto, una página sobre Salto, Uruguay.

Ayudás sobre termas, parques, paisajes, gastronomía, eventos, alojamientos, museos, patrimonio y turismo.

Respondé en español.
Sé amable y muy breve.
Respondé en máximo 2 o 3 frases.
No inventes datos.
Si no sabés algo, decí que no tenés esa información."
        ],
        [
            "role" => "user",
            "content" => $mensaje
        ]
    ],

    "stream" => false,

    "keep_alive" => "5m",

    "options" => [
        "num_predict" => 40,
        "temperature" => 0.3
    ]
];

$ch = curl_init("http://127.0.0.1:11434/api/chat");

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,

    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json"
    ],

    CURLOPT_POSTFIELDS => json_encode($datos),

    CURLOPT_CONNECTTIMEOUT => 5,
    CURLOPT_TIMEOUT => 30,

    CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4
]);

$inicio = microtime(true);

$respuesta = curl_exec($ch);

$tiempo = microtime(true) - $inicio;

if ($respuesta === false) {

    $error = curl_error($ch);

    curl_close($ch);

    echo json_encode([
        "error" => $error,
        "tiempo" => round($tiempo, 2)
    ]);

    exit;
}

$codigoHTTP = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

$resultado = json_decode($respuesta, true);

if ($codigoHTTP !== 200) {
    echo json_encode([
        "error" => "Ollama respondió con HTTP " . $codigoHTTP,
        "tiempo" => round($tiempo, 2)
    ]);
    exit;
}

if (isset($resultado["error"])) {
    echo json_encode([
        "error" => $resultado["error"],
        "tiempo" => round($tiempo, 2)
    ]);
    exit;
}

$respuestaIA = $resultado["message"]["content"] ?? "No recibí una respuesta.";

echo json_encode([
    "respuesta" => $respuestaIA,
    "tiempo" => round($tiempo, 2)
]);