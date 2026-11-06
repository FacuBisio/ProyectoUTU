<?php

function validarFormularioLugar(array $datos): ?array
{
    $campos = [
        "nombre",
        "descripcion",
        "direccion",
        "imagen",
        "horario",
        "precio",
        "telefono",
        "sitio_web",
        "accesible",
        "latitud",
        "longitud",
        "id_categoria",
    ];

    foreach ($campos as $campo) {
        if (!isset($datos[$campo]) && $campo === "accesible") {
            $datos[$campo] = "";
        }
        if (!isset($datos[$campo]) || !is_string($datos[$campo])) {
            return null;
        }
    }

    $idCategoria = filter_var($datos["id_categoria"], FILTER_VALIDATE_INT);
    if ($idCategoria === false || $idCategoria < 1) {
        return null;
    }

    $nombre = trim($datos["nombre"]);
    $descripcion = trim($datos["descripcion"]);
    $direccion = trim($datos["direccion"]);
    $imagen = trim($datos["imagen"]);
    $horario = trim($datos["horario"]);
    $precio = trim($datos["precio"]);
    $telefono = trim($datos["telefono"]);
    $sitioWeb = trim($datos["sitio_web"]);
    $accesibleRecibido = $datos["accesible"];
    $latitudRecibida = trim($datos["latitud"]);
    $longitudRecibida = trim($datos["longitud"]);

    $tieneLatitud = $latitudRecibida !== "";
    $tieneLongitud = $longitudRecibida !== "";
    $latitud = $tieneLatitud && is_numeric($latitudRecibida) ? (float) $latitudRecibida : null;
    $longitud = $tieneLongitud && is_numeric($longitudRecibida) ? (float) $longitudRecibida : null;
    $coordenadasValidas = (!$tieneLatitud && !$tieneLongitud)
        || ($tieneLatitud
            && $tieneLongitud
            && $latitud !== null
            && $longitud !== null
            && $latitud >= -90
            && $latitud <= 90
            && $longitud >= -180
            && $longitud <= 180);

    if (
        $nombre === ""
        || mb_strlen($nombre, "UTF-8") > 150
        || strlen($descripcion) > 65535
        || $direccion === ""
        || mb_strlen($direccion, "UTF-8") > 200
        || mb_strlen($imagen, "UTF-8") > 255
        || mb_strlen($horario, "UTF-8") > 255
        || mb_strlen($precio, "UTF-8") > 100
        || mb_strlen($telefono, "UTF-8") > 30
        || mb_strlen($sitioWeb, "UTF-8") > 255
        || ($sitioWeb !== "" && (
            !filter_var($sitioWeb, FILTER_VALIDATE_URL)
            || !preg_match('/^https?:\/\//i', $sitioWeb)
        ))
        || !in_array($accesibleRecibido, ["", "0", "1"], true)
        || !$coordenadasValidas
    ) {
        return null;
    }

    return [
        "nombre" => $nombre,
        "descripcion" => $descripcion !== "" ? $descripcion : null,
        "direccion" => $direccion,
        "imagen" => $imagen !== "" ? $imagen : null,
        "horario" => $horario !== "" ? $horario : null,
        "precio" => $precio !== "" ? $precio : null,
        "telefono" => $telefono !== "" ? $telefono : null,
        "sitioWeb" => $sitioWeb !== "" ? $sitioWeb : null,
        "accesible" => $accesibleRecibido === "" ? null : (int) $accesibleRecibido,
        "latitud" => $latitud,
        "longitud" => $longitud,
        "idCategoria" => $idCategoria,
    ];
}
