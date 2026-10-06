<?php
if (defined("GOSALTO_CHAT_WIDGET_INCLUDED")) {
    return;
}
define("GOSALTO_CHAT_WIDGET_INCLUDED", true);
require_once(__DIR__ . "/../config/config.php");
?>
<link rel="stylesheet" href="<?= htmlspecialchars(url('assets/css/chatbot.css?v=4'), ENT_QUOTES, 'UTF-8') ?>">

<section id="chatbot" data-endpoint="<?= htmlspecialchars(url('api/chatbot.php'), ENT_QUOTES, 'UTF-8') ?>" aria-label="Asistente de GoSalto" aria-hidden="true">
    <div id="chat-header">
        <span>📍 Asistente GoSalto</span>
        <button type="button" id="cerrar-chat" aria-label="Cerrar chat">×</button>
    </div>

    <div id="chat-mensajes" aria-live="polite" aria-relevant="additions">
        <div class="mensaje bot">¡Hola! 👋 Soy el asistente de GoSalto.<br><br>¿En qué puedo ayudarte?</div>
    </div>

    <form id="chat-input">
        <input
            type="text"
            id="mensaje"
            name="mensaje"
            maxlength="1000"
            placeholder="Escribí tu pregunta..."
            aria-label="Escribí tu pregunta"
            autocomplete="off"
            required
        >
        <button type="submit" aria-label="Enviar mensaje">
            <span aria-hidden="true">➤</span>
        </button>
    </form>
</section>

<button type="button" id="boton-chat" aria-label="Abrir asistente de GoSalto" aria-expanded="false" aria-controls="chatbot">
    <span aria-hidden="true">💬</span>
</button>
<script src="<?= htmlspecialchars(url('assets/js/chatbot.js?v=3'), ENT_QUOTES, 'UTF-8') ?>" defer></script>