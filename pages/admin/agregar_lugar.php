<?php

session_start();
require_once("../../conexion.php");

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 1) {
    die("Acceso denegado.");
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

    $sql = "INSERT INTO LUGAR
            (NOMBRE, DESCRIPCION, DIRECCION, IMAGEN, LATITUD, LONGITUD)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conexion->prepare($sql);

    $stmt->bind_param(
        "ssssdd",
        $nombre,
        $descripcion,
        $direccion,
        $imagen,
        $latitud,
        $longitud
    );

    $stmt->execute();

    $id_lugar = $conexion->insert_id;

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

    <title>Agregar Lugar</title>

</head>

<body>

<h1>Agregar Lugar</h1>

<form method="POST">

    <label>Categoría:</label>

    <select name="id_categoria" required>

        <?php while ($categoria = $categorias->fetch_assoc()) { ?>

            <option value="<?= $categoria["ID_CATEGORIA"] ?>">

                <?= htmlspecialchars($categoria["NOMBRE"]) ?>

            </option>

        <?php } ?>

    </select>

    <br><br>

    <label>Nombre:</label>

    <input 
        type="text" 
        name="nombre" 
        required
    >

    <br><br>

    <label>Descripción:</label>

    <textarea 
        name="descripcion" 
        required
    ></textarea>

    <br><br>

    <label>Dirección:</label>

    <input 
        type="text" 
        name="direccion" 
        required
    >

    <br><br>

    <label>Imagen:</label>

    <input 
        type="text" 
        name="imagen"
        placeholder="assets/img/ejemplo.jpg"
    >

    <br><br>

    <label>Latitud:</label>

    <input 
        type="text" 
        name="latitud"
    >

    <br><br>

    <label>Longitud:</label>

    <input 
        type="text" 
        name="longitud"
    >

    <br><br>

    <button type="submit">
        Guardar Lugar
    </button>

</form>

<br>

<a href="lugares.php">
    Volver a lugares
</a>

<?php include("../../includes/chat-widget.php"); ?>
</body>

</html>