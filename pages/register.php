<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#123c30">
    <title>Crear cuenta | GoSalto</title>
    <link rel="stylesheet" href="../assets/css/var.css">
    <link rel="stylesheet" href="../assets/css/login.css?v=4">
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
                <span class="auth-etiqueta"><i class="fa-solid fa-location-dot" aria-hidden="true"></i> Viví Salto a tu manera</span>
                <h1>Todo lo que te gusta de Salto, en un solo lugar.</h1>
                <p>Creá tu cuenta y empezá a descubrir lugares, experiencias y sabores para disfrutar la ciudad.</p>
                <div class="auth-puntos">
                    <span><i class="fa-solid fa-water" aria-hidden="true"></i> Termas únicas</span>
                    <span><i class="fa-solid fa-leaf" aria-hidden="true"></i> Naturaleza cerca</span>
                    <span><i class="fa-solid fa-utensils" aria-hidden="true"></i> Sabores locales</span>
                </div>
            </div>
        </section>

        <section class="auth-panel" aria-labelledby="titulo-registro">
            <div class="auth-panel-contenido">
                <a class="auth-volver-inicio" href="../index.php">
                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                    Volver al inicio
                </a>

                <div class="auth-icono" aria-hidden="true">
                    <i class="fa-solid fa-user-plus"></i>
                </div>

                <span class="auth-saludo">Tu próxima aventura empieza acá</span>
                <h2 id="titulo-registro">Crear cuenta</h2>
                <p class="auth-descripcion">Completá tus datos para empezar a explorar Salto.</p>

                <form class="auth-form" action="guardarusuarios.php" method="POST">
                    <label for="nombre">Nombre completo</label>
                    <div class="auth-campo">
                        <i class="fa-regular fa-user" aria-hidden="true"></i>
                        <input
                            id="nombre"
                            type="text"
                            name="nombre"
                            placeholder="Tu nombre"
                            autocomplete="name"
                            required
                        >
                    </div>

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
                            placeholder="Al menos 8 caracteres"
                            autocomplete="new-password"
                            minlength="8"
                            required
                        >
                    </div>

                    <label for="telefono">Teléfono</label>
                    <div class="auth-campo">
                        <i class="fa-solid fa-phone" aria-hidden="true"></i>
                        <input
                            id="telefono"
                            type="tel"
                            name="telefono"
                            placeholder="Tu número de teléfono"
                            autocomplete="tel"
                            required
                        >
                    </div>

                    <button class="auth-submit" type="submit">
                        Crear mi cuenta
                        <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                    </button>
                </form>

                <p class="auth-registro">
                    ¿Ya tenés una cuenta?
                    <a href="login.php">Iniciar sesión</a>
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
