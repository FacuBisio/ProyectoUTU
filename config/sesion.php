<?php

function iniciarSesionSegura(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    ini_set("session.use_strict_mode", "1");
    ini_set("session.use_only_cookies", "1");

    $conexionSegura = isset($_SERVER["HTTPS"])
        && $_SERVER["HTTPS"] !== ""
        && strtolower((string) $_SERVER["HTTPS"]) !== "off";
    session_set_cookie_params([
        "lifetime" => 0,
        "path" => "/",
        "secure" => $conexionSegura,
        "httponly" => true,
        "samesite" => "Lax",
    ]);

    if (!session_start()) {
        error_log("No se pudo iniciar la sesión.");
        http_response_code(500);
        exit("No se pudo iniciar la sesión.");
    }
}
