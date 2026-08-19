<?php

header("Content-Type: application/json; charset=UTF-8");

$datos = [
    "model" => "llama3.2:1b",

    "messages" => [
        [
            "role" => "system",
            "content" => "Sos el asistente virtual de GoSalto.

GoSalto es una página turística sobre Salto, Uruguay.

Ayudás a los usuarios con información sobre:
termas,
parques,
paisajes,
gastronomía,
eventos,
alojamientos,
museos,
patrimonio
y lugares turísticos de Salto.

Respondé siempre en español.

Sé amable, claro y breve.

No inventes información.
Si no conocés un dato, decí que no tenés esa información."
        ],

        [
            "role" => "user",
            "content" => "Hola"
        ]
    ],

    "stream" => false
];

$ch = curl_init(
    "http://127.0.0.1:11434/api/chat"
);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,

    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json"
    ],

    CURLOPT_POSTFIELDS => json_encode($datos),

    CURLOPT_CONNECTTIMEOUT => 5,
    CURLOPT_TIMEOUT => 60,

    CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4
]);

$inicio = microtime(true);

$respuesta = curl_exec($ch);

$tiempo = microtime(true) - $inicio;

if ($respuesta === false) {

    echo json_encode([
        "error" => curl_error($ch),
        "tiempo" => round($tiempo, 2)
    ]);

    curl_close($ch);
    exit;
}

curl_close($ch);

echo json_encode([
    "respuesta" => json_decode($respuesta, true),
    "tiempo" => round($tiempo, 2)
]);