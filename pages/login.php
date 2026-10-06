<?php
session_start();

$errorInicioSesion = ($_GET['error'] ?? '') === 'invalid';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#123c30">
    <title>Iniciar sesión | GoSalto</title>
    <link rel="stylesheet" href="../assets/css/var.css">
    <link rel="stylesheet" href="../assets/css/login.css?v=3">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body class="auth-page">
    <main class="auth-layout">
        <a class="auth-logo" href="../index.php" aria-label="GoSalto, volver al inicio">
            <img src="../assets/img/LogoGoSalto.png" alt="">
            <span>GoSalto</span>
        </a>

        <section class="auth-presentacion" aria-label="Descubrí Salto">
            <div class="auth-presentacion-fondo"></div>
            <div class="auth-presentacion-contenido">
                <span class="auth-etiqueta"><i class="fa-solid fa-location-dot" aria-hidden="true"></i> Tu próximo destino</span>
                <h1>Salto se disfruta mejor cuando lo descubrís.</h1>
                <p>Ingresá a tu cuenta y encontrá lugares, experiencias y sabores para disfrutar la ciudad.</p>
                <div class="auth-puntos">
                    <span><i class="fa-solid fa-water" aria-hidden="true"></i> Termas únicas</span>
                    <span><i class="fa-solid fa-leaf" aria-hidden="true"></i> Naturaleza cerca</span>
                    <span><i class="fa-solid fa-utensils" aria-hidden="true"></i> Sabores locales</span>
                </div>
            </div>
        </section>

        <section class="auth-panel" aria-labelledby="titulo-login">
            <div class="auth-panel-contenido">
                <a class="auth-volver-inicio" href="../index.php">
                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                    Volver al inicio
                </a>

                <div class="auth-icono" aria-hidden="true">
                    <i class="fa-regular fa-user"></i>
                </div>

                <span class="auth-saludo">¡Qué bueno verte!</span>
                <h2 id="titulo-login">Iniciar sesión</h2>
                <p class="auth-descripcion">Ingresá tus datos para seguir explorando Salto.</p>

                <?php if ($errorInicioSesion) { ?>
                    <div class="auth-error" role="alert">
                        <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                        El correo o la contraseña no son correctos. Revisalos e intentá nuevamente.
                    </div>
                <?php } ?>

                <form class="auth-form" action="verificacionusuario.php" method="POST">
                    <label for="correo">Correo electrónico</label>
                    <div class="auth-campo">
                        <i class="fa-regular fa-envelope" aria-hidden="true"></i>
                        <input
                            id="correo"
                            type="email"
                            name="correo"
                            placeholder="nombre@ejemplo.com"
                            autocomplete="email"
                            required
                        >
                    </div>

                    <label for="contrasena">Contraseña</label>
                    <div class="auth-campo">
                        <i class="fa-solid fa-lock" aria-hidden="true"></i>
                        <input
                            id="contrasena"
                            type="password"
                            name="contrasena"
                            placeholder="Ingresá tu contraseña"
                            autocomplete="current-password"
                            required
                        >
                    </div>

                    <button class="auth-submit" type="submit">
                        Entrar a mi cuenta
                        <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                    </button>
                </form>

                <p class="auth-registro">
                    ¿Todavía no tenés una cuenta?
                    <a href="register.php">Crear cuenta</a>
                </p>

                <div class="auth-seguridad">
                    <i class="fa-solid fa-shield-halved" aria-hidden="true"></i>
                    Tu información se mantiene protegida.
                </div>
            </div>
        </section>
    </main>
    <?php include("../includes/chat-widget.php"); ?>
</body>
</html>
