<?php

$host = $_SERVER["HTTP_HOST"] ?? "";
if (!preg_match('/\A(?:[a-z0-9.-]+|\[[a-f0-9:]+\])(?::[0-9]{1,5})?\z/i', $host)) {
    http_response_code(400);
    exit("Host inválido.");
}

$scheme = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off") ? "https" : "http";
$basePath = str_replace("\\", "/", dirname($_SERVER["SCRIPT_NAME"] ?? "/sitemap.php"));
$basePath = rtrim($basePath, "/");
if ($basePath === ".") {
    $basePath = "";
}

$pages = [
    "",
    "pages/eventos/eventos.php",
    "pages/gastronomia/cafeterias.php",
    "pages/gastronomia/comida-rapida.php",
    "pages/gastronomia/heladerias.php",
    "pages/gastronomia/locales-top.php",
    "pages/gastronomia/recomendados.php",
    "pages/gastronomia/restaurantes.php",
    "pages/lugares/museos.php",
    "pages/lugares/ocio.php",
    "pages/lugares/paisajes.php",
    "pages/lugares/parques.php",
    "pages/lugares/patrimonio.php",
    "pages/lugares/termas.php",
];

header("Content-Type: application/xml; charset=UTF-8");
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

foreach ($pages as $page) {
    $path = $basePath . "/" . $page;
    $url = $scheme . "://" . $host . $path;
    echo "  <url><loc>" . htmlspecialchars($url, ENT_XML1 | ENT_QUOTES, "UTF-8") . "</loc></url>\n";
}

echo "</urlset>\n";
