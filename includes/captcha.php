<?php

// La clave pública se imprime en el widget; la clave secreta nunca sale del servidor.
function recaptcha_site_key(): string
{
    $siteKey = getenv("RECAPTCHA_SITE_KEY");

    if (!is_string($siteKey) || $siteKey === "") {
        $siteKey = $_SERVER["RECAPTCHA_SITE_KEY"] ?? "";
    }

    return is_string($siteKey) ? trim($siteKey) : "";
}

function recaptcha_clave_secreta(): string
{
    $secretKey = getenv("RECAPTCHA_SECRET_KEY");

    if (!is_string($secretKey) || $secretKey === "") {
        $secretKey = $_SERVER["RECAPTCHA_SECRET_KEY"] ?? "";
    }

    return is_string($secretKey) ? trim($secretKey) : "";
}

// Permite desactivar los formularios de forma segura si falta alguna clave.
function recaptcha_configurado(): bool
{
    return recaptcha_site_key() !== "" && recaptcha_clave_secreta() !== "";
}

// Consulta el token a Google; ante fallos de configuración o red, no autoriza el envío.
function recaptcha_validar(mixed $token): bool
{
    $secretKey = recaptcha_clave_secreta();
    if ($secretKey === "") {
        error_log("Google reCAPTCHA no está configurado: falta RECAPTCHA_SECRET_KEY.");
        return false;
    }

    if (!is_string($token) || trim($token) === "") {
        return false;
    }

    if (!function_exists("curl_init")) {
        error_log("No se pudo validar Google reCAPTCHA: la extensión cURL de PHP no está habilitada.");
        return false;
    }

    $curl = curl_init("https://www.google.com/recaptcha/api/siteverify");
    if ($curl === false) {
        error_log("No se pudo inicializar la validación de Google reCAPTCHA.");
        return false;
    }

    curl_setopt_array($curl, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => [
            "secret" => $secretKey,
            "response" => trim($token),
        ],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT => 10,
    ]);

    $respuesta = curl_exec($curl);
    $errorCurl = curl_error($curl);
    $estadoHttp = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if (!is_string($respuesta) || $estadoHttp !== 200) {
        error_log("Falló la verificación con Google reCAPTCHA. HTTP {$estadoHttp}. {$errorCurl}");
        return false;
    }

    $resultado = json_decode($respuesta, true);
    if (!is_array($resultado)) {
        error_log("Google reCAPTCHA devolvió una respuesta inválida.");
        return false;
    }

    return ($resultado["success"] ?? false) === true;
}
