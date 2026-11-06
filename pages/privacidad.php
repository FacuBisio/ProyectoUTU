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
    <meta name="description" content="Información sobre los datos personales que GoSalto trata, su finalidad y cómo realizar consultas.">
    <title>Privacidad | GoSalto</title>
    <link rel="stylesheet" href="<?= escaparPrivacidad(url('assets/css/var.css')) ?>">
    <link rel="stylesheet" href="<?= escaparPrivacidad(url('assets/css/styles.css')) ?>">
    <link rel="stylesheet" href="<?= escaparPrivacidad(url('assets/css/componentes.css')) ?>">
    <link rel="stylesheet" href="<?= escaparPrivacidad(url('assets/css/legal.css?v=1')) ?>">
</head>
<body>
    <?php include(__DIR__ . "/../includes/navbar.php"); ?>

    <main class="legal-pagina" id="contenido">
        <div class="legal-contenedor">
            <header class="legal-encabezado">
                <p class="legal-etiqueta">GoSalto · SIGTUR Salto</p>
                <h1>Política de privacidad</h1>
                <p class="legal-fecha">Última actualización: 6 de noviembre de 2026</p>
            </header>
            <article class="legal-documento">
                <p class="legal-aviso"><strong>Responsable:</strong> Escuela Superior Catalina Harriague de Castaños. Para consultas sobre privacidad y datos personales, escribe a <a href="mailto:itssalto@gmail.com">itssalto@gmail.com</a>.</p>

                <h2>Datos que trata el sitio</h2>
                <p>Al crear una cuenta, el sitio solicita nombre, correo electrónico, contraseña y teléfono. La contraseña se almacena como hash y no en texto legible. La cuenta también se asocia a los comentarios, reacciones, eventos publicados y lugares guardados por la persona usuaria.</p>
                <p>El formulario de contacto a organizadores guarda nombre, correo electrónico, mensaje y fecha de envío. La información se utiliza para gestionar la consulta y puede ser revisada por administradores autorizados. Evita incluir contraseñas, documentos u otra información sensible en los mensajes.</p>
                <p>Si utilizas el asistente de GoSalto, el mensaje se envía al servidor para buscar coincidencias con el catálogo de lugares. El endpoint de la aplicación no guarda el texto del chat en la base de datos.</p>

                <h2>Finalidades y acceso</h2>
                <p>Los datos se utilizan para operar las funciones del sitio, administrar cuentas, mostrar publicaciones de la comunidad, moderar contenido y responder consultas. El acceso a funciones y datos administrativos se limita según el rol asignado a cada cuenta.</p>

                <h2>Conservación y solicitudes</h2>
                <p>El sistema no define actualmente un plazo automático general de conservación. Los datos se mantienen mientras sean necesarios para las funciones del sitio y su administración. Para solicitar acceso, rectificación o eliminación de datos, escribe a <a href="mailto:itssalto@gmail.com">itssalto@gmail.com</a>, indicando qué información involucra la solicitud. La gestión se realizará según corresponda y de acuerdo con la normativa aplicable.</p>

                <h2>Ubicación, mapas y servicios externos</h2>
                <p>La ubicación del dispositivo solo se solicita si la persona pulsa el control para mostrarla. Se usa para centrar el mapa y no se guarda en la base de datos de GoSalto. Los mapas se sirven mediante Leaflet y OpenStreetMap; los datos meteorológicos se consultan a Open-Meteo. Los enlaces a Google Maps se abren cuando la persona los selecciona. El navegador se comunica con esos proveedores y se aplican sus respectivas políticas.</p>
                <p>El sitio también carga recursos de interfaz desde Google Fonts, cdnjs y unpkg. Esos proveedores reciben las solicitudes necesarias para entregar los recursos.</p>

                <h2>Sesión y preferencias</h2>
                <p>El sitio utiliza una cookie técnica de sesión de PHP para mantener la sesión mientras se usan funciones de cuenta. Se configura como HttpOnly y SameSite=Lax; el atributo Secure se activa cuando el sitio funciona sobre HTTPS. Las preferencias de accesibilidad se guardan en el almacenamiento local del navegador, no en una cookie. No se identificó en el código una herramienta de analítica ni publicidad.</p>

                <h2>Seguridad y normativa</h2>
                <p>El sitio aplica controles técnicos como contraseñas con hash, sesiones protegidas y restricciones de acceso por rol. Ningún sistema conectado a Internet puede garantizar seguridad absoluta. El tratamiento de datos personales se enmarca en la normativa uruguaya aplicable, incluida la Ley N.º 18.331. Puedes consultar a la <a href="https://www.gub.uy/unidad-reguladora-control-datos-personales" target="_blank" rel="noopener noreferrer">Unidad Reguladora y de Control de Datos Personales</a>.</p>

                <h2>Actualizaciones</h2>
                <p>Esta política puede actualizarse si cambian las funciones o los datos tratados. La versión vigente se publicará en esta página con su fecha de actualización.</p>
            </article>
        </div>
    </main>

    <?php include(__DIR__ . "/../includes/footer.php"); ?>
    <?php include(__DIR__ . "/../includes/chat-widget.php"); ?>
</body>
</html>
