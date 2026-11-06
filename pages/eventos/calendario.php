<?php
require_once("../../conexion.php");

$idEvento = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
if (!$idEvento || $idEvento < 1) {
    http_response_code(400);
    exit("El identificador del evento no es válido.");
}

$stmtEvento = $conexion->prepare(
    "SELECT NOMBRE, DESCRIPCION, DIA, MES, ANIO, HORA_INI, HORA_FIN, DIRECCION, LATITUD, LONGITUD
     FROM EVENTO
     WHERE ID_EVENTO = ?
     LIMIT 1"
);
$stmtEvento->bind_param("i", $idEvento);
$stmtEvento->execute();
$evento = $stmtEvento->get_result()->fetch_assoc();
$stmtEvento->close();

if (!$evento || !$evento["ANIO"]) {
    http_response_code(404);
    exit("No se encontró el evento o no tiene una fecha completa.");
}

$fecha = sprintf("%04d-%02d-%02d", $evento["ANIO"], $evento["MES"], $evento["DIA"]);
$zona = new DateTimeZone("America/Montevideo");
$inicio = DateTimeImmutable::createFromFormat("!Y-m-d H:i:s", $fecha . " " . $evento["HORA_INI"], $zona);
$fin = DateTimeImmutable::createFromFormat("!Y-m-d H:i:s", $fecha . " " . $evento["HORA_FIN"], $zona);
if (!$inicio || !$fin || $inicio->format("Y-m-d") !== $fecha || $fin->format("Y-m-d") !== $fecha) {
    http_response_code(500);
    exit("La fecha u horario del evento no es válido.");
}

$textoIcs = static function (string $valor): string {
    return str_replace(
        ["\\", ";", ",", "\r\n", "\n", "\r"],
        ["\\\\", "\\;", "\\,", "\\n", "\\n", ""],
        $valor
    );
};
$escribirLineaIcs = static function (string $linea): void {
    $primeraLinea = true;
    while ($linea !== "") {
        $limite = $primeraLinea ? 75 : 74;
        $fragmento = mb_strcut($linea, 0, $limite, "UTF-8");
        echo ($primeraLinea ? "" : " ") . $fragmento . "\r\n";
        $linea = substr($linea, strlen($fragmento));
        $primeraLinea = false;
    }
};
$ubicacion = trim((string) $evento["DIRECCION"]);
if (is_numeric($evento["LATITUD"]) && is_numeric($evento["LONGITUD"])) {
    $ubicacion = trim($ubicacion . " (" . $evento["LATITUD"] . ", " . $evento["LONGITUD"] . ")");
}

header("Content-Type: text/calendar; charset=UTF-8");
header("Content-Disposition: attachment; filename=\"gosalto-evento-" . (int) $idEvento . ".ics\"");
header("X-Content-Type-Options: nosniff");
$escribirLineaIcs("BEGIN:VCALENDAR");
$escribirLineaIcs("VERSION:2.0");
$escribirLineaIcs("PRODID:-//GoSalto//Agenda//ES");
$escribirLineaIcs("CALSCALE:GREGORIAN");
$escribirLineaIcs("BEGIN:VEVENT");
$escribirLineaIcs("UID:evento-" . (int) $idEvento . "@gosalto");
$escribirLineaIcs("DTSTAMP:" . gmdate("Ymd\\THis\\Z"));
$escribirLineaIcs("DTSTART:" . $inicio->setTimezone(new DateTimeZone("UTC"))->format("Ymd\\THis\\Z"));
$escribirLineaIcs("DTEND:" . $fin->setTimezone(new DateTimeZone("UTC"))->format("Ymd\\THis\\Z"));
$escribirLineaIcs("SUMMARY:" . $textoIcs($evento["NOMBRE"]));
if (!empty($evento["DESCRIPCION"])) {
    $escribirLineaIcs("DESCRIPTION:" . $textoIcs($evento["DESCRIPCION"]));
}
if ($ubicacion !== "") {
    $escribirLineaIcs("LOCATION:" . $textoIcs($ubicacion));
}
$escribirLineaIcs("END:VEVENT");
$escribirLineaIcs("END:VCALENDAR");
