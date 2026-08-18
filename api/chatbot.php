<?php

header("Content-Type: application/json; charset=UTF-8");


$mensaje = $_POST["mensaje"] ?? "";


if (trim($mensaje) === "") {

    echo json_encode([
        "error" => "No se recibió ningún mensaje."
    ]);

    exit;
}


$datos = [

    "model" => "llama3.2:1b",

    "messages" => [

        [
            "role" => "system",

            "content" =>
            "Sos el asistente virtual de GoSalto.

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

            "content" => $mensaje
        ]

    ],

    "stream" => false
];


$ch = curl_init(
    "http://localhost:11434/api/chat"
);


curl_setopt(
    $ch,
    CURLOPT_RETURNTRANSFER,
    true
);


curl_setopt(
    $ch,
    CURLOPT_POST,
    true
);


curl_setopt(
    $ch,
    CURLOPT_HTTPHEADER,
    [
        "Content-Type: application/json"
    ]
);


curl_setopt(
    $ch,
    CURLOPT_POSTFIELDS,
    json_encode($datos)
);


$respuesta = curl_exec($ch);


if ($respuesta === false) {

    echo json_encode([
        "error" => curl_error($ch)
    ]);

    curl_close($ch);

    exit;
}


curl_close($ch);


$resultado =
    json_decode($respuesta, true);


if (isset($resultado["error"])) {

    echo json_encode([
        "error" => $resultado["error"]
    ]);

    exit;
}


$respuestaIA =
    $resultado["message"]["content"]
    ?? "No recibí una respuesta.";


echo json_encode([

    "respuesta" => $respuestaIA

]);