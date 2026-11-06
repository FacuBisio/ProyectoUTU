<?php
// Evita insertar dos veces el chat cuando varios componentes lo incluyen.
if (defined("GOSALTO_CHAT_WIDGET_INCLUDED")) {
    return;
}
define("GOSALTO_CHAT_WIDGET_INCLUDED", true);
require_once(__DIR__ . "/../config/config.php");
?>
<!-- Widget común conectado al endpoint del asistente turístico. -->
<link rel="stylesheet" href="<?= htmlspecialchars(url('assets/css/chatbot.css?v=6'), ENT_QUOTES, 'UTF-8') ?>">

<section id="chatbot" data-endpoint="<?= htmlspecialchars(url('api/chatbot.php'), ENT_QUOTES, 'UTF-8') ?>" aria-label="Asistente de GoSalto" aria-hidden="true">
    <div id="chat-header">
        <div>
            <span>📍 Asistente GoSalto</span>
            <small>Información turística de Salto</small>
        </div>
        <button type="button" id="cerrar-chat" aria-label="Cerrar chat">×</button>
    </div>

    <div id="chat-mensajes" role="log" aria-live="polite" aria-relevant="additions">
        <div class="mensaje bot">¡Hola! 👋 Puedo ayudarte a encontrar lugares, consultar datos publicados y ver eventos próximos. ¿Qué estás buscando?</div>
        <div class="chat-sugerencias" aria-label="Consultas sugeridas">
            <button type="button" class="chat-sugerencia" data-consulta="¿Qué puedo visitar en Salto?">Lugares para visitar</button>
            <button type="button" class="chat-sugerencia" data-consulta="Busco termas">Termas</button>
            <button type="button" class="chat-sugerencia" data-consulta="¿Qué eventos hay?">Eventos</button>
            <button type="button" class="chat-sugerencia" data-consulta="Busco lugares accesibles">Accesibilidad</button>
        </div>
    </div>

    <form id="chat-input" method="POST" action="<?= htmlspecialchars(url('api/chatbot.php'), ENT_QUOTES, 'UTF-8') ?>">
        <input
            type="text"
            id="mensaje"
            name="mensaje"
            maxlength="1000"
            placeholder="Escribe tu pregunta..."
            aria-label="Escribe tu pregunta"
            autocomplete="off"
            required
        >
        <button type="submit" aria-label="Enviar mensaje">
            <span aria-hidden="true">➤</span>
        </button>
    </form>
</section>

<button type="button" id="boton-chat" aria-label="Abrir asistente de GoSalto" aria-expanded="false" aria-controls="chatbot">
    <span class="chat-launcher-icon" aria-hidden="true">
        <svg viewBox="0 0 32 32" focusable="false">
            <path d="M16 4.5c-7 0-12.5 4.7-12.5 10.6 0 3.2 1.7 6.1 4.5 8l-.9 4.1 5-2.1c1.2.3 2.5.5 3.9.5 7 0 12.5-4.7 12.5-10.5S23 4.5 16 4.5Z"></path>
            <circle cx="11" cy="15.3" r="1.25"></circle>
            <circle cx="16" cy="15.3" r="1.25"></circle>
            <circle cx="21" cy="15.3" r="1.25"></circle>
        </svg>
    </span>
    <span class="chat-launcher-pin" aria-hidden="true">
        <svg viewBox="0 0 20 20" focusable="false">
            <path d="M10 17s5-5.2 5-9a5 5 0 1 0-10 0c0 3.8 5 9 5 9Z"></path>
            <circle cx="10" cy="8" r="1.7"></circle>
        </svg>
    </span>
</button>
<script src="<?= htmlspecialchars(url('assets/js/chatbot.js?v=4'), ENT_QUOTES, 'UTF-8') ?>" defer></script>