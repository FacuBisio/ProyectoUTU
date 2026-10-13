<?php

// Prefijo público del proyecto; url() lo usa para generar enlaces coherentes.
define("BASE_URL", "/PROYECTO%20UTU/");

function url(string $ruta): string
{
    return BASE_URL . $ruta;
}