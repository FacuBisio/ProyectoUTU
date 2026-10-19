<?php

require_once(__DIR__ . "/config.php");

function rutaImagenLugar(int $idLugar): string
{
    static $rutas = [
        5 => "assets/img/trouville.jpeg",
        9 => "assets/img/Parques/parqueharriague.jpg",
        10 => "assets/img/Parques/parquesolari.png",
        11 => "assets/img/Parques/parqueindigena.jpg",
        12 => "assets/img/Paisajes/costaneranorte.jpeg",
        13 => "assets/img/Paisajes/cuevas.png",
        14 => "assets/img/Paisajes/roosevelt.png",
        15 => "assets/img/Historia/teatro.jpeg",
        16 => "assets/img/Historia/catedral.jpg",
        17 => "assets/img/Historia/granhotel.jpg",
        21 => "assets/img/Museos/mdelhombre.jpg",
        22 => "assets/img/Museos/gallino.jpg",
        23 => "assets/img/Museos/horacio.jpg",
        24 => "assets/img/Termas/dayman.jpeg",
        25 => "assets/img/Termas/arapey.jpg",
        26 => "assets/img/Termas/acuamania.jpg",
        27 => "assets/img/Ocio/porco.jpg",
        28 => "assets/img/Ocio/ferne.jpg",
        29 => "assets/img/Ocio/cine.jpg",
        30 => "assets/img/Restaurantes/elrancho.jpeg",
        31 => "assets/img/Restaurantes/trattoria.jpeg",
        32 => "assets/img/Restaurantes/lacaldera.jpeg",
        33 => "assets/img/ComidaRapida/trouville.jpeg",
        34 => "assets/img/ComidaRapida/burgerking.jpeg",
        35 => "assets/img/ComidaRapida/subway.jpeg",
        36 => "assets/img/Heladerias/nevada.jpeg",
        37 => "assets/img/Heladerias/premium.jpeg",
        38 => "assets/img/Heladerias/alfredito.jpeg",
        42 => "assets/img/Panaderias/dondiego.jpeg",
        43 => "assets/img/Panaderias/minerva.jpeg",
        44 => "assets/img/Panaderias/aurora.jpeg",
        45 => "assets/img/Cafeterias/jefris.jpeg",
        46 => "assets/img/Cafeterias/recova.jpeg",
        47 => "assets/img/Cafeterias/basalto.jpeg",
    ];

    return url($rutas[$idLugar] ?? "assets/img/imagen-principal.jpeg");
}
