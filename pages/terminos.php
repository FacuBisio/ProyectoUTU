<?php
require_once(__DIR__ . "/../config/sesion.php");
iniciarSesionSegura();
require_once(__DIR__ . "/../config/config.php");

function escaparTerminos($valor): string
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
    <meta name="description" content="Términos de uso de la plataforma turística GoSalto, SIGTUR Salto.">
    <title>Términos y condiciones | GoSalto</title>
    <link rel="stylesheet" href="<?= escaparTerminos(url('assets/css/var.css')) ?>">
    <link rel="stylesheet" href="<?= escaparTerminos(url('assets/css/styles.css')) ?>">
    <link rel="stylesheet" href="<?= escaparTerminos(url('assets/css/componentes.css')) ?>">
    <link rel="stylesheet" href="<?= escaparTerminos(url('assets/css/legal.css?v=1')) ?>">
</head>
<body>
    <?php include(__DIR__ . "/../includes/navbar.php"); ?>

    <main class="legal-pagina" id="contenido">
        <div class="legal-contenedor">
            <header class="legal-encabezado">
                <p class="legal-etiqueta">GoSalto · SIGTUR Salto</p>
                <h1>Términos y condiciones de uso</h1>
                <p class="legal-fecha">Última actualización: 6 de noviembre de 2026</p>
            </header>
            <article class="legal-documento">
                <p class="legal-aviso"><strong>Responsable del proyecto:</strong> Escuela Superior Catalina Harriague de Castaños. Contacto: <a href="mailto:itssalto@gmail.com">itssalto@gmail.com</a>.</p>

                <h2>1. Finalidad del sitio</h2>
                <p>GoSalto, también denominado SIGTUR Salto, es una plataforma web de carácter informativo para consultar lugares, gastronomía y eventos de Salto, Uruguay. Algunas funciones requieren crear una cuenta. El sitio no procesa reservas ni pagos.</p>

                <h2>2. Uso adecuado</h2>
                <p>La persona usuaria se compromete a utilizar el sitio de forma lícita, no interferir con su funcionamiento, no intentar acceder sin autorización a cuentas o datos y no publicar contenido ilícito, engañoso, ofensivo o que infrinja derechos de terceros.</p>

                <h2>3. Cuentas y publicaciones</h2>
                <p>Quien crea una cuenta debe proporcionar datos válidos y mantener sus credenciales en reserva. Las publicaciones, comentarios y reacciones se asocian a la cuenta que los envía. El equipo administrador puede revisar o retirar contenido y gestionar cuentas según las funciones disponibles.</p>

                <h2>4. Información turística y eventos</h2>
                <p>La información del catálogo y los eventos puede cambiar o estar desactualizada. Se recomienda confirmar directamente con los establecimientos u organizadores los horarios, precios, disponibilidad y demás condiciones antes de tomar decisiones. GoSalto no garantiza la exactitud o vigencia de información proporcionada por terceros.</p>

                <h2>5. Propiedad intelectual y recursos de terceros</h2>
                <p>Los contenidos originales de GoSalto se comparten bajo la licencia indicada en el pie del sitio. Los nombres, marcas, fotografías, mapas y otros recursos pertenecientes a terceros mantienen sus respectivas condiciones y deben utilizarse conforme a ellas.</p>

                <h2>6. Disponibilidad y servicios externos</h2>
                <p>El sitio se ofrece según su disponibilidad y puede verse afectado por mantenimiento, fallas técnicas o interrupciones de proveedores externos. Mapas, clima y enlaces de navegación dependen de servicios de terceros sujetos a sus propias políticas y condiciones.</p>

                <h2>7. Privacidad</h2>
                <p>El tratamiento de datos personales se describe en la <a href="<?= escaparTerminos(url('pages/privacidad.php')) ?>">Política de privacidad</a>. Las cookies y tecnologías de almacenamiento del navegador se explican en la <a href="<?= escaparTerminos(url('pages/cookies.php')) ?>">Política de cookies</a>.</p>

                <h2>8. Cambios y contacto</h2>
                <p>Estos términos pueden actualizarse cuando cambien el sitio o sus funciones. La versión vigente estará disponible en esta página. Para consultas, escribe a <a href="mailto:itssalto@gmail.com">itssalto@gmail.com</a>.</p>
                <p>Este documento es información general del proyecto y no constituye asesoramiento jurídico.</p>
            </article>
        </div>
    </main>

    <?php include(__DIR__ . "/../includes/footer.php"); ?>
    <?php include(__DIR__ . "/../includes/chat-widget.php"); ?>
</body>
</html>
