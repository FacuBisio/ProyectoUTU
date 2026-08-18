<?php

$apiKey = getenv("OPENAI_API_KEY");

if (!$apiKey) {
    die("❌ ERROR: PHP no encuentra OPENAI_API_KEY");
}

echo "✅ PHP encontró la API key.<br>";
echo "Ahora vamos a comprobar la conexión con OpenAI...<br><br>";

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

curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$respuesta = curl_exec($ch);

if ($respuesta === false) {
    die("❌ Error de conexión: " . curl_error($ch));
}

$codigo = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

$resultado = json_decode($respuesta, true);

echo "Código HTTP: " . $codigo . "<br><br>";

if (isset($resultado["error"])) {
    echo "❌ Error de OpenAI:<br>";
    echo htmlspecialchars($resultado["error"]["message"]);
    exit;
}

echo "✅ CONEXIÓN CORRECTA<br><br>";

echo "Respuesta de OpenAI:<br>";

echo htmlspecialchars(
    $resultado["output"][0]["content"][0]["text"] ?? "No se recibió texto."
);