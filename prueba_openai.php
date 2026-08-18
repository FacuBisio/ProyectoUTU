<?php

$apiKey = getenv("OPENAI_API_KEY");

if (!$apiKey) {
    die("❌ PHP no encuentra OPENAI_API_KEY");
}

$data = [
    "model" => "gpt-5-mini",
    "input" => "Respondé solamente: Hola, GoSalto funciona correctamente."
];

$ch = curl_init("https://api.openai.com/v1/responses");

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer " . $apiKey
]);

curl_setopt(
    $ch,
    CURLOPT_POSTFIELDS,
    json_encode($data)
);

$respuesta = curl_exec($ch);

if ($respuesta === false) {
    die("❌ Error de conexión: " . curl_error($ch));
}

$codigo = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

$resultado = json_decode($respuesta, true);

echo "<h2>Prueba de conexión con OpenAI</h2>";

echo "<p>Código HTTP: $codigo</p>";

if (isset($resultado["error"])) {

    echo "<p>❌ Error de OpenAI:</p>";

    echo "<pre>";
    echo htmlspecialchars(
        $resultado["error"]["message"] ?? "Error desconocido"
    );
    echo "</pre>";

    exit;
}

echo "<p>✅ CONEXIÓN CORRECTA</p>";

echo "<p>Respuesta:</p>";

echo "<strong>";

echo htmlspecialchars(
    $resultado["output"][0]["content"][0]["text"]
    ?? "No se recibió respuesta"
);

echo "</strong>";