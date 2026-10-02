<?php

session_start();
require_once("../../conexion.php");

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 1) {
    die("Acceso denegado.");
}

$id_lugar = $_GET["id"] ?? null;

if (!$id_lugar) {
    die("Lugar no encontrado.");
}

$sql = "SELECT 
            l.*,
            lc.ID_CATEGORIA
        FROM LUGAR l
        LEFT JOIN LUGAR_CATEGORIA lc
            ON l.ID_LUGAR = lc.ID_LUGAR
        WHERE l.ID_LUGAR = ?";

$stmt = $conexion->prepare($sql);

$stmt->bind_param(
    "i",
    $id_lugar
);

$stmt->execute();

$lugar = $stmt->get_result()->fetch_assoc();

if (!$lugar) {
    die("Lugar no encontrado.");
}

$categorias = $conexion->query(
    "SELECT ID_CATEGORIA, NOMBRE
     FROM CATEGORIA
     ORDER BY NOMBRE"
);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $direccion = $_POST["direccion"];
    $imagen = $_POST["imagen"];
    $latitud = $_POST["latitud"];
    $longitud = $_POST["longitud"];
    $id_categoria = $_POST["id_categoria"];

    $sql = "UPDATE LUGAR
            SET NOMBRE = ?,
                DESCRIPCION = ?,
                DIRECCION = ?,
                IMAGEN = ?,
                LATITUD = ?,
                LONGITUD = ?
            WHERE ID_LUGAR = ?";

    $stmt = $conexion->prepare($sql);

    $stmt->bind_param(
        "ssssddi",
        $nombre,
        $descripcion,
        $direccion,
        $imagen,
        $latitud,
        $longitud,
        $id_lugar
    );

    $stmt->execute();

    $sql = "DELETE FROM LUGAR_CATEGORIA
            WHERE ID_LUGAR = ?";

    $stmt = $conexion->prepare($sql);

    $stmt->bind_param(
        "i",
        $id_lugar
    );

    $stmt->execute();

    $sql = "INSERT INTO LUGAR_CATEGORIA
            (ID_LUGAR, ID_CATEGORIA)
            VALUES (?, ?)";

    $stmt = $conexion->prepare($sql);

    $stmt->bind_param(
        "ii",
        $id_lugar,
        $id_categoria
    );

    $stmt->execute();

    header("Location: lugares.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>Editar Lugar</title>

</head>

<body>

<h1>Editar Lugar</h1>

<form method="POST">

    <label>Categoría:</label>

    <select name="id_categoria" required>

        <?php while ($categoria = $categorias->fetch_assoc()) { ?>

            <option 
                value="<?= $categoria["ID_CATEGORIA"] ?>"
                <?= $categoria["ID_CATEGORIA"] == $lugar["ID_CATEGORIA"] ? "selected" : "" ?>
            >

                <?= htmlspecialchars($categoria["NOMBRE"]) ?>

            </option>

        <?php } ?>

    </select>

    <br><br>

    <label>Nombre:</label>

    <input 
        type="text"
        name="nombre"
        value="<?= htmlspecialchars($lugar["NOMBRE"]) ?>"
        required
    >

    <br><br>

    <label>Descripción:</label>

    <textarea 
        name="descripcion"
        required
    ><?= htmlspecialchars($lugar["DESCRIPCION"]) ?></textarea>

    <br><br>

    <label>Dirección:</label>

    <input
        type="text"
        name="direccion"
        value="<?= htmlspecialchars($lugar["DIRECCION"]) ?>"
        required
    >

    <br><br>

    <label>Imagen:</label>

    <input
        type="text"
        name="imagen"
        value="<?= htmlspecialchars($lugar["IMAGEN"] ?? "") ?>"
    >

    <br><br>

    <label>Latitud:</label>

    <input
        type="text"
        name="latitud"
        value="<?= htmlspecialchars($lugar["LATITUD"] ?? "") ?>"
    >

    <br><br>

    <label>Longitud:</label>

    <input
        type="text"
        name="longitud"
        value="<?= htmlspecialchars($lugar["LONGITUD"] ?? "") ?>"
    >

    <br><br>

    <button type="submit">
        Guardar cambios
    </button>

</form>

<br>

<a href="lugares.php">
    Volver a lugares
</a>

</body>

</html>