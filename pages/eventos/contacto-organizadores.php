<?php
require_once(__DIR__ . "/../../config/sesion.php");
iniciarSesionSegura();
require_once("../../conexion.php");

function escaparTexto($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

$organizadores = [];
$stmt = $conexion->prepare(
    "SELECT u.NOMBRE, ut.TELEFONO
     FROM USUARIO u
     INNER JOIN ROL r ON r.ID_ROL = u.ID_ROL
     LEFT JOIN USUARIO_TELEFONO ut ON ut.ID_USUARIO = u.ID_USUARIO
     WHERE r.NOMBRE = ?
     ORDER BY u.NOMBRE ASC"
);
$rolOrganizador = "Organizador";
$stmt->bind_param("s", $rolOrganizador);
$stmt->execute();
$resultado = $stmt->get_result();
while ($fila = $resultado->fetch_assoc()) {
    $organizadores[] = [
        'NOMBRE' => (string) ($fila['NOMBRE'] ?? ''),
        'TELEFONO' => (string) ($fila['TELEFONO'] ?? ''),
    ];
}
$stmt->close();

$mensajeExito = '';
$mensajeError = '';
if (empty($_SESSION['csrf_contacto'])) {
    $_SESSION['csrf_contacto'] = bin2hex(random_bytes(32));
}

$mensajeExito = $_SESSION['flash_contacto'] ?? '';
unset($_SESSION['flash_contacto']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || !hash_equals($_SESSION['csrf_contacto'], $token)) {
        http_response_code(400);
        exit('La solicitud venció o no es válida. Recarga la página e inténtalo de nuevo.');
    }

    $nombreRecibido = $_POST['nombre'] ?? null;
    $correoRecibido = $_POST['correo'] ?? null;
    $mensajeRecibido = $_POST['mensaje'] ?? null;
    if (!is_string($nombreRecibido) || !is_string($correoRecibido) || !is_string($mensajeRecibido)) {
        http_response_code(400);
        exit('Revisa los datos del formulario antes de enviarlo.');
    }

    $nombre = trim($nombreRecibido);
    $correo = trim($correoRecibido);
    $mensaje = trim($mensajeRecibido);
    if (
        $nombre === ''
        || mb_strlen($nombre, 'UTF-8') > 100
        || !filter_var($correo, FILTER_VALIDATE_EMAIL)
        || mb_strlen($correo, 'UTF-8') > 150
        || $mensaje === ''
        || mb_strlen($mensaje, 'UTF-8') > 5000
    ) {
        $mensajeError = 'Revisa el nombre, el correo y el mensaje. El mensaje admite hasta 5000 caracteres.';
    } else {
        try {
            $stmtConsulta = $conexion->prepare(
                'INSERT INTO CONTACTO_CONSULTA (NOMBRE, CORREO, MENSAJE) VALUES (?, ?, ?)'
            );
            $stmtConsulta->bind_param('sss', $nombre, $correo, $mensaje);
            $stmtConsulta->execute();
        } catch (mysqli_sql_exception $error) {
            error_log('No se pudo guardar una consulta de contacto: ' . $error->getMessage());
            http_response_code(500);
            exit('No se pudo guardar la consulta. Inténtalo de nuevo más tarde.');
        }

        $_SESSION['flash_contacto'] = 'Tu consulta quedó guardada y será revisada por el equipo.';
        header('Location: contacto-organizadores.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactar a los organizadores | GoSalto</title>
    <link rel="stylesheet" href="../../assets/css/var.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/componentes.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        body {
            background: linear-gradient(180deg, #f4f7f4 0%, #edf5f2 100%);
            color: #173832;
            font-family: 'Montserrat', sans-serif;
        }
        .contacto-organizadores {
            max-width: 1100px;
            margin: 2rem auto 0;
            padding: 1.5rem 1.5rem 3rem;
            min-height: calc(100vh - 260px);
        }
        .contacto-organizadores-grid {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 2rem;
            align-items: stretch;
        }
        .contacto-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 18px 40px rgba(21, 51, 45, 0.08);
            padding: 1.75rem;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .contacto-card h1, .contacto-card h2 {
            margin: 0 0 1rem;
            color: #153931;
        }
        .contacto-lista {
            display: grid;
            gap: 1rem;
            margin-top: 1rem;
        }
        .contacto-item {
            background: #f3faf6;
            border: 1px solid #dfeee8;
            border-radius: 14px;
            padding: 1rem 1.1rem;
        }
        .contacto-item strong {
            display: block;
            margin-bottom: 0.35rem;
            color: #133d35;
        }
        .contacto-item a {
            color: #0b6f64;
            text-decoration: none;
            font-weight: 600;
        }
        .contacto-form {
            display: grid;
            gap: 1rem;
        }
        .contacto-form label {
            font-weight: 600;
            color: #1d433d;
        }
        .contacto-form input,
        .contacto-form textarea {
            width: 100%;
            border: 1px solid #d5e3de;
            border-radius: 12px;
            padding: 0.9rem 1rem;
            font: inherit;
            resize: vertical;
            background: #f9fdfb;
        }
        .contacto-form button {
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #1b5650 0%, #2d7a5c 100%);
            color: white;
            padding: 0.9rem 1.2rem;
            font-weight: 700;
            cursor: pointer;
        }
        .contacto-privacidad {
            margin: 0;
            color: #53665e;
            font-size: 0.82rem;
            line-height: 1.5;
        }
        .contacto-exito {
            margin-top: 1rem;
            padding: 0.8rem 1rem;
            background: #eafaf0;
            border: 1px solid #c6ebcf;
            color: #1d613e;
            border-radius: 12px;
            font-weight: 600;
        }
        .contacto-exito--error {
            background: #fff1f0;
            border-color: #f0c3bf;
            color: #8d2921;
        }
        @media (max-width: 780px) {
            .contacto-organizadores-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include("../../includes/navbar.php"); ?>

    <main class="contacto-organizadores">
        <div class="contacto-organizadores-grid">
            <section class="contacto-card">
                <h1>Contactar a los organizadores</h1>
                <p>Estos son los contactos de las personas responsables de organizar los eventos y actividades de la comunidad.</p>

                <?php if (empty($organizadores)): ?>
                    <p>No hay organizadores disponibles en este momento.</p>
                <?php else: ?>
                    <div class="contacto-lista">
                        <?php foreach ($organizadores as $organizador): ?>
                            <div class="contacto-item">
                                <strong><?= escaparTexto($organizador['NOMBRE']) ?></strong>
                                <?php if ($organizador['TELEFONO'] !== ''): ?>
                                    <a href="tel:<?= escaparTexto($organizador['TELEFONO']) ?>"><?= escaparTexto($organizador['TELEFONO']) ?></a>
                                <?php else: ?>
                                    <span>Sin teléfono cargado</span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>

            <section class="contacto-card">
                <h2>Formulario de contacto</h2>
                <form class="contacto-form" method="POST" action="contacto-organizadores.php">
                    <input type="hidden" name="csrf_token" value="<?= escaparTexto($_SESSION['csrf_contacto']) ?>">
                    <div>
                        <label for="nombre">Nombre</label>
                        <input id="nombre" name="nombre" type="text" maxlength="100" placeholder="Tu nombre" required>
                    </div>

                    <div>
                        <label for="correo">Correo electrónico</label>
                        <input id="correo" name="correo" type="email" maxlength="150" placeholder="tuemail@ejemplo.com" required>
                    </div>

                    <div>
                        <label for="mensaje">Mensaje</label>
                        <textarea id="mensaje" name="mensaje" rows="6" maxlength="5000" placeholder="Escribe tu consulta..." required></textarea>
                    </div>

                    <p class="contacto-privacidad">Tu nombre, correo y mensaje se guardarán para que el equipo pueda responder. No incluyas datos sensibles. <a href="<?= escaparTexto(url('pages/privacidad.php')) ?>">Lee el aviso de privacidad</a>.</p>
                    <button type="submit">Enviar consulta</button>
                </form>

                <?php if ($mensajeError !== ''): ?>
                    <div class="contacto-exito contacto-exito--error" role="alert"><?= escaparTexto($mensajeError) ?></div>
                <?php endif; ?>
                <?php if ($mensajeExito !== ''): ?>
                    <div class="contacto-exito" role="status"><?= escaparTexto($mensajeExito) ?></div>
                <?php endif; ?>
            </section>
        </div>
    </main>

    <?php include("../../includes/footer.php"); ?>
    <?php include("../../includes/chat-widget.php"); ?>
</body>
</html>
