<?php

header("Content-Type: application/json");

$apiKey = getenv("OPENAI_API_KEY");

if (!$apiKey) {
    echo json_encode([
        "error" => "No se encontró OPENAI_API_KEY"
    ]);
    exit;
}

$mensaje = $_POST["mensaje"] ?? "";

if (empty(trim($mensaje))) {
    echo json_encode([
        "error" => "Mensaje vacío"
    ]);
    exit;
}

$data = [
    "model" => "gpt-5-mini",
    "input" => [
        [
            "role" => "system",
            "content" => "Sos el asistente virtual de GoSalto, una página turística sobre Salto, Uruguay. Respondé de forma clara, amable y breve. Ayudá a los usuarios con información turística sobre Salto."
        ],
        [
            "role" => "user",
            "content" => $mensaje
        ]
    ]
];

$ch = curl_init("https://api.openai.com/v1/responses");

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_POST, true);

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer " . $apiKey
]);

curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$respuesta = curl_exec($ch);

if ($respuesta === false) {
    echo json_encode([
        "error" => curl_error($ch)
    ]);
    curl_close($ch);
    exit;
}

curl_close($ch);

$resultado = json_decode($respuesta, true);

if (isset($resultado["error"])) {
    echo json_encode([
        "error" => $resultado["error"]["message"]
    ]);
    exit;
}

echo json_encode([
    "respuesta" => $resultado["output"][0]["content"][0]["text"] ?? "No pude generar una respuesta."
]);