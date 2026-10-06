<?php
session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit();
}

require_once("../conexion.php");

$idUsuario = (int) $_SESSION["id_usuario"];
$error = "";
$exito = "";
$stmtPerfil = $conexion->prepare(
    "SELECT u.NOMBRE, u.CORREO, u.CONTRASENA, ut.TELEFONO
     FROM USUARIO u
     LEFT JOIN USUARIO_TELEFONO ut ON ut.ID_USUARIO = u.ID_USUARIO
     WHERE u.ID_USUARIO = ?
     LIMIT 1"
);
$stmtPerfil->bind_param("i", $idUsuario);
$stmtPerfil->execute();
$perfil = $stmtPerfil->get_result()->fetch_assoc();
$stmtPerfil->close();

if (!$perfil) {
    session_destroy();
    header("Location: login.php");
    exit();
}

if (empty($_SESSION["csrf_perfil"])) {
    $_SESSION["csrf_perfil"] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"] ?? "";
    $correo = $_POST["correo"] ?? "";
    $telefono = $_POST["telefono"] ?? "";
    $contrasenaActual = $_POST["contrasena_actual"] ?? "";
    $nuevaContrasena = $_POST["nueva_contrasena"] ?? "";
    $confirmarContrasena = $_POST["confirmar_contrasena"] ?? "";
    $token = $_POST["csrf_token"] ?? "";

    if (
        !is_string($nombre)
        || !is_string($correo)
        || !is_string($telefono)
        || !is_string($contrasenaActual)
        || !is_string($nuevaContrasena)
        || !is_string($confirmarContrasena)
        || !is_string($token)
        || !hash_equals($_SESSION["csrf_perfil"], $token)
    ) {
        $error = "No se pudo validar el formulario. Actualizá la página e intentá nuevamente.";
    } else {
        $nombre = trim($nombre);
        $correo = trim($correo);
        $telefono = trim($telefono);

        if (
            $nombre === ""
            || mb_strlen($nombre, "UTF-8") > 100
            || !filter_var($correo, FILTER_VALIDATE_EMAIL)
            || strlen($correo) > 150
            || $telefono === ""
            || mb_strlen($telefono, "UTF-8") > 30
        ) {
            $error = "Revisá el nombre, el correo electrónico y el teléfono ingresados.";
        } elseif ($nuevaContrasena !== "" && strlen($nuevaContrasena) < 8) {
            $error = "La nueva contraseña debe tener al menos 8 caracteres.";
        } elseif ($nuevaContrasena !== "" && $nuevaContrasena !== $confirmarContrasena) {
            $error = "La nueva contraseña y su confirmación no coinciden.";
        } elseif ($nuevaContrasena !== "" && $contrasenaActual === "") {
            $error = "Ingresá tu contraseña actual para poder cambiarla.";
        } else {
            $stmtCorreo = $conexion->prepare(
                "SELECT ID_USUARIO FROM USUARIO WHERE CORREO = ? AND ID_USUARIO <> ? LIMIT 1"
            );
            $stmtCorreo->bind_param("si", $correo, $idUsuario);
            $stmtCorreo->execute();
            $correoEnUso = $stmtCorreo->get_result()->num_rows > 0;
            $stmtCorreo->close();

            if ($correoEnUso) {
                $error = "Ese correo ya está asociado a otra cuenta.";
            } elseif (
                $nuevaContrasena !== ""
                && !(
                    password_get_info($perfil["CONTRASENA"])["algo"] !== null
                        ? password_verify($contrasenaActual, $perfil["CONTRASENA"])
                        : hash_equals($perfil["CONTRASENA"], $contrasenaActual)
                )
            ) {
                $error = "La contraseña actual no es correcta.";
            } else {
                try {
                    $conexion->begin_transaction();

                    if ($nuevaContrasena !== "") {
                        $hashContrasena = password_hash($nuevaContrasena, PASSWORD_DEFAULT);
                        $stmtActualizar = $conexion->prepare(
                            "UPDATE USUARIO SET NOMBRE = ?, CORREO = ?, CONTRASENA = ? WHERE ID_USUARIO = ?"
                        );
                        $stmtActualizar->bind_param("sssi", $nombre, $correo, $hashContrasena, $idUsuario);
                    } else {
                        $stmtActualizar = $conexion->prepare(
                            "UPDATE USUARIO SET NOMBRE = ?, CORREO = ? WHERE ID_USUARIO = ?"
                        );
                        $stmtActualizar->bind_param("ssi", $nombre, $correo, $idUsuario);
                    }
                    $stmtActualizar->execute();
                    $stmtActualizar->close();

                    $stmtTelefono = $conexion->prepare(
                        "SELECT ID_USUARIO FROM USUARIO_TELEFONO WHERE ID_USUARIO = ? LIMIT 1"
                    );
                    $stmtTelefono->bind_param("i", $idUsuario);
                    $stmtTelefono->execute();
                    $tieneTelefono = $stmtTelefono->get_result()->num_rows > 0;
                    $stmtTelefono->close();

                    if ($tieneTelefono) {
                        $stmtActualizarTelefono = $conexion->prepare(
                            "UPDATE USUARIO_TELEFONO SET TELEFONO = ? WHERE ID_USUARIO = ?"
                        );
                        $stmtActualizarTelefono->bind_param("si", $telefono, $idUsuario);
                        $stmtActualizarTelefono->execute();
                        $stmtActualizarTelefono->close();
                    } else {
                        $stmtInsertarTelefono = $conexion->prepare(
                            "INSERT INTO USUARIO_TELEFONO (ID_USUARIO, TELEFONO) VALUES (?, ?)"
                        );
                        $stmtInsertarTelefono->bind_param("is", $idUsuario, $telefono);
                        $stmtInsertarTelefono->execute();
                        $stmtInsertarTelefono->close();
                    }

                    $conexion->commit();
                    $_SESSION["nombre"] = $nombre;
                    $_SESSION["correo"] = $correo;
                    $exito = "Tus datos se actualizaron correctamente.";

                    $stmtPerfil = $conexion->prepare(
                        "SELECT u.NOMBRE, u.CORREO, u.CONTRASENA, ut.TELEFONO
                         FROM USUARIO u
                         LEFT JOIN USUARIO_TELEFONO ut ON ut.ID_USUARIO = u.ID_USUARIO
                         WHERE u.ID_USUARIO = ?
                         LIMIT 1"
                    );
                    $stmtPerfil->bind_param("i", $idUsuario);
                    $stmtPerfil->execute();
                    $perfil = $stmtPerfil->get_result()->fetch_assoc();
                    $stmtPerfil->close();
                } catch (mysqli_sql_exception $exception) {
                    $conexion->rollback();
                    error_log("No se pudo actualizar el perfil del usuario: " . $exception->getMessage());
                    $error = "No se pudieron guardar los cambios. Intentá nuevamente.";
                    $perfil["NOMBRE"] = $nombre;
                    $perfil["CORREO"] = $correo;
                    $perfil["TELEFONO"] = $telefono;
                }
            }

            if ($error !== "") {
                $perfil["NOMBRE"] = $nombre;
                $perfil["CORREO"] = $correo;
                $perfil["TELEFONO"] = $telefono;
            }
        }
    }
}

