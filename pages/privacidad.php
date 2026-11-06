<?php
require_once(__DIR__ . "/../config/sesion.php");
iniciarSesionSegura();
require_once(__DIR__ . "/../config/config.php");

function escaparPrivacidad($valor): string
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
    <meta name="description" content="Información sobre los datos que GoSalto utiliza para responder consultas y ofrecer sus funciones turísticas.">
    <title>Privacidad | GoSalto</title>
    <link rel="stylesheet" href="<?= escaparPrivacidad(url('assets/css/var.css')) ?>">
    <link rel="stylesheet" href="<?= escaparPrivacidad(url('assets/css/styles.css')) ?>">
    <link rel="stylesheet" href="<?= escaparPrivacidad(url('assets/css/componentes.css')) ?>">
    <link rel="stylesheet" href="<?= escaparPrivacidad(url('assets/css/sobre-sigtur.css')) ?>">
</head>
<body>
    <?php include(__DIR__ . "/../includes/navbar.php"); ?>

    <main class="acerca-pagina" id="contenido">
        <section class="acerca-contenido" aria-labelledby="privacidad-titulo">
            <div class="acerca-introduccion">
                <span class="acerca-subtitulo">Información clara</span>
                <h1 id="privacidad-titulo">Privacidad</h1>
                <p>Esta página explica qué datos maneja GoSalto en las funciones actuales del sitio.</p>
            </div>

            <section aria-labelledby="datos-contacto">
                <h2 id="datos-contacto">Formulario de contacto</h2>
                <p>Cuando envías una consulta a los organizadores, guardamos el nombre, correo electrónico, mensaje y fecha de envío en la base de datos del sitio. El equipo administrador puede leer la consulta y actualizar su estado para darle seguimiento.</p>
                <p>Usamos esos datos únicamente para revisar y responder la consulta. No escribas contraseñas, documentos ni información financiera en el mensaje. La consulta se conserva mientras sea necesaria para gestionarla; puedes pedir su eliminación escribiendo a <a href="mailto:gosaltouy@gmail.com">gosaltouy@gmail.com</a>.</p>
            </section>

            <section aria-labelledby="cuenta-privacidad">
                <h2 id="cuenta-privacidad">Cuenta y participación</h2>
                <p>Si creas una cuenta, el sitio almacena nombre, correo, contraseña protegida mediante hash y teléfono. Los comentarios, reacciones, eventos publicados y lugares guardados se asocian a la cuenta para ofrecer esas funciones y moderar la comunidad.</p>
                <p>El acceso a estas funciones y a los datos administrativos está limitado según el rol de la cuenta.</p>
            </section>

            <section aria-labelledby="ubicacion-privacidad">
                <h2 id="ubicacion-privacidad">Ubicación, mapas y servicios externos</h2>
                <p>La ubicación del navegador solo se solicita cuando pulsas “Mostrar mi ubicación”. Se usa para centrar el mapa en tu dispositivo y no se guarda en la base de datos de GoSalto. El navegador puede requerir una conexión HTTPS para permitir esta función.</p>
                <p>Los mapas y sus imágenes se cargan desde OpenStreetMap; el clima se consulta a Open-Meteo. Al cargar esos servicios, tu navegador se comunica directamente con sus proveedores y se aplican sus respectivas políticas de privacidad.</p>
            </section>

            <section aria-labelledby="consultas-privacidad">
                <h2 id="consultas-privacidad">Consultas y solicitudes</h2>
                <p>Para consultar, corregir o solicitar la eliminación de datos enviados al formulario o asociados a una cuenta, escribe a <a href="mailto:gosaltouy@gmail.com">gosaltouy@gmail.com</a> e indica a qué datos se refiere la solicitud. El acceso a los mensajes guardados está restringido al panel administrativo.</p>
            </section>
        </section>
    </main>

    <?php include(__DIR__ . "/../includes/footer.php"); ?>
    <?php include(__DIR__ . "/../includes/chat-widget.php"); ?>
</body>
</html>
