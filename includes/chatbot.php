<div id="chatbot">

    <div id="chat-header">
        <span>🤖 GoSalto</span>
        <button onclick="cerrarChat()">×</button>
    </div>

    <div id="chat-mensajes">
        <div class="mensaje bot">
            ¡Hola! 👋 Soy el asistente de GoSalto.
            <br><br>
            ¿En qué puedo ayudarte?
        </div>
    </div>

    <div id="chat-input">

        <input 
            type="text" 
            id="mensaje"
            placeholder="Escribí tu pregunta..."
            onkeypress="if(event.key === 'Enter') enviarMensaje()"
        >

        <button type="button" onclick="enviarMensaje()">
    <i class="fas fa-paper-plane"></i>
</button>
    </div>

</div>

<button id="boton-chat" onclick="abrirChat()">
    <i class="fa-solid fa-comments"></i>
</button>