function escaparPerfil($valor)
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, "UTF-8");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#123c30">
    <title>Mi perfil | GoSalto</title>
    <link rel="stylesheet" href="../assets/css/var.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/componentes.css">
    <link rel="stylesheet" href="../assets/css/perfil.css?v=2">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
    <?php include("../includes/navbar.php"); ?>

    <main class="perfil-pagina">
        <section class="perfil-tarjeta" aria-labelledby="titulo-perfil">
            <div class="perfil-encabezado">
                <span class="perfil-icono" aria-hidden="true"><i class="fa-regular fa-id-card"></i></span>
                <div>
                    <p class="perfil-etiqueta">Tu cuenta GoSalto</p>
                    <h1 id="titulo-perfil">Configurar perfil</h1>
                    <p>Actualizá tus datos y mantené tu cuenta al día.</p>
                </div>
            </div>

            <?php if ($error !== ""): ?>
                <div class="perfil-mensaje perfil-mensaje--error" role="alert"><?= escaparPerfil($error) ?></div>
            <?php endif; ?>
            <?php if ($exito !== ""): ?>
                <div class="perfil-mensaje perfil-mensaje--exito" role="status"><?= escaparPerfil($exito) ?></div>
            <?php endif; ?>

            <form class="perfil-formulario" method="POST" action="perfil.php">
                <input type="hidden" name="csrf_token" value="<?= escaparPerfil($_SESSION["csrf_perfil"]) ?>">

                <label for="nombre">Nombre completo</label>
                <input id="nombre" type="text" name="nombre" value="<?= escaparPerfil($perfil["NOMBRE"]) ?>" maxlength="100" autocomplete="name" required>

                <label for="correo">Correo electrónico</label>
                <input id="correo" type="email" name="correo" value="<?= escaparPerfil($perfil["CORREO"]) ?>" maxlength="150" autocomplete="email" required>

                <label for="telefono">Teléfono</label>
                <input id="telefono" type="tel" name="telefono" value="<?= escaparPerfil($perfil["TELEFONO"] ?? "") ?>" maxlength="30" autocomplete="tel" required>

                <div class="perfil-separador">
                    <h2>Cambiar contraseña</h2>
                    <p>Dejá estos campos vacíos si no querés cambiarla.</p>
                </div>

                <label for="contrasena_actual">Contraseña actual</label>
                <input id="contrasena_actual" type="password" name="contrasena_actual" autocomplete="current-password">

                <label for="nueva_contrasena">Nueva contraseña</label>
                <input id="nueva_contrasena" type="password" name="nueva_contrasena" minlength="8" autocomplete="new-password">

                <label for="confirmar_contrasena">Confirmar nueva contraseña</label>
                <input id="confirmar_contrasena" type="password" name="confirmar_contrasena" minlength="8" autocomplete="new-password">

                <button class="perfil-guardar" type="submit">Guardar cambios <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></button>
            </form>
        </section>
    </main>

    <?php include("../includes/footer.php"); ?>
</body>
</html>
