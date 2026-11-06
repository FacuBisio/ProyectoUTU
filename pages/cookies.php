<?php
require_once(__DIR__ . "/../config/sesion.php");
iniciarSesionSegura();
require_once(__DIR__ . "/../config/config.php");

function escaparCookies($valor): string
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
    <meta name="description" content="Información sobre cookies y almacenamiento local utilizados por GoSalto.">
    <title>Política de cookies | GoSalto</title>
    <link rel="stylesheet" href="<?= escaparCookies(url('assets/css/var.css')) ?>">
    <link rel="stylesheet" href="<?= escaparCookies(url('assets/css/styles.css')) ?>">
    <link rel="stylesheet" href="<?= escaparCookies(url('assets/css/componentes.css')) ?>">
    <link rel="stylesheet" href="<?= escaparCookies(url('assets/css/legal.css?v=1')) ?>">
</head>
<body>
    <?php include(__DIR__ . "/../includes/navbar.php"); ?>

    <main class="legal-pagina" id="contenido">
        <div class="legal-contenedor">
            <header class="legal-encabezado">
                <p class="legal-etiqueta">GoSalto · SIGTUR Salto</p>
                <h1>Política de cookies</h1>
                <p class="legal-fecha">Última actualización: 6 de noviembre de 2026</p>
            </header>
            <article class="legal-documento">
                <p class="legal-aviso">Esta política describe las tecnologías identificadas en el código actual. Los servicios externos pueden aplicar sus propias prácticas cuando el navegador se conecta con ellos.</p>

                <h2>1. ¿Qué son las cookies?</h2>
                <p>Las cookies son pequeños datos que un sitio guarda en el navegador. Pueden mantener una sesión o permitir funciones solicitadas por la persona usuaria. El almacenamiento local del navegador es una tecnología distinta y no es una cookie.</p>

                <h2>2. Cookie técnica de sesión</h2>
                <p>GoSalto utiliza la cookie de sesión de PHP para conservar el estado de inicio de sesión y atender funciones que dependen de la sesión, como formularios protegidos. Es temporal y se configura para durar mientras la sesión del navegador esté activa. Tiene los atributos HttpOnly y SameSite=Lax; Secure se activa cuando el sitio usa HTTPS. El nombre lo determina la configuración del servidor PHP.</p>

                <h2>3. Preferencias de accesibilidad</h2>
                <p>Las preferencias de tema, contraste y tamaño de texto se guardan en el almacenamiento local del navegador bajo la clave <code>gosalto-preferencias-accesibilidad</code>. Permanecen en ese navegador hasta que se borren los datos del sitio o se restablezcan desde los controles disponibles. No se envían como cookie al servidor.</p>

                <h2>4. Analítica y publicidad</h2>
                <p>En el código actual no se identificó una herramienta de analítica ni de publicidad que instale cookies. Si en el futuro se incorporan, esta política deberá actualizarse.</p>

                <h2>5. Servicios externos</h2>
                <p>Mapas, clima, tipografías e iconos pueden cargarse desde OpenStreetMap, Open-Meteo, Google Fonts, Google Maps, cdnjs y unpkg, según la página y la acción realizada. Al solicitar recursos, el navegador se comunica directamente con los proveedores; estos pueden tratar datos técnicos de la conexión de acuerdo con sus políticas. Esta plataforma no controla las tecnologías que dichos proveedores utilicen.</p>

                <h2>6. Cómo gestionar el almacenamiento</h2>
                <p>La persona usuaria puede borrar cookies y almacenamiento local desde la configuración del navegador. Si se bloquea la cookie de sesión, puede dejar de funcionar el inicio de sesión y otras funciones asociadas. Borrar las preferencias locales restablece las opciones de accesibilidad al valor inicial.</p>

                <h2>7. Contacto y actualizaciones</h2>
                <p>Responsable del proyecto: Escuela Superior Catalina Harriague de Castaños. Para consultas sobre esta política, escribe a <a href="mailto:itssalto@gmail.com">itssalto@gmail.com</a>. La política se actualizará si cambian las tecnologías utilizadas.</p>
                <p>Este documento es informativo y no constituye asesoramiento jurídico.</p>
            </article>
        </div>
    </main>

    <?php include(__DIR__ . "/../includes/footer.php"); ?>
    <?php include(__DIR__ . "/../includes/chat-widget.php"); ?>
</body>
</html>
