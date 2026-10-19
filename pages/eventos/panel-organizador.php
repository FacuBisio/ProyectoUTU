<?php
session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../login.php");
    exit();
}

if ((int) ($_SESSION["id_rol"] ?? 0) !== 2) {
    http_response_code(403);
    exit("Acceso denegado.");
}

require_once("../../conexion.php");
require_once("../../config/config.php");

function escaparHistorial($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, "UTF-8");
}

$idUsuario = (int) $_SESSION["id_usuario"];
$stmt = $conexion->prepare(
    "SELECT ID_EVENTO, NOMBRE, DESCRIPCION, HORA_INI, HORA_FIN,
            DIA, MES, ANIO, DIRECCION, IMAGEN
     FROM EVENTO
     WHERE ID_USUARIO = ?
     ORDER BY ID_EVENTO DESC"
);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$publicaciones = $stmt->get_result();
$cantidadPublicaciones = $publicaciones->num_rows;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#123c30">
    <title>Panel de organizador | GoSalto</title>
    <link rel="stylesheet" href="../../assets/css/var.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/componentes.css">
    <link rel="stylesheet" href="../../assets/css/eventos.css?v=6">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        .organizador-historial {
            width: min(980px, calc(100% - 40px));
            margin: 38px auto 70px;
        }
        .organizador-historial-encabezado {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 22px;
            padding: 24px;
            border: 1px solid #e1e9e3;
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 10px 30px rgba(24, 57, 42, 0.055);
        }
        .organizador-historial-encabezado h1 {
            margin: 0 0 7px;
            color: #17382d;
            font-size: clamp(1.5rem, 3vw, 2rem);
        }
        .organizador-historial-encabezado p {
            margin: 0;
            color: #738078;
            line-height: 1.6;
        }
        .organizador-historial-acciones {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .organizador-historial-acciones a {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 15px;
            border-radius: 10px;
            background: #18744f;
            color: #fff;
            font-size: 0.85rem;
            font-weight: 750;
            text-decoration: none;
        }
        .organizador-publicaciones {
            display: grid;
            gap: 16px;
        }
        .organizador-publicacion {
            display: grid;
            grid-template-columns: minmax(0, 220px) minmax(0, 1fr);
            overflow: hidden;
            border: 1px solid #e1e9e3;
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 10px 30px rgba(24, 57, 42, 0.055);
        }
        .organizador-publicacion img {
            width: 100%;
            height: 100%;
            min-height: 190px;
            object-fit: cover;
            background: #e7eee9;
        }
        .organizador-publicacion-contenido {
            min-width: 0;
            padding: 20px;
        }
        .organizador-publicacion-contenido h2 {
            margin: 0 0 10px;
            color: #203a2e;
            font-size: 1.15rem;
        }
        .organizador-publicacion-contenido p {
            margin: 0 0 13px;
            color: #68776e;
            line-height: 1.6;
            overflow-wrap: anywhere;
        }
        .organizador-publicacion-datos {
            display: flex;
            flex-wrap: wrap;
            gap: 9px 18px;
            color: #526b5c;
            font-size: 0.82rem;
        }
        .organizador-publicacion-datos span {
            display: inline-flex;
            align-items: center;
            gap: 7px;
        }
        .organizador-publicacion-datos i {
            color: #21845c;
        }
        .organizador-historial-vacio {
            padding: 42px 24px;
            border: 1px dashed #c9d9ce;
            border-radius: 18px;
            background: #fff;
            color: #65766b;
            text-align: center;
        }
        .organizador-historial-vacio i {
            display: block;
            margin-bottom: 12px;
            color: #21845c;
            font-size: 2rem;
        }
        @media (max-width: 640px) {
            .organizador-publicacion {
                grid-template-columns: 1fr;
            }
            .organizador-publicacion img {
                height: 190px;
                min-height: 0;
            }
        }
    </style>
</head>
<body>
    <?php include("../../includes/navbar.php"); ?>

    <main class="eventos-pagina">
        <header class="eventos-portada">
            <div class="eventos-portada-contenido">
                <span class="eventos-etiqueta"><i class="fa-solid fa-clipboard-list" aria-hidden="true"></i> Espacio de organizadores</span>
                <h1>Tu historial de <span>publicaciones</span></h1>
                <p>Consulta los eventos que has compartido con la comunidad.</p>
            </div>
        </header>

        <section class="organizador-historial" aria-labelledby="titulo-historial">
            <header class="organizador-historial-encabezado">
                <div>
                    <h1 id="titulo-historial">Mis publicaciones</h1>
                    <p><?= number_format($cantidadPublicaciones, 0, ",", ".") ?> eventos publicados, ordenados del más reciente al más antiguo.</p>
                </div>
                <div class="organizador-historial-acciones">
                    <a href="eventos.php#publicar"><i class="fa-solid fa-plus" aria-hidden="true"></i> Publicar evento</a>
                </div>
            </header>

            <?php if ($cantidadPublicaciones === 0): ?>
                <div class="organizador-historial-vacio">
                    <i class="fa-regular fa-calendar" aria-hidden="true"></i>
                    <strong>Todavía no publicaste eventos.</strong>
                    <p>Cuando compartas un evento, aparecerá en este historial.</p>
                </div>
            <?php else: ?>
                <div class="organizador-publicaciones">
                    <?php while ($publicacion = $publicaciones->fetch_assoc()): ?>
                        <?php
                        $imagen = trim((string) ($publicacion["IMAGEN"] ?? ""));
                        $rutaImagen = $imagen !== "" ? __DIR__ . "/../../" . ltrim($imagen, "/\\") : "";
                        $imagenSrc = $imagen !== "" && is_file($rutaImagen)
                            ? "../../" . ltrim($imagen, "/\\")
                            : "../../assets/img/imagen-principal.jpeg";
                        $fechaEvento = sprintf(
                            "%02d/%02d%s",
                            (int) $publicacion["DIA"],
                            (int) $publicacion["MES"],
                            $publicacion["ANIO"] ? "/" . (int) $publicacion["ANIO"] : ""
                        );
                        ?>
                        <article class="organizador-publicacion">
                            <img src="<?= escaparHistorial($imagenSrc) ?>" alt="" loading="lazy">
                            <div class="organizador-publicacion-contenido">
                                <h2><?= escaparHistorial($publicacion["NOMBRE"]) ?></h2>
                                <?php if (trim((string) ($publicacion["DESCRIPCION"] ?? "")) !== ""): ?>
                                    <p><?= nl2br(escaparHistorial($publicacion["DESCRIPCION"])) ?></p>
                                <?php endif; ?>
                                <div class="organizador-publicacion-datos">
                                    <span><i class="fa-regular fa-calendar" aria-hidden="true"></i><?= escaparHistorial($fechaEvento) ?></span>
                                    <span><i class="fa-regular fa-clock" aria-hidden="true"></i><?= escaparHistorial(substr((string) $publicacion["HORA_INI"], 0, 5)) ?>–<?= escaparHistorial(substr((string) $publicacion["HORA_FIN"], 0, 5)) ?></span>
                                    <?php if (trim((string) ($publicacion["DIRECCION"] ?? "")) !== ""): ?>
                                        <span><i class="fa-solid fa-location-dot" aria-hidden="true"></i><?= escaparHistorial($publicacion["DIRECCION"]) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <?php include("../../includes/footer.php"); ?>
    <?php include("../../includes/chat-widget.php"); ?>
</body>
</html>
