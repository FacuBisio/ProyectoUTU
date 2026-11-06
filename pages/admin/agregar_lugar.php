<?php

require_once(__DIR__ . "/../../config/sesion.php");
iniciarSesionSegura();
require_once("../../conexion.php");

if (!isset($_SESSION["id_usuario"]) || (int) ($_SESSION["id_rol"] ?? 0) !== 1) {
    http_response_code(403);
    die("Acceso denegado.");
}

if (empty($_SESSION["csrf_admin_lugares"])) {
    $_SESSION["csrf_admin_lugares"] = bin2hex(random_bytes(32));
}

$categorias = $conexion->query(
    "SELECT ID_CATEGORIA, NOMBRE 
     FROM CATEGORIA 
     ORDER BY NOMBRE"
);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST["csrf_token"] ?? "";
    if (!is_string($token) || !hash_equals($_SESSION["csrf_admin_lugares"], $token)) {
        http_response_code(400);
        exit("La solicitud venció o no es válida. Recarga la página e inténtalo de nuevo.");
    }

    $nombre = $_POST["nombre"] ?? null;
    $descripcion = $_POST["descripcion"] ?? null;
    $direccion = $_POST["direccion"] ?? null;
    $imagen = $_POST["imagen"] ?? null;
    $horario = $_POST["horario"] ?? null;
    $precio = $_POST["precio"] ?? null;
    $telefono = $_POST["telefono"] ?? null;
    $sitioWeb = $_POST["sitio_web"] ?? null;
    $accesibleRecibido = $_POST["accesible"] ?? "";
    $latitudRecibida = $_POST["latitud"] ?? null;
    $longitudRecibida = $_POST["longitud"] ?? null;
    $idCategoriaRecibido = $_POST["id_categoria"] ?? null;
    $idCategoria = is_string($idCategoriaRecibido)
        ? filter_var($idCategoriaRecibido, FILTER_VALIDATE_INT)
        : false;

    if (
        !is_string($nombre)
        || !is_string($descripcion)
        || !is_string($direccion)
        || !is_string($imagen)
        || !is_string($horario)
        || !is_string($precio)
        || !is_string($telefono)
        || !is_string($sitioWeb)
        || !is_string($accesibleRecibido)
        || !is_string($latitudRecibida)
        || !is_string($longitudRecibida)
        || !$idCategoria
    ) {
        http_response_code(400);
        exit("Revisa los datos del lugar y selecciona una categoría válida.");
    }

    $nombre = trim($nombre);
    $descripcion = trim($descripcion);
    $direccion = trim($direccion);
    $imagen = trim($imagen);
    $horario = trim($horario);
    $precio = trim($precio);
    $telefono = trim($telefono);
    $sitioWeb = trim($sitioWeb);
    $latitudRecibida = trim($latitudRecibida);
    $longitudRecibida = trim($longitudRecibida);
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
        || ($sitioWeb !== "" && (!filter_var($sitioWeb, FILTER_VALIDATE_URL) || !preg_match('/^https?:\/\//i', $sitioWeb)))
        || !in_array($accesibleRecibido, ["", "0", "1"], true)
        || !$coordenadasValidas
    ) {
        http_response_code(400);
        exit("Revisa los datos, el sitio web, la accesibilidad y las coordenadas del lugar.");
    }

    $descripcion = $descripcion !== "" ? $descripcion : null;
    $imagen = $imagen !== "" ? $imagen : null;
    $horario = $horario !== "" ? $horario : null;
    $precio = $precio !== "" ? $precio : null;
    $telefono = $telefono !== "" ? $telefono : null;
    $sitioWeb = $sitioWeb !== "" ? $sitioWeb : null;
    $accesible = $accesibleRecibido === "" ? null : (int) $accesibleRecibido;
    $conexion->begin_transaction();

    try {
        $stmtLugar = $conexion->prepare(
            "INSERT INTO LUGAR
                (NOMBRE, DESCRIPCION, DIRECCION, IMAGEN, HORARIO, PRECIO, TELEFONO, SITIO_WEB, ACCESIBLE, LATITUD, LONGITUD, ACTUALIZADO_EN)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)"
        );
        $stmtLugar->bind_param(
            "ssssssssidd",
            $nombre,
            $descripcion,
            $direccion,
            $imagen,
            $horario,
            $precio,
            $telefono,
            $sitioWeb,
            $accesible,
            $latitud,
            $longitud
        );
        $stmtLugar->execute();
        $idLugar = $conexion->insert_id;

        $stmtCategoria = $conexion->prepare(
            "INSERT INTO LUGAR_CATEGORIA (ID_LUGAR, ID_CATEGORIA) VALUES (?, ?)"
        );
        $stmtCategoria->bind_param("ii", $idLugar, $idCategoria);
        $stmtCategoria->execute();
        $conexion->commit();
    } catch (mysqli_sql_exception $error) {
        $conexion->rollback();
        error_log("No se pudo guardar el lugar y su categoría: " . $error->getMessage());
        http_response_code(500);
        exit("No se pudo guardar el lugar. Verifica la categoría e inténtalo de nuevo.");
    }

    header("Location: lugares.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#163d30">
    <title>Agregar lugar | GoSalto</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="../../assets/css/admin-panel.css?v=2">
    <link rel="stylesheet" href="../../assets/css/admin-crud.css?v=3">
</head>

<body>
<div class="admin-crud">
    <header class="admin-crud-nav">
        <a class="admin-crud-marca" href="../../index.php"><span>G</span> GoSalto <small>Administración</small></a>
        <nav aria-label="Administración">
            <a href="panel.php">Panel</a>
            <a href="usuarios.php">Usuarios</a>
            <a href="lugares.php" aria-current="page">Lugares</a>
            <a href="eventos.php">Eventos</a>
            <a href="comentarios.php">Comentarios</a>
            <a href="consultas.php">Consultas</a>
        </nav>
        <a class="admin-crud-salir" href="../logout.php">Cerrar sesión</a>
    </header>

    <main class="admin-crud-contenido admin-crud-contenido--formulario">
        <div class="admin-crud-migas"><a href="panel.php">Panel</a><span>/</span><a href="lugares.php">Lugares</a><span>/</span><span>Agregar</span></div>
        <section class="admin-crud-encabezado">
            <div>
                <p class="admin-crud-etiqueta">Amplía el catálogo</p>
                <h1>Agregar un lugar</h1>
                <p>Completa la información para que todos puedan descubrir este destino.</p>
            </div>
        </section>

        <section class="admin-crud-form-card" aria-labelledby="titulo-formulario">
            <div class="admin-crud-form-intro">
                <span class="admin-crud-form-icono" aria-hidden="true">＋</span>
                <div><h2 id="titulo-formulario">Información del lugar</h2><p>Los campos marcados con <span aria-hidden="true">*</span> son obligatorios.</p></div>
            </div>
            <form class="admin-crud-form" method="POST">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION["csrf_admin_lugares"], ENT_QUOTES, "UTF-8") ?>">
                <div class="admin-crud-campos">
                    <div class="admin-crud-campo">
                        <label for="id_categoria">Categoría <span aria-hidden="true">*</span></label>
                        <select id="id_categoria" name="id_categoria" required>
                            <?php while ($categoria = $categorias->fetch_assoc()): ?>
                                <option value="<?= (int) $categoria["ID_CATEGORIA"] ?>"><?= htmlspecialchars($categoria["NOMBRE"], ENT_QUOTES, "UTF-8") ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="admin-crud-campo">
                        <label for="nombre">Nombre del lugar <span aria-hidden="true">*</span></label>
                        <input id="nombre" type="text" name="nombre" maxlength="150" placeholder="Ej.: Parque Harriague" autocomplete="off" required>
                    </div>
                    <div class="admin-crud-campo admin-crud-campo--ancho">
                        <label for="descripcion">Descripción <span aria-hidden="true">*</span></label>
                        <textarea id="descripcion" name="descripcion" rows="4" placeholder="Cuenta qué hace especial a este lugar..." required></textarea>
                    </div>
                    <div class="admin-crud-campo admin-crud-campo--ancho">
                        <label for="direccion">Dirección <span aria-hidden="true">*</span></label>
                        <input id="direccion" type="text" name="direccion" maxlength="200" placeholder="Calle, zona o referencia" required>
                    </div>
                    <div class="admin-crud-campo">
                        <label for="horario">Horario</label>
                        <input id="horario" type="text" name="horario" maxlength="255" placeholder="Ej.: Mar-Dom, 10:00 a 18:00">
                    </div>
                    <div class="admin-crud-campo">
                        <label for="precio">Precio o costo</label>
                        <input id="precio" type="text" name="precio" maxlength="100" placeholder="Ej.: Entrada gratuita">
                    </div>
                    <div class="admin-crud-campo">
                        <label for="telefono">Teléfono</label>
                        <input id="telefono" type="tel" name="telefono" maxlength="30" placeholder="Número de contacto">
                    </div>
                    <div class="admin-crud-campo">
                        <label for="sitio_web">Sitio web</label>
                        <input id="sitio_web" type="url" name="sitio_web" maxlength="255" placeholder="https://...">
                    </div>
                    <div class="admin-crud-campo">
                        <label for="accesible">Accesibilidad física</label>
                        <select id="accesible" name="accesible">
                            <option value="">Sin información</option>
                            <option value="1">Accesible</option>
                            <option value="0">No accesible</option>
                        </select>
                    </div>
                    <div class="admin-crud-campo admin-crud-campo--ancho">
                        <label for="imagen">Ruta de la imagen</label>
                        <input id="imagen" type="text" name="imagen" maxlength="255" placeholder="assets/img/ejemplo.jpg">
                        <small>Introduce una ruta dentro de la carpeta de imágenes del sitio.</small>
                    </div>
                    <div class="admin-crud-campo">
                        <label for="latitud">Latitud</label>
                        <input id="latitud" type="number" name="latitud" step="any" min="-90" max="90" placeholder="-31.3977508">
                    </div>
                    <div class="admin-crud-campo">
                        <label for="longitud">Longitud</label>
                        <input id="longitud" type="number" name="longitud" step="any" min="-180" max="180" placeholder="-57.9623953">
                    </div>
                </div>
                <div class="admin-crud-form-acciones">
                    <a class="admin-crud-boton-secundario" href="lugares.php">Cancelar</a>
                    <button class="admin-crud-boton-principal" type="submit">Guardar lugar <span aria-hidden="true">→</span></button>
                </div>
            </form>
        </section>
        <footer class="admin-crud-pie"><span>GoSalto <span aria-hidden="true">·</span> Panel de administración</span><a href="lugares.php">Volver a lugares <span aria-hidden="true">→</span></a></footer>
    </main>
</div>

<?php include("../../includes/chat-widget.php"); ?>
</body>

</html